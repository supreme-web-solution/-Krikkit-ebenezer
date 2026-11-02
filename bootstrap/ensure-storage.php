<?php

/**
 * Create storage dirs before Laravel boots (Forge zero deploy, composer post-autoload-dump).
 */
$base = dirname(__DIR__);

/** @var list<string> */
$relativeDirs = [
    'framework/views',
    'framework/cache/data',
    'framework/sessions',
    'logs',
    'app/public',
];

foreach ($relativeDirs as $relative) {
    $path = $base.'/storage/'.$relative;

    if (is_dir($path)) {
        continue;
    }

    @mkdir($path, 0755, true);
}
