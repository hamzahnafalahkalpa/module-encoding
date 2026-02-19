# CLAUDE.md - Module Encoding

This file provides guidance to Claude Code when working with this module.

## Module Overview

`hanafalah/module-encoding` provides a flexible encoding/code generation system for Laravel applications. It allows generating customizable codes (like invoice numbers, transaction IDs, patient IDs, etc.) with configurable patterns including:

- **Alphanumeric** prefixes/suffixes
- **Auto-incrementing** sequences with dynamic length expansion
- **Date-based** components with multiple format options
- **Configurable separators** between code segments
- **Period-based reset** (daily, monthly, yearly)

## CRITICAL: Memory Exhaustion Warning

**This module uses `registers(['*'])` in its ServiceProvider, which can cause memory issues.**

The ServiceProvider at `src/ModuleEncodingServiceProvider.php` calls:
```php
$this->registerMainClass(ModuleEncoding::class)
    ->registerCommandService(Providers\CommandServiceProvider::class)
    ->registers(['*']);
```

While `registers(['*'])` in recent versions of `laravel-support` only registers SAFE methods, be aware that:
- If you need to extend this module, avoid calling `registers(['Schema'])` explicitly
- The `ModuleEncoding` class extends `PackageManagement` which uses `HasModelConfiguration` trait
- Schema classes (`Schemas/Encoding.php`, `Schemas/ModelHasEncoding.php`) extend `PackageManagement`/`Unicode`

**Safe pattern for extending:**
```php
// DON'T add more registers() calls if extending
// DON'T call Schema methods during boot
```

## Architecture Overview

```
module-encoding/
├── assets/
│   ├── config/
│   │   └── config.php              # Module configuration
│   └── database/
│       └── migrations/
│           └── 2024_10_20_*.php    # ModelHasEncoding table migration
├── src/
│   ├── Commands/
│   │   ├── EnvironmentCommand.php  # Base command class
│   │   └── InstallMakeCommand.php  # php artisan module-encoding:install
│   ├── Concerns/
│   │   └── HasEncoding.php         # Trait for models using encodings
│   ├── Contracts/
│   │   ├── Data/                   # Data Transfer Object contracts
│   │   ├── Schemas/                # Schema contracts
│   │   └── ModuleEncoding.php      # Main contract
│   ├── Data/                       # DTOs using spatie/laravel-data
│   │   ├── EncodingData.php
│   │   ├── ModelHasEncodingData.php
│   │   ├── ModelHasEncodingPropsData.php
│   │   ├── SeparatorData.php
│   │   └── StructureData.php
│   ├── Facades/
│   │   └── ModuleEncoding.php      # Facade for accessing module
│   ├── Models/
│   │   └── Encoding/
│   │       ├── Encoding.php        # Extends Unicode (polymorphic)
│   │       └── ModelHasEncoding.php # Pivot model with props
│   ├── Providers/
│   │   └── CommandServiceProvider.php
│   ├── Resources/                  # API Resources
│   │   ├── Encoding/
│   │   │   └── ViewEncoding.php
│   │   └── ModelHasEncoding/
│   │       └── ViewModelHasEncoding.php
│   ├── Schemas/
│   │   ├── Encoding.php            # Encoding business logic
│   │   └── ModelHasEncoding.php    # ModelHasEncoding business logic
│   ├── ModuleEncoding.php          # Main class (extends PackageManagement)
│   └── ModuleEncodingServiceProvider.php
└── composer.json
```

## Key Classes

### HasEncoding Trait

**Location:** `src/Concerns/HasEncoding.php`

The primary trait to add encoding functionality to any Eloquent model.

**CRITICAL:** Contains a static property `$__should_reset` that tracks reset state. In Laravel Octane environments, be cautious as this persists between requests.

**Key Methods:**

| Method | Purpose | Notes |
|--------|---------|-------|
| `hasEncoding($label, $is_update)` | Generate code for a given label | Returns generated code string |
| `generateCode($label, $is_update)` | Core code generation logic | Uses cached encoding configs |
| `getEncodingData($label)` | Get encoding config by label | From `config('module-encoding.encodings')` |

**Relationships provided:**
- `modelHasEncoding()` - morphOne relationship
- `modelHasEncodings()` - morphMany relationship
- `encoding()` - hasOneThrough relationship
- `encodings()` - belongsToMany relationship

**Usage:**
```php
use Hanafalah\ModuleEncoding\Concerns\HasEncoding;

class Invoice extends Model
{
    use HasEncoding;

    protected static function booted()
    {
        static::creating(function ($invoice) {
            $invoice->code = static::hasEncoding('invoice_code');
        });
    }
}
```

