<?php
if (!defined("ABSPATH")) exit;

/**
 * Dirty way of returning include contents
 *
 * @param string $template
 * @return string
 */
function return_include_once($template)
{
    ob_start();
    include_once $template;
    return ob_get_clean();
}

/**
 * Return all user(author) post comments regardless of post types
 *
 * @param int $user_id
 * @return int
 */
function abp_count_user_all_post_comments($user_id)
{
    global $wpdb;
    $row = $wpdb->get_row("SELECT COUNT(*) AS comment_count FROM $wpdb->comments c INNER JOIN $wpdb->posts p ON p.id = c.comment_post_id WHERE  p.post_author = $user_id");
    return (isset($row->comment_count) && $row->comment_count > -1) ? $row->comment_count : 0;
}

/**
 * Check to see if the author exist in current user roles
 *
 * @param $roles array
 * @return bool
 */
function abp_is_user_author($roles)
{
    return in_array('author', $roles);
}

/**
 * Get most recent post of user
 *
 * @param int $user_id
 * @param string|array $post_types
 * @return array
 */
function abp_get_most_recent_post_of_user($user_id, $post_types = '' ) {
    global $wpdb;

    if (is_array($post_types) && count($post_types))
        $post_types = implode('","', $post_types);

    if (!is_string($post_types) || '' === $post_types)
        $post_types = 'post';

    $post_type_in = ' AND post_type IN ("'.$post_types.'")';

    $most_recent_post = array();
    $recent_post = $wpdb->get_row( $wpdb->prepare("SELECT ID, post_date_gmt FROM {$wpdb->prefix}posts WHERE post_author = %d $post_type_in AND post_status = 'publish' ORDER BY post_date_gmt DESC LIMIT 1", $user_id ), ARRAY_A);

    // Make sure we found a post
    if ( isset($recent_post['ID']) ) {
        $post_gmt_ts = strtotime($recent_post['post_date_gmt']);

        $most_recent_post = array(
            'post_id'		=> $recent_post['ID'],
            'post_date_gmt'	=> $recent_post['post_date_gmt'],
            'post_gmt_ts'	=> $post_gmt_ts
        );
    }

    return $most_recent_post;
}

/**
 * Convert array to space separated html like attributes
 *
 * @param array $attrs
 * @param array $block
 * @return string
 */
function abp_html_attrs($attrs, $block = array())
{
    if (!is_array($attrs))
        return '';

    $keys = array_keys($attrs);

    return implode(' ', array_map(function($k, $v) use ($block) {
        return in_array($k, $block) ? '' : ($k . '="' . $v . '"');
    }, $keys, $attrs));
}

/**
 * Retrieve the avatar `<img>` tag for a user, email address, MD5 hash, comment, or post.
 *
 * @param int $size
 * @return false|string
 */
function abp_get_avatar($size = 96)
{
    global $authordata;
    return get_avatar($authordata->ID, $size);
}

/**
 * Check to see if the link to author name is checked
 *
 * @return bool
 */
function abp_link_to_author_name()
{
    $_link_to_author_name = get_the_author_meta('abp_author_show_link');
    $_global_link_to_author_name = get_option('abp_sf_all_author_link');

    return $_link_to_author_name || $_global_link_to_author_name;
}

/**
 * Retrieve author name
 *
 * @param bool $no_anchor
 * @return string
 */
function abp_get_author_name($is_anchor = true)
{
    global $authordata;

    return abp_link_to_author_name() && $is_anchor
        ? '<a href="' . site_url("/author/{$authordata->user_nicename}") . '">' . $authordata->display_name . '</a>'
        : $authordata->display_name;
}

/**
 * Retrieve list of all social links
 *
 * @return array|bool
 */
function abp_get_social_links()
{
    global $authordata;

    if (get_option('abp_sf_hide_social_links') || !$authordata) {
        return false;
    }

    $links = array();

    if ($authordata->user_url) {
        $links[] = array(
            'url' => $authordata->user_url,
            'title' => 'Website',
            'class' => 'website'
        );
    }

    foreach(ABP::build_author_fields() as $fields) {
        if (!isset($fields['fields']))
            continue;

        foreach($fields['fields'] as $field_key => $field) {
            if ($field['type'] !== 'url')
                continue;

            $url = get_the_author_meta($field_key);

            if (!$url)
                continue;

            $links[] = array(
                'url' => $url,
                'title' => $field['title'],
                'class' => str_replace('_', '-', $field_key)
            );
        }
    }

    return $links;
}

/**
 * Retrieve authors bio description
 *
 * @return string
 */
function abp_get_author_bio()
{
    $_desc = get_the_author_meta('description');

    if (!get_option('abp_sf_hide_author_desc')) {
        return $_desc ? $_desc : '';
    }

    return '';
}
