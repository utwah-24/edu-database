<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Name shown in footer copyright (defaults to Utafiti elimu)
    |--------------------------------------------------------------------------
    */
    'copyright_label' => env('FOOTER_COPYRIGHT_NAME') ?: 'Utafiti elimu',

    /*
    |--------------------------------------------------------------------------
    | Footer contact
    |--------------------------------------------------------------------------
    */
    'contact_email' => env('FOOTER_CONTACT_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Social profile URLs (omit or leave empty to hide)
    |--------------------------------------------------------------------------
    */
    'social' => [
        'facebook' => env('FOOTER_SOCIAL_FACEBOOK'),
        'x' => env('FOOTER_SOCIAL_X') ?: env('FOOTER_SOCIAL_TWITTER'),
        'linkedin' => env('FOOTER_SOCIAL_LINKEDIN'),
        'instagram' => env('FOOTER_SOCIAL_INSTAGRAM'),
        'youtube' => env('FOOTER_SOCIAL_YOUTUBE'),
    ],
];
