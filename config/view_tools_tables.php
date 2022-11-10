<?php

return [
    /*
    |--------------------------------------------------------------------------
    | nullValue
    |--------------------------------------------------------------------------
    |
    | Value to display for null values
    |
    */
    'nullValue' => '-',
    /*
    |--------------------------------------------------------------------------
    | noResultsText
    |--------------------------------------------------------------------------
    |
    | Value to display empty data sets
    |
    */
    'noResultsText' => 'No results...',
    /*
    |--------------------------------------------------------------------------
    | attributes
    |--------------------------------------------------------------------------
    |
    | HTML attributes
    |
    */
    'attributes' => [
        'table' => ['class' => 'table'],
        'headers' => ['class' => 'headers'],
        'header' => ['class' => 'header'],
        'body' => ['class' => 'body'],
        'row' => ['class' => 'row'],
        'cell' => ['class' => 'cell']
    ],
    /*
    |--------------------------------------------------------------------------
    | views
    |--------------------------------------------------------------------------
    |
    | Views used for rendering table parts.
    |
    */
    'views' => [
        'table' => 'view_tools::table.table',
        'headers' => 'view_tools::table.headers',
        'header' => 'view_tools::table.header',
        'body' => 'view_tools::table.tbody',
        'row' => 'view_tools::table.row',
        'cell' => 'view_tools::table.cell',
        'before' => 'view_tools::table.before',
        'after' => 'view_tools::table.after',
        'action' => 'view_tools::table.action',
        'footer' => 'view_tools::table.tfoot',
        'empty' => 'view_tools::table.empty'
    ]
];
