<?php

class skh_DiviOverrides
{

    static function init()
    {
        remove_action('wp_head', 'et_add_viewport_meta');
        add_action('wp_head', [__CLASS__, 'et_add_viewport_meta']);
        add_filter('et_core_esc_attr', [__CLASS__, 'et_core_esc_attr']);
    }

    static function et_add_viewport_meta()
    {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0" />';
    }

    static function et_core_esc_attr($default){
        $default['role'] = 'esc_attr';
        return $default;
    }
}
