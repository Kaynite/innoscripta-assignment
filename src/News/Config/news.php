<?php

return [
    'default' => env('NEWS_DRIVER', 'newsapi'),

    'drivers' => [
        'newsapi' => [
            'key' => env('NEWS_API_KEY'),
        ],

        'new_york_times' => [
            'api_key' => env('NEW_YORK_TIMES_API_KEY'),
        ],

        'the_guardian' => [
            'api_key' => env('THE_GUARDIAN_API_KEY'),
        ],
    ],
];
