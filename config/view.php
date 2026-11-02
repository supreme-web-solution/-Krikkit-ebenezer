<?php

return [

    'paths' => [
        resource_path('views'),
    ],

    /*
    | Do not use realpath() here — on fresh Forge/deploy clones the views
    | directory may not exist yet and realpath() returns false, which breaks
    | Blade with "Please provide a valid cache path."
    */
    'compiled' => env('VIEW_COMPILED_PATH', storage_path('framework/views')),

];
