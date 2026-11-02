<?php

namespace App\Support\Storage;

final class EnsureStoragePaths
{
    public static function bootstrap(): void
    {
        require base_path('bootstrap/ensure-storage.php');
    }
}
