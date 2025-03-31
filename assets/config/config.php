<?php

use Hanafalah\ModuleEncoding\{
    Models as ModuleEncodingModels,
    Commands as ModuleEncodingCommands,
    Contracts
};

return [
    'app' => [
        'contracts' => [
            // ADD YOUR CONTRACTS HERE
        ],
    ],
    'commands' => [
        ModuleEncodingCommands\InstallMakeCommand::class
    ],
    'libs' => [
        'model' => 'Models',
        'contract' => 'Contracts',
        'schema' => 'Schemas'
    ],
    'database' => [
        'models' => [
            // ADD YOUR MODELS HERE
        ]
    ]
];
