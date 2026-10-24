<?php
    use App\Support\Ui\ThemePalette;

    $savedTheme = ThemePalette::normalize(auth()->user()?->appearance);
?>
<style id="krikkit-theme"><?php echo ThemePalette::runtimeCss($savedTheme['accent'] ?? 'base', $savedTheme['base'] ?? 'neutral'); ?></style>
<?php /**PATH C:\laragon\www\supreme-web-solution\Krikkit\resources\views/components/layouts/partials/themeStyle.blade.php ENDPATH**/ ?>