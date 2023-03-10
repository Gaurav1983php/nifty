<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8144023ac926e4b8555b4bd52e1dcef0
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPFormsGeolocation\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPFormsGeolocation\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8144023ac926e4b8555b4bd52e1dcef0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8144023ac926e4b8555b4bd52e1dcef0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8144023ac926e4b8555b4bd52e1dcef0::$classMap;

        }, null, ClassLoader::class);
    }
}
