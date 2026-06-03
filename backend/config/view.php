<?php

return [
    'paths' => [
        dirname(__DIR__, 2).'/frontend/resources/views',
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views')) ?: storage_path('framework/views')
    ),
];
