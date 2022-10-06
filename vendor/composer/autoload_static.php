<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit267acc97081986d1e0b1569321863cc0
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'Noobsplugin\\Noobsquiz\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Noobsplugin\\Noobsquiz\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit267acc97081986d1e0b1569321863cc0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit267acc97081986d1e0b1569321863cc0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit267acc97081986d1e0b1569321863cc0::$classMap;

        }, null, ClassLoader::class);
    }
}
