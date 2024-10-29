<?php
if (!defined("ABSPATH")) exit;

/**
 * Class Author_Box handles frontend author box on single post
 *
 * @since 1.0
 */
class ABP_Author_Box
{
    public static function init()
    {
        add_filter( 'the_content', array(__CLASS__, 'add_author_box') );
    }

    /**
     * Append author box to the post content
     * @param $content string
     * @return string
     */
    public static function add_author_box($content)
    {
        if (!is_singular())
            return $content;

        if (!is_main_query())
            return $content;

        if (get_option('abp_sf_hide_author_box'))
            return $content;

        $content .= ABP_Template_Loader::_include('author-box.php');

        return $content;
    }
}

ABP_Author_Box::init();
