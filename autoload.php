<?php

class skh_DiviChild_Autoload
{
    private static ?skh_DiviChild_Autoload $instance = null;
    private static RecursiveIteratorIterator $iterator;
    private static array $classlist = [];
    private const ASSETS_URL = DIVI_CHILD_MODULES_URL . "/assets";
    private static array $divi_child_extra_modules_paths;

    private function __construct()
    {
        $dir = new RecursiveDirectoryIterator(DIVI_CHILD_MODULES_PATH);
        self::$iterator = new RecursiveIteratorIterator($dir);
        self::$divi_child_extra_modules_paths = [
            [
                "iterator" => self::createIterator(DIVI_CHILD_BP . "/includes/supreme-modules-pro-for-divi"),
                "classNameParser" => function ($fname)
                {
                    if (strpos($fname, '.php') !== false)
                        $fname = 'skh_DSM_' . implode('_', preg_split('/(?=[A-Z])/', basename($fname, '.php'), -1, PREG_SPLIT_NO_EMPTY));
                    return $fname;
                }
            ]
        ];
        add_action('et_builder_ready', [__CLASS__, 'autoload_divi_child_modules']);
        add_filter('et_module_classes', [__CLASS__, 'divi_custom_module_class']);
        add_action('et_builder_ready', [__CLASS__, 'autoload_divi_child_extra_modules']);
        add_filter('et_module_classes', [__CLASS__, 'divi_extra_module_class']);
        self::register_divi_child_modules_js();
    }

    private static function createIterator($path)
    {
        if(is_dir($path))
            return new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        return null;
    }

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            $selfClass = __CLASS__;
            self::$instance = new $selfClass;
        }

        return self::$instance;
    }

    private static function process_divi_child_modules(RecursiveIteratorIterator $iterator, object $cb, object $classNameParser)
    {
        foreach ($iterator as $file)
        {
            $fname = $file->getFilename();
            $fpath = $file->getPath();
            $fileFolders = explode(DIRECTORY_SEPARATOR, $fpath);
            $className = $classNameParser($fname);
            if (
                preg_match('%\.php$%', $fname)
                && !in_array('templates', $fileFolders)
                && !in_array('assets', $fileFolders)
                && !in_array('helpers', $fileFolders)
            )
            {
                $cb($file, $fname, $fpath, $fileFolders, $className);
            }
        }
    }

    static function autoload_divi_child_modules()
    {
        self::process_divi_child_modules(
            self::$iterator,
            function ($file, $fname, $fpath, $fileFolders, $className)
            {
                $instance = new $className;
                add_shortcode($instance::$shortcode, array($instance, '_shortcode_callback'));
            },
            function ($fname)
            {
                return "skh_ET_Builder_Module_" . str_replace('.php', '', $fname);
            }
        );
    }

    static function divi_custom_module_class($classlist)
    {
        self::process_divi_child_modules(
            self::$iterator,
            function ($file, $fname, $fpath, $fileFolders, $className) use ($classlist)
            {
                include_once($file->getPathname());
                self::$classlist[$className::$shortcode] = array('classname' => $className);
            },
            function ($fname)
            {
                return "skh_ET_Builder_Module_" . str_replace('.php', '', $fname);
            }
        );
        return  array_merge($classlist, self::$classlist);
    }

    static function divi_extra_module_class($classlist)
    {
        foreach (self::$divi_child_extra_modules_paths as $extraModuleInfo)
        {
            if(!$extraModuleInfo['iterator']) continue;
            self::process_divi_child_modules($extraModuleInfo['iterator'], function ($file, $fname, $fpath, $fileFolders, $className)
            {
                include_once($file->getPathname());
                self::$classlist[$className::$shortcode] = array('classname' => $className);
            }, $extraModuleInfo['classNameParser']);
        }
        return $classlist;
    }

    private static function register_divi_child_modules_js()
    {
        foreach (self::$iterator as $file)
        {
            $fname = $file->getFilename();
            $fpath = $file->getPath();
            $fileFolders = explode(DIRECTORY_SEPARATOR, $fpath);
            if (
                preg_match('%\.js$%', $fname)
                && in_array('assets', $fileFolders)
            )
            {
                $fpath = $file->getPath();
                $handle = str_replace('.js', '-js', $fname);
                $ver = defined('WP_DEBUG') && WP_DEBUG ? time() : DIVI_CHILD_VERSION;
                wp_register_script("skh-divi-child-$handle", self::ASSETS_URL . "/js/$fname", ['jquery'], $ver);
            }
        }
    }

    static function autoload_divi_child_extra_modules()
    {
        foreach (self::$divi_child_extra_modules_paths as $extraModuleInfo)
        {
            if(!$extraModuleInfo['iterator']) continue;
            self::process_divi_child_modules($extraModuleInfo['iterator'], function ($file, $fname, $fpath, $fileFolders, $className)
            {
                $instance = new $className;
                add_shortcode($instance::$shortcode, array($instance, '_shortcode_callback'));
            }, $extraModuleInfo['classNameParser']);
        }
    }

}




skh_DiviChild_Autoload::getInstance();
