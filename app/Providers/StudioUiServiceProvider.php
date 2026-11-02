<?php

namespace App\Providers;

use App\View\Compilers\StudioTagCompiler;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class StudioUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('views/studio'), 'studio');

        $compiler = new StudioTagCompiler(
            app('blade.compiler')->getClassComponentAliases(),
            app('blade.compiler')->getClassComponentNamespaces(),
            app('blade.compiler')
        );

        app('blade.compiler')->precompiler(fn (string $value) => $compiler->compile($value));
    }
}
