<?php

class skh_DiviChild_Autoload
{
    private static ?skh_DiviChild_Autoload $instance = null;
    private static RecursiveIteratorIterator $iterator;
    private static array $classlist = [];

    private function __construct()
    {
        $dir = new RecursiveDirectoryIterator(DIVI_CHILD_MODULES_PATH);
        self::$iterator = new RecursiveIteratorIterator($dir);
        add_action('et_builder_ready', [__CLASS__, 'autoload_divi_child_modules']);
        add_filter('et_module_classes', [__CLASS__, 'divi_custom_module_class']);
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

    private static function process_divi_child_modules($cb)
    {
        foreach (self::$iterator as $file)
        {
            $fname = $file->getFilename();
            $fpath = $file->getPath();
            $fileFolders = explode(DIRECTORY_SEPARATOR, $fpath);
            $className = "skh_ET_Builder_Module_" . str_replace('.php', '', $fname);
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
        self::process_divi_child_modules(function ($file, $fname, $fpath, $fileFolders, $className)
        {
            $instance = new $className;
            // remove_shortcode($instance::$shortcode);
            add_shortcode($instance::$shortcode, array($instance, '_shortcode_callback'));
        });
    }

    static function divi_custom_module_class($classlist)
    {
        self::process_divi_child_modules(
            function ($file, $fname, $fpath, $fileFolders, $className) use ($classlist)
            {
                include_once($file->getPathname());
                self::$classlist[$className::$shortcode] = array('classname' => $className);
            }
        );
        return  array_merge($classlist, self::$classlist);
    }
}




skh_DiviChild_Autoload::getInstance();
