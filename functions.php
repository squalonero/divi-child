<?php

class skh_DiviChild
{

    private static int $load_priority = 1;

    static function init()
    {
        require_once 'constants.php';
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_styles']);
        add_action('init', [__CLASS__, 'autoload'], self::$load_priority);
        add_action('after_setup_theme', [__CLASS__, 'load_textdomain']);
    }

    static function autoload()
    {
        require_once 'autoload.php';
    }

    static function load_textdomain()
    {
        load_child_theme_textdomain("stackhouse", DIVI_CHILD_BP . '/languages');
    }

    static function enqueue_styles()
    {
        $parenthandle = 'divi-style';
        $theme = wp_get_theme();
        $version = defined('WP_DEBUG') && WP_DEBUG ? $theme->parent()->get('Version') . time() : $theme->parent()->get('Version');
        wp_enqueue_style(
            $parenthandle,
            get_template_directory_uri() . '/style.css',
            array(), // if the parent theme code has a dependency, copy it to here
            $version
        );
        wp_enqueue_style(
            'divi-child-style',
            get_stylesheet_uri(),
            array($parenthandle),
            $version
        );
    }
}

skh_DiviChild::init();
