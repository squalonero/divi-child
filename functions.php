<?php

class skh_DiviChild
{

    private static int $load_priority = 1;

    static function init()
    {
        self::autoload();
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
        add_action('after_setup_theme', [__CLASS__, 'load_textdomain']);
        add_action('after_setup_theme', [skh_DiviOverrides::class, 'init']);
        add_filter( 'rest_authentication_errors', [__CLASS__,'authentication_status']);
        add_filter('pre_get_posts',[__CLASS__,'searchfilter']);
    }

    static function autoload()
    {
        require_once 'constants.php';
        require_once 'autoload.php';
    }

    static function load_textdomain()
    {
        load_child_theme_textdomain("stackhouse", DIVI_CHILD_BP . '/languages');
    }

    static function enqueue_styles()
    {
        $parenthandle = 'divi-style-parent';
        $theme = wp_get_theme();
        $version = defined('WP_DEBUG') && WP_DEBUG ? $theme->parent()->get('Version') . time() : $theme->parent()->get('Version');
        //dequeue gutemberg global styles
        wp_dequeue_style( 'global-styles' );
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        //DO NOT enqueue theme styles as Divi already enqueues them for the child theme
    }
    static function authentication_status($result)
    {
        if ( ! empty( $result ) ) {
            return $result;
          }
          if ( ! is_user_logged_in() ) {
            return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
          }
        //   if ( ! current_user_can( 'administrator' ) ) {
        //     return new WP_Error( 'rest_not_admin', 'You are not an administrator.', array( 'status' => 401 ) );
        //   }
          return $result;
    }

    static function enqueue_scripts()
    {
        $theme = wp_get_theme();
        $version = defined('WP_DEBUG') && WP_DEBUG ? $theme->parent()->get('Version') . time() : $theme->parent()->get('Version');

        wp_enqueue_script('skh-accessibility-js', DIVI_CHILD_ASSETS_URL . "/js/accessibility.js", ['jquery'], $version);
    }

    static function searchfilter($query) {

        if ($query->is_search && !is_admin() ) {
            $query->set('post_type',array('post','page'));
        }

    return $query;
    }

}

skh_DiviChild::init();
