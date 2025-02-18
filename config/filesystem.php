<?php

declare(strict_types=1);

return [
    'default' => env('FILESYSTEM_DRIVER', 'public'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('public'),
            'url' => env('APP_URL'),
            'visibility' => 'public',
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
