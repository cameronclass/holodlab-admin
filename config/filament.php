<?php

return [
    'cache_ttl' => 3600,
    'layout' => [
        'max_content_width' => 'full',
        'tables' => [
            'default_record_count_per_page' => 25,
        ],
    ],
    'default_filesystem_disk' => 'public',
    'resources' => [
        'should_show_navigation_badge' => true,
    ],
    'broadcasting' => [
        'echo_config' => [
            'broadcaster' => 'pusher',
            'key' => env('VITE_PUSHER_APP_KEY'),
            'cluster' => env('VITE_PUSHER_APP_CLUSTER'),
            'forceTLS' => true,
        ],
    ],
];
