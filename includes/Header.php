<?php


class skh_Header
{
    static function init()
    {
        add_action('wp_body_open', [__CLASS__, 'skip_to_content']);
    }

    static function skip_to_content()
    {
        $classes = is_admin_bar_showing() ? 'skip-admin-bar': '';
        ob_start();
        include DIVI_CHILD_TEMPLATES_PATH . '/skip_to_content.php';
        echo ob_get_clean();
    }
}

skh_Header::init();