### Encoding Model

**Location:** `src/Models/Encoding/Encoding.php`

Extends the polymorphic `Unicode` model from `laravel-support`. Stores encoding definitions with:
- `name` - Display name
- `label` - Unique identifier for config lookup
- Uses `unicodes` table (polymorphic)

### ModelHasEncoding Model

**Location:** `src/Models/Encoding/ModelHasEncoding.php`

Pivot model linking encodings to any model type:
- Uses ULIDs as primary key
- Stores `reference_id`, `reference_type` for polymorphic relationship
- `value` - The generated code value
- `props` - JSON field storing structure and separator config (uses `HasProps` trait)

### Schema Classes

**Encoding Schema:** `src/Schemas/Encoding.php`
- Extends `Unicode` schema from `laravel-support`
- `prepareStoreEncoding()` - Creates encoding with optional model binding
- `encoding()` - Query builder accessor

**ModelHasEncoding Schema:** `src/Schemas/ModelHasEncoding.php`
- `prepareStoreModelHasEncoding()` - Creates/updates encoding association

## Data Transfer Objects (DTOs)

All DTOs use `spatie/laravel-data`:

### EncodingData
```php
[
    'name' => 'Invoice Code',
    'label' => 'invoice_code',
    'flag' => 'Encoding',  // Auto-set
    'model_has_encoding' => [...] // Optional ModelHasEncodingData
]
```

### ModelHasEncodingData
```php
[
    'encoding_id' => 1,
    'reference_id' => '01HXYZ...',
    'reference_type' => 'App\Models\Invoice',
    'value' => 'INV-202601-0001',
    'props' => [...] // ModelHasEncodingPropsData
]
```

### StructureData
Defines individual code segments:
```php
[
    'type' => 'alphanumeric|incrementing|date',
    'value' => 'INV',           // For alphanumeric
    'length' => 3,              // Auto-calculated for alphanumeric
    'format' => 'YYYYMM',       // For date type
    'resetable' => 'year|month|day'  // For date-triggered reset
]
```

### SeparatorData
```php
[
    'distance' => 4,        // Insert separator every N characters
    'separator' => '-'      // The separator character
]
```

## Configuration

**Config file:** `assets/config/config.php`

Published to: `config/module-encoding.php`

```php
return [
    'namespace' => 'Hanafalah\\ModuleEncoding',
    'app' => [
        'contracts' => []
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas',
        'database' => 'Database',
        'data' => 'Data',
        'resource' => 'Resources',
        'migration' => '../assets/database/migrations'
    ],
    'database' => [
        'models' => []
    ],
    'commands' => [
        InstallMakeCommand::class
    ],
    'encodings' => [
        // Define your encoding patterns here
        'invoice_code' => [
            'label' => 'invoice_code',
            // ... encoding structure
        ]
    ]
];
```

## Code Generation Logic

### Structure Types

1. **alphanumeric** - Static text prefix/suffix
   ```php
   ['type' => 'alphanumeric', 'value' => 'INV']
   // Output: "INV"
   ```

2. **incrementing** - Auto-incrementing number with padding
   ```php
   ['type' => 'incrementing', 'length' => 4]
   // Output: "0001", "0002", ... "9999", "10000" (auto-expands)
   ```

3. **date** - Date-based component with format options
   ```php
   ['type' => 'date', 'format' => 'YYYYMM', 'resetable' => 'month']
   // Output: "202601", resets increment on new month
   ```

### Date Format Options

| Format | Example | Length |
|--------|---------|--------|
| YYYY | 2026 | 4 |
| YY | 26 | 2 |
| MM | 01 | 2 |
| DD | 14 | 2 |
| YYYYMM | 202601 | 6 |
| YYYYMMDD | 20260114 | 8 |
| YYMMDD | 260114 | 6 |
| DDMMYYYY | 14012026 | 8 |
| MMYYYY | 012026 | 6 |

### Reset Periods

- `year` - Reset increment on new year
- `month` - Reset increment on new month
- `day` - Reset increment on new day

## Caching

The module uses `SupportCache` for performance:
- `encoding_config` - Maps labels to encoding IDs
- `model_has_encoding_configs` - Stores active encoding structures

**Cache configuration in Schema:**
```php
protected array $__cache = [
    'index' => [
        'name'     => 'encoding',
        'tags'     => ['encoding', 'encoding-index'],
        'duration' => 60 * 24 * 7  // 7 days
    ]
];
```

## Installation

```bash
php artisan module-encoding:install
```

This publishes:
- `config/module-encoding.php` - Configuration file
- Database migrations

