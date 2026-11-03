<?php

namespace App\Http\Middleware;

use App\Installer\InstalledState;
use Closure;
use Deep42\Hitchhiker\Contracts\InstallationStateManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class UseFileStoresUntilInstalled
{
    public function __construct(
        private readonly InstallationStateManager $installState,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->installState->isInstalled()) {
            // Migrations create sessions/cache/jobs tables. Keep the wizard off
            // the database even if .env already says SESSION_DRIVER=database.
            InstalledState::useFileStores();
        }

        return $next($request);
    }
}
