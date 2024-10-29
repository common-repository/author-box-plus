<?php
if (!defined("ABSPATH")) exit;

/**
 * Class Author_Page handles front end author profile page
 *
 * @since 1.0
 */
class ABP_Author_Page
{
    private static $recent_posts;

    public static function init()
    {
        if (!get_option('abp_sf_not_override_author_page')) {
            add_filter( 'template_include', array(__CLASS__, 'override_author_template'));
        }

        add_filter( 'wp', array(__CLASS__, 'handle_404') );

        add_action('abp_before_profile_page_content', array(__CLASS__, 'before_profile_page_content'));
        add_action('abp_after_page_content', array(__CLASS__, 'after_profile_page_content'));
    }

    /**
     * Override default author archive (author.php/archive.php) page template
     * @param $template string
     * @return string
     */
    public static function override_author_template($template)
    {
        if (is_author()) {
            $template = ABP_Template_Loader::load('author-page.php');
        }

        return $template;
    }

    /**
     * Spit out 404 page
     *
     * @return void
     */
    public static function handle_404()
    {
        if (!is_author())
            return;

        if (abp_link_to_author_name())
            return;

        $_show_404 = get_option('abp_sf_show_author_404');
        $_do_not_override_page = get_option('abp_sf_not_override_author_page');

        if ($_do_not_override_page || !$_show_404)
            return;
        
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
    }

    /**
     * Returns user page content
     *
     * @return void
     */
    public static function page_content()
    {
        echo '<div id="abp-author-page-content" class="groupfix">';
        echo do_shortcode(get_the_author_meta('abp_author_page_content'));
        echo '</div>';
    }

    /**
     * Return author box html content
     *
     * @return void|string
     */
    public static function author_box()
    {
        $_hide_page_author_box = get_option('abp_sf_hide_page_author_box');

        if ($_hide_page_author_box)
            return '';

        include_once ABP_Template_Loader::load('author-page-author-box.php');
    }

    /**
     * Return recent post html content
     *
     * @return void|string
     */
    public static function recent_posts()
    {
        $_show_author_recent_post = get_option('abp_sf_hide_author_recent_post');

        if ($_show_author_recent_post)
            return '';

        global $authordata;

        $count = get_option('abp_sf_author_recent_post_count');

        self::$recent_posts = new WP_Query( array(
            'posts_per_page' => !empty($count) ? $count : 3,
            'author' => $authordata->ID,
            'post_type' => 'post',
            'post_status' => 'publish'
        ));

        if (!self::$recent_posts->have_posts())
            return '';

        include_once ABP_Template_Loader::load('author-page-recent-posts.php');
    }

    /**
     * Applied at hook before_profile_page_content
     *
     * @return void
     */
    public static function before_profile_page_content()
    {
        $class = '';
        $hide_sidebar = get_option('abp_sf_hide_sidebar');

        if (!$hide_sidebar)
            $class = 'class="abp-author-left-content-box"';

        echo '<div id="abp-author-page" ' . $class . '>';
    }

    /**
     * Applied at hook after_profile_page_content
     *
     * @return void
     */
    public static function after_profile_page_content()
    {
        echo '</div>';
        $hide_sidebar = get_option('abp_sf_hide_sidebar');

        if (!$hide_sidebar)
            get_sidebar();
    }
}

ABP_Author_Page::init();
