<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb36d7dc2b87dab5f4aee0b459ad63f5f
{
    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
    );

    public static $classMap = array (
        'PiramideUploader' => __DIR__ . '/../..' . '/piramide-uploader/PiramideUploader.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitb36d7dc2b87dab5f4aee0b459ad63f5f::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb36d7dc2b87dab5f4aee0b459ad63f5f::$classMap;

        }, null, ClassLoader::class);
    }
}