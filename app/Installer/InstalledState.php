<?php

namespace App\Installer;

use Deep42\Hitchhiker\Contracts\InstallationStateManager;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * A copied storage/installed file is not enough. cPanel uploads often include
 * that lock while the new database is still empty, and the app then crashes
 * on the sessions table before /install can run migrations.
 */
final class InstalledState implements InstallationStateManager
{
    private ?bool $memo = null;

    private bool $detecting = false;

    public function isInstalled(): bool
    {
        if ($this->memo !== null) {
            return $this->memo;
        }

        if ($this->detecting) {
            return false;
        }

        $this->detecting = true;

        try {
            return $this->memo = $this->detect();
        } finally {
            $this->detecting = false;
        }
    }

    public function markInstalled(): void
    {
        $this->write($this->lockPath(), now()->toIso8601String());
        $this->write($this->stampPath(), now()->toIso8601String());
        $this->memo = true;
    }

    public function forgetReadyStamp(): void
    {
        $this->memo = null;
        if (is_file($this->stampPath())) {
            @unlink($this->stampPath());
        }
    }

    public static function isMissingInstallTable(QueryException $e): bool
    {
        $state = (string) ($e->errorInfo[0] ?? '');
        if ($state !== '42S02' && ! str_contains($e->getMessage(), 'Base table or view not found') && ! str_contains($e->getMessage(), 'no such table')) {
            return false;
        }

        $message = strtolower($e->getMessage());

        foreach (['sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'users'] as $table) {
            if (str_contains($message, '.'.$table.'\'')
                || str_contains($message, '.'.$table.'`')
                || str_contains($message, 'table: '.$table)
                || str_contains($message, '`'.$table.'`')
                || str_contains($message, "'".$table."'")) {
                return true;
            }
        }

        return false;
    }

    public static function useFileStores(): void
    {
        config([
            'session.driver' => 'file',
            'cache.default' => 'file',
            'queue.default' => 'sync',
        ]);
    }

    private function detect(): bool
    {
        if (! is_file($this->lockPath())) {
            $this->forgetReadyStamp();

            return false;
        }

        if (is_file($this->stampPath())) {
            return true;
        }

        if (! $this->schemaReady()) {
            return false;
        }

        $this->write($this->stampPath(), now()->toIso8601String());

        return true;
    }

    private function schemaReady(): bool
    {
        try {
            return Schema::hasTable('users') && Schema::hasTable('sessions');
        } catch (Throwable) {
            return false;
        }
    }

    private function lockPath(): string
    {
        $configured = config('installer.installed_file');

        return is_string($configured) && $configured !== ''
            ? $configured
            : storage_path('installed');
    }

    private function stampPath(): string
    {
        return storage_path('framework/installer-ready');
    }

    private function write(string $path, string $contents): void
    {
        $directory = dirname($path);
        if (! is_dir($directory)) {
            @mkdir($directory, 0755, true);
        }

        @file_put_contents($path, $contents);
    }
}
