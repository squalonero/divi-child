<?php

class skh_DiviChild
{

    static function init()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_styles']);
    }

    static function enqueue_styles()
    {
        $parenthandle = 'divi-style';
        $theme = wp_get_theme();
        wp_enqueue_style(
            $parenthandle,
            get_template_directory_uri() . '/style.css',
            array(), // if the parent theme code has a dependency, copy it to here
            $theme->parent()->get('Version')
        );
        wp_enqueue_style(
            'divi-child-style',
            get_stylesheet_uri(),
            array($parenthandle),
            $theme->get('Version')
        );
    }
}

skh_DiviChild::init();
