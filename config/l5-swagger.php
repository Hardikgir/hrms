<?php

return [
    'defaults' => [
        'routes' => [
            'api' => 'api/documentation',
        ],
        'paths' => [
            'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', false),
            'docs_json' => 'api-docs.json',
            'docs_yaml' => 'api-docs.yaml',
            'format_to_use_docs' => env('L5_FORMAT_TO_USE_DOCS', 'json'),
            'annotations' => [
                base_path('app'),
            ],
        ],
    ],
    'defaults' => [
        'api' => [
            'title' => 'HRMS API Documentation',
        ],
    ],
    'routes' => [
        'api' => 'api/documentation',
    ],
    'paths' => [
        'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', false),
        'annotations' => [
            base_path('app'),
        ],
    ],
];

