<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Types
    |--------------------------------------------------------------------------
    |
    | Here you may specify description type format value in activity logs
    | for specific users accessing your application.
    |
    */

    'user_types' => [
        'guest' => 'Guest',
        'registered' => 'Registered',
        'crawler' => 'Crawler',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Methods
    |--------------------------------------------------------------------------
    |
    | Here are each of the most commonly used HTTP verbs for your application that
    | you wish to have a description format value in storing activity logs.
    |
    */

    'method' => [
        'get' => 'Viewed',
        'post' => 'Created',
        'patch' => 'Edited',
        'put' => 'Edited',
        'delete' => 'Deleted',
        'crawled' => 'crawled'
    ],

    /*
    |--------------------------------------------------------------------------
    | User ID Field
    |--------------------------------------------------------------------------
    |
    | Here you may specify field name of primary key id used in your
    | users table.
    |
    */

    'user_id' => 'id'
];
