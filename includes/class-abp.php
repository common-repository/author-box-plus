<?php
if (!defined("ABSPATH")) exit;

/**
 * Class Author Main class for plugin initiation
 *
 * @since 1.0
 */
final class ABP
{
    // Main instance
    protected static $_instance = null;

    protected function __construct()
    {
        if (is_admin()) {
            // Backend
            register_activation_hook(__FILE__, array(self::$_instance, 'activation'));
            register_deactivation_hook(__FILE__, array(self::$_instance, 'deactivation'));

            include_once ABP_INCLUDES_DIR . 'class-abp-admin.php';

            // Adding settings tab
            add_filter('plugin_action_links_' . plugin_basename(ABP_DIR_FILE), function($links) {
                return array_merge($links, array(
                    '<a href="' . admin_url('users.php?page=' . ABP_ADMIN_PAGE) . '">Settings</a>',
                ));
            });

        } else {
            // Frontend
            include_once ABP_INCLUDES_DIR . 'class-abp-template-loader.php';
            include_once ABP_INCLUDES_DIR . 'class-abp-author-box.php';
            include_once ABP_INCLUDES_DIR . 'class-abp-author-page.php';

            add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
            add_action('wp_head', array($this, 'custom_css'));
        }
    }

    /**
     * @return $this
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Activation function hook
     * No used in this plugin
     *
     * @return void
     */
    public static function activation()
    {
        if (!current_user_can('activate_plugins'))
            return;
    }

    /**
     * Deactivation function hook
     * No used in this plugin
     *
     * @return void
     */
    public static function deactivation()
    {
    }

    /**
     * Frontend assets css/js
     *
     * @return void
     */
    public function frontend_scripts()
    {
        if (is_author() || is_singular()) {
            wp_enqueue_style('abp', (ABP_ASSETS_URL . 'css/abp.css'));
        }
    }

    /**
     * Output custom css for single/author profile page
     *
     * @return void
     */
    public function custom_css()
    {
        if (!is_author() || !is_singular()) {
            return;
        }

        $custom_css = get_option('abp_settings_fields_custom_css');

        if ($custom_css) {
            echo '<style type="text/css" id="abp_custom_css">'.$custom_css.'</style>';
        }
    }

    /**
     * Detect if the current theme has theme/author-box-plus directory
     * to override templates
     *
     * @return string
     */
    public function template_path()
    {
        $dir = get_stylesheet_directory() . '/author-box-plus';
        return (is_dir($dir)) ? trailingslashit($dir) : ABP_TEMPLATES;
    }

    /**
     * Author fields data structure, can be overridden by using
     * add_filter('abp_author_fields', [..])
     *
     * @return array
     */
    public static function build_author_fields()
    {
        $fields = array(
            'author_management' => array(
                'title' => __('Author Management', 'author'),
                'fields' => array(
                    'abp_author_show_link' => array(
                        'title' => __('Link to authors page', 'author'),
                        'type' => 'checkbox',
                        'value' => 1,
                        'has_cap' => 'edit_users',
                        'desc' => 'Enable link to user profile page (appear on author box).<br>You can trigger 404 if someone tries to visit author profile page by url unless this is checked<br>Change this behaviour, from <a href="'.admin_url('users.php?page=' . ABP_ADMIN_PAGE).'">Settings</a>'
                    ),
                    'abp_author_facebook' => array(
                        'title' => __('Facebook', 'author'),
                        'type' => 'url',
                        'attrs' => array(
                            'class' => 'regular-text'
                        )
                    ),
                    'abp_author_twitter' => array(
                        'title' => __('Twitter', 'author'),
                        'type' => 'url',
                        'attrs' => array(
                            'class' => 'regular-text'
                        )
                    ),
                    'abp_author_googleplus' => array(
                        'title' => __('Google+', 'author'),
                        'type' => 'url',
                        'attrs' => array(
                            'class' => 'regular-text'
                        )
                    ),
                    'abp_author_linkedin' => array(
                        'title' => __('LinkedIn', 'author'),
                        'type' => 'url',
                        'attrs' => array(
                            'class' => 'regular-text'
                        )
                    ),
                    'abp_author_page_content' => array(
                        'title' => __('Your page', 'author'),
                        'type' => 'wp_editor',
                        'desc' => 'Your page content when someone views your profile',
                        'content' => '',
                        'settings' => array(
                            'wpautop' => false
                        )
                    )
                )
            )
        );

        return apply_filters('abp_author_fields', $fields);
    }

    /**
     * Settings fields data structure, can be overridden by using
     * add_filter('setting_fields', [..])
     *
     * @return array
     */
    public static function build_setting_fields()
    {
        $fields = array(
            'author-settings' => array(
                array(
                    'title' => __('Author Box', 'author'),
                    'fields' => array(
                        'abp_sf_hide_author_box' => array(
                            'title' => __('Hide author box', 'author'),
                            'type' => 'checkbox',
                            'value' => 1
                        ),
                        'abp_sf_hide_author_desc' => array(
                            'title' => __('Hide author bio', 'author'),
                            'type' => 'checkbox',
                            'value' => 1
                        ),
                        'abp_sf_hide_social_links' => array(
                            'title' => __('Hide social links', 'author'),
                            'type' => 'checkbox',
                            'value' => 1
                        ),
                        'abp_sf_all_author_link' => array(
                            'title' => __('Link to authors page', 'author'),
                            'type' => 'checkbox',
                            'value' => 1,
                            'desc' => 'This will enable links to all authors name, regardless of the specific selection on user edit screen'
                        )
                    )
                ),
                array(
                    'title' => __('Author profile page', 'author'),
                    'fields' => array(
                        'abp_sf_not_override_author_page' => array(
                            'title' => __('Do not override authors template', 'author'),
                            'type' => 'checkbox',
                            'value' => 1,
                            'desc' => 'Prevents overriding active theme author template (/author/user)<br><strong>Note:</strong> Author profile page won\'t work if this is checked'
                        ),
                        'abp_sf_show_author_404' => array(
                            'title' => __('Display "404 not found"', 'author'),
                            'type' => 'checkbox',
                            'value' => 1,
                            'desc' => 'This will display 404 Not found for the author profile page, unless <strong>Link to authors page</strong> is checked'
                        ),
                        'abp_sf_hide_page_author_box' => array(
                            'title' => __('Hide authors box', 'author'),
                            'type' => 'checkbox',
                            'value' => 1
                        ),
                        'abp_sf_hide_author_recent_post' => array(
                            'title' => __('Hide authors recent posts', 'author'),
                            'type' => 'checkbox',
                            'value' => 1
                        ),
                        'abp_sf_hide_sidebar' => array(
                            'title' => __('Hide sidebar', 'author'),
                            'type' => 'checkbox',
                            'value' => 1
                        ),
                        'abp_sf_author_recent_post_count' => array(
                            'title' => __('Number of recent posts', 'author'),
                            'type' => 'number',
                            'callback' => 'intval',
                            'desc' => 'Defaults to 3'
                        ),
                        'abp_sf_custom_css' => array(
                            'title' => __('Custom CSS', 'author'),
                            'type' => 'textarea',
                            'attrs' => array(
                                'cols' => 50,
                                'rows' => 10,
                                'style' => 'font-family: monospace'
                            ),
                            'desc' => 'Apply custom CSS to post/profile page'
                        )
                    )
                )
            ),
        );

        return apply_filters('abp_setting_fields', $fields);
    }
}

/**
 * Main instance
 *
 * @return ABP
 */
function ABP()
{
    return ABP::instance();
}