Then run:
```bash
php artisan migrate
```

## Dependencies

- `hanafalah/laravel-support` - Base package (REQUIRED)

Inherited from laravel-support:
- `spatie/laravel-data` - Data Transfer Objects
- `hanafalah/laravel-has-props` - JSON props support

## Database Schema

### model_has_encodings Table

```sql
CREATE TABLE model_has_encodings (
    id VARCHAR(26) PRIMARY KEY,       -- ULID
    reference_id VARCHAR(36) NOT NULL,
    reference_type VARCHAR(60) NOT NULL,
    value VARCHAR(255) NULL,
    encoding_id BIGINT UNSIGNED NULL REFERENCES unicodes(id),
    props JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX encoding_ref (reference_id, reference_type),
    INDEX encoding_keys (reference_id, reference_type, encoding_id)
);
```

### unicodes Table (from laravel-support)

The `Encoding` model uses the polymorphic `unicodes` table with `flag = 'Encoding'`.

## Usage Examples

### Define Encoding Pattern

In `config/module-encoding.php`:
```php
'encodings' => [
    'invoice_code' => [
        'label' => 'invoice_code',
        'structure' => [
            ['type' => 'alphanumeric', 'value' => 'INV'],
            ['type' => 'date', 'format' => 'YYYYMM', 'resetable' => 'month'],
            ['type' => 'incrementing', 'length' => 4]
        ],
        'separator' => [
            'distance' => 3,
            'separator' => '-'
        ]
    ]
]
```

### Generate Code in Model

```php
use Hanafalah\ModuleEncoding\Concerns\HasEncoding;

class Invoice extends Model
{
    use HasEncoding;

    public static function generateInvoiceCode(): string
    {
        return static::hasEncoding('invoice_code');
    }
}

// Usage
$code = Invoice::generateInvoiceCode(); // "INV-202-601-0001"
```

### Using Schema Directly

```php
use Hanafalah\ModuleEncoding\Facades\ModuleEncoding;

$encoding = ModuleEncoding::useSchema('encoding')
    ->prepareStoreEncoding(EncodingData::from([
        'name' => 'Invoice Code',
        'label' => 'invoice_code'
    ]));
```

## Octane Considerations

**WARNING:** The `HasEncoding` trait has a static property:
```php
private static bool $__should_reset = false;
```

In Laravel Octane, this persists between requests. The property is only modified within the `formatingDate()` method and `resetIncrementForNewPeriod()`, and resets are based on date comparisons, so it should be safe. However, if you extend this module:

- DO NOT add additional static properties
- DO NOT cache encoding data in class properties
- ALWAYS use request-scoped or cache-based state

## Common Patterns

### Custom Model Override

In `config/database.php` or through model binding:
```php
'models' => [
    'Encoding' => \App\Models\CustomEncoding::class,
    'ModelHasEncoding' => \App\Models\CustomModelHasEncoding::class,
]
```

### Extend Schema

```php
namespace App\Schemas;

use Hanafalah\ModuleEncoding\Schemas\Encoding as BaseEncoding;

class CustomEncoding extends BaseEncoding
{
    public function customMethod()
    {
        // Your custom logic
    }
}
```

## Troubleshooting

### Encoding returns empty string

1. Check encoding label exists in `config('module-encoding.encodings')`
2. Verify cache is populated: `SupportCache::getSavedCache('encoding_config')`
3. Check `model_has_encoding_configs` cache has the encoding

### Increment not resetting

1. Verify `resetable` is set correctly in date structure
2. Check date format matches the resetable period
3. The reset only happens when generating a new code after period change

### Memory issues on boot

If experiencing memory exhaustion:
1. Check if any custom code calls `registers(['Schema'])`
2. Ensure you're not instantiating Schema classes during boot
3. Use lazy loading patterns for Schema access

## Testing Changes

After modifying this module:

```bash
# Clear all caches
docker exec -it wellmed-backbone php artisan cache:clear
docker exec -it wellmed-backbone php artisan config:clear

# Reload Octane
docker exec -it wellmed-backbone php artisan octane:reload

# Test encoding generation
docker exec -it wellmed-backbone php artisan tinker
>>> \App\Models\Invoice::hasEncoding('invoice_code')
```

## Modification Checklist

Before modifying module-encoding:

- [ ] No new static properties added to traits
- [ ] Schema classes not instantiated during boot
- [ ] Config access deferred until after boot
- [ ] Tested code generation with multiple tenants
- [ ] Tested increment reset across date boundaries
- [ ] Cache invalidation handled properly
- [ ] Memory stays stable in Octane environment
