<?php
/**
 * Display authors profile page
 *
 * @template author-page.php
 */

/**
 * Assuming this the correct theme header
 */
get_header();

/**
 * before_profile_page_content hook
 */
do_action('abp_before_profile_page_content');

/**
 * Author box
 */
ABP_Author_Page::author_box();

/**
 * Author profile page content
 */
ABP_Author_Page::page_content();

/**
 * Displays author recent posts
 *
 * @template author-page-recent-posts.php
 */
ABP_Author_Page::recent_posts();

/**
 * after_profile_page_content hook
 */
do_action('abp_after_page_content');

/**
 * Assuming this the correct theme footer
 */
get_footer();
