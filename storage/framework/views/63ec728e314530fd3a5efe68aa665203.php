<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (isset($component)) { $__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.partials.brand','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.partials.brand'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064)): ?>
<?php $attributes = $__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064; ?>
<?php unset($__attributesOriginalb4310b2bf4c6b97ee5cbfb4533e94064); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064)): ?>
<?php $component = $__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064; ?>
<?php unset($__componentOriginalb4310b2bf4c6b97ee5cbfb4533e94064); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal2d87f67cf40ec64138dcedbd77d428d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.partials.seo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.partials.seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4)): ?>
<?php $attributes = $__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4; ?>
<?php unset($__attributesOriginal2d87f67cf40ec64138dcedbd77d428d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d87f67cf40ec64138dcedbd77d428d4)): ?>
<?php $component = $__componentOriginal2d87f67cf40ec64138dcedbd77d428d4; ?>
<?php unset($__componentOriginal2d87f67cf40ec64138dcedbd77d428d4); ?>
<?php endif; ?>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(route('installer.assets.css')); ?>">
    <?php
        $accent = config('installer.theme.accent', config('installer.theme.primary', '#262626'));
        $accentForeground = config('installer.theme.accent_foreground', '#ffffff');
        $accentDark = config('installer.theme.accent_dark', '#ffffff');
        $accentDarkForeground = config('installer.theme.accent_dark_foreground', '#1a1a1a');
        $themeMode = config('installer.theme.mode', 'system');
    ?>
    <style>
        :root {
            --theme-accent: <?php echo e($accent); ?>;
            --theme-accent-foreground: <?php echo e($accentForeground); ?>;
            --theme-accent-dark: <?php echo e($accentDark); ?>;
            --theme-accent-dark-foreground: <?php echo e($accentDarkForeground); ?>;
        }
        html { background-color: var(--color-krikkit-canvas, #fff); color-scheme: light; }
        html.dark { background-color: var(--color-krikkit-canvas, #0a0a0a); color-scheme: dark; }
        [x-cloak] { display: none !important; }
        .installer-sidebar {
            justify-content: flex-start;
        }
        .installer-brand-logo {
            display: block;
            height: 2rem;
            width: auto;
            max-width: 10rem;
            object-fit: contain;
            object-position: left center;
        }
        .installer-actions-end {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-left: auto;
        }
        .btn.btn--continue,
        .btn.btn--back {
            display: inline-flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
        .btn .btn-text-icon {
            display: inline-flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            line-height: 1;
            white-space: nowrap;
        }
        .btn .btn-text-icon--busy {
            display: none;
        }
        .btn [wire\:loading\.flex],
        .btn [wire\:loading] {
            align-items: center;
            justify-content: center;
            flex-direction: row;
            gap: 0.5rem;
        }
        .btn.is-finishing .btn-text-icon--idle,
        .btn.is-finishing [wire\:loading\.flex],
        .btn.is-finishing [wire\:loading] {
            display: none !important;
        }
        .btn.is-finishing .btn-text-icon--busy[x-cloak],
        .btn.is-finishing .btn-text-icon--busy {
            display: inline-flex !important;
        }
        .btn .spinner {
            display: inline-block;
            flex: 0 0 1rem;
            box-sizing: border-box;
            margin: 0;
            width: 1rem;
            height: 1rem;
            vertical-align: middle;
        }
        .btn svg {
            display: block;
            flex: 0 0 1rem;
            width: 1rem;
            height: 1rem;
        }
    </style>
    <script>
        (() => {
            const configured = <?php echo \Illuminate\Support\Js::from($themeMode)->toHtml() ?>;
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const dark = configured === 'dark' || (configured === 'system' && prefersDark);
            document.documentElement.classList.toggle('dark', dark);
            document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        })();
    </script>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>tailwind.config = { darkMode: 'class' }</script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal7535147e3c68f35abc03824f14d5620f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7535147e3c68f35abc03824f14d5620f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.partials.themeStyle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.partials.themeStyle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7535147e3c68f35abc03824f14d5620f)): ?>
<?php $attributes = $__attributesOriginal7535147e3c68f35abc03824f14d5620f; ?>
<?php unset($__attributesOriginal7535147e3c68f35abc03824f14d5620f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7535147e3c68f35abc03824f14d5620f)): ?>
<?php $component = $__componentOriginal7535147e3c68f35abc03824f14d5620f; ?>
<?php unset($__componentOriginal7535147e3c68f35abc03824f14d5620f); ?>
<?php endif; ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="font-sans antialiased">
    <?php echo e($slot); ?>

    <?php if (isset($component)) { $__componentOriginal91104845e677e54551961253c3a80058 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91104845e677e54551961253c3a80058 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => '844b6b6837275c86b96b7c1e64e409ab::toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('krikkit::toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91104845e677e54551961253c3a80058)): ?>
<?php $attributes = $__attributesOriginal91104845e677e54551961253c3a80058; ?>
<?php unset($__attributesOriginal91104845e677e54551961253c3a80058); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91104845e677e54551961253c3a80058)): ?>
<?php $component = $__componentOriginal91104845e677e54551961253c3a80058; ?>
<?php unset($__componentOriginal91104845e677e54551961253c3a80058); ?>
<?php endif; ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH C:\laragon\www\supreme-web-solution\Krikkit\resources\views/vendor/installer/layouts/installer.blade.php ENDPATH**/ ?>