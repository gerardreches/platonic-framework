<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitef637136d5fae12e96d0c7e8c829c969
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Platonic\\Api\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Platonic\\Api\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Platonic\\Api\\Customizer\\Customizer' => __DIR__ . '/../..' . '/includes/Customizer/Customizer.php',
        'Platonic\\Api\\Customizer\\Interface\\CustomizerRules' => __DIR__ . '/../..' . '/includes/Customizer/Interface/CustomizerRules.php',
        'Platonic\\Api\\Settings\\Example\\PluginSettingsPageExample' => __DIR__ . '/../..' . '/includes/Settings/Example/PluginSettingsPageExample.php',
        'Platonic\\Api\\Settings\\Example\\SettingsPageExample' => __DIR__ . '/../..' . '/includes/Settings/Example/SettingsPageExample.php',
        'Platonic\\Api\\Settings\\Example\\ThemeSettingsPage' => __DIR__ . '/../..' . '/includes/Settings/Example/ThemeSettingsPage.php',
        'Platonic\\Api\\Settings\\Interface\\PluginSettingsPageRules' => __DIR__ . '/../..' . '/includes/Settings/Interface/PluginSettingsPageRules.php',
        'Platonic\\Api\\Settings\\Interface\\SettingsPageRules' => __DIR__ . '/../..' . '/includes/Settings/Interface/SettingsPageRules.php',
        'Platonic\\Api\\Settings\\Interface\\SettingsRules' => __DIR__ . '/../..' . '/includes/Settings/Interface/SettingsRules.php',
        'Platonic\\Api\\Settings\\Interface\\ThemeSettingsPageRules' => __DIR__ . '/../..' . '/includes/Settings/Interface/ThemeSettingsPageRules.php',
        'Platonic\\Api\\Settings\\PluginSettings' => __DIR__ . '/../..' . '/includes/Settings/PluginSettings.php',
        'Platonic\\Api\\Settings\\Settings' => __DIR__ . '/../..' . '/includes/Settings/Settings.php',
        'Platonic\\Api\\Settings\\ThemeSettings' => __DIR__ . '/../..' . '/includes/Settings/ThemeSettings.php',
        'Platonic\\Api\\Settings\\Trait\\OptionsPage' => __DIR__ . '/../..' . '/includes/Settings/Trait/OptionsPage.php',
        'Platonic\\Api\\Settings\\Trait\\Sanitization' => __DIR__ . '/../..' . '/includes/Settings/Trait/Sanitization.php',
        'Platonic\\Api\\Settings\\Trait\\SettingsFields' => __DIR__ . '/../..' . '/includes/Settings/Trait/SettingsFields.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitef637136d5fae12e96d0c7e8c829c969::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitef637136d5fae12e96d0c7e8c829c969::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitef637136d5fae12e96d0c7e8c829c969::$classMap;

        }, null, ClassLoader::class);
    }
}