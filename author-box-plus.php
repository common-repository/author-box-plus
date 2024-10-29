<?php
/**
 * Plugin Name: Author Box Plus
 * Plugin URI: https://wooninjas.com
 * Description: Author Box Plus plugin allows authors to easily create and manage their profiles.
 * Version: 1.0.3
 * Author: WooNinjas
 * Author URI: https://wooninjas.com
 * Text Domain: author
 * License: GPLv2 or later
 */

if (!defined("ABSPATH")) exit;

// Directory
define('ABP_DIR', plugin_dir_path(__FILE__));
define('ABP_DIR_FILE', ABP_DIR . basename(__FILE__));
define('ABP_INCLUDES_DIR', trailingslashit(ABP_DIR . 'includes'));
define('ABP_INCLUDES_LIB_DIR', trailingslashit(ABP_INCLUDES_DIR . 'libraries'));
define('ABP_ADMIN_TEMPLATES', trailingslashit(ABP_DIR . 'includes/admin'));
define('ABP_TEMPLATES', trailingslashit(ABP_DIR . 'templates'));

// URLS
define('ABP_URL', trailingslashit(plugins_url('', __FILE__)));
define('ABP_ASSETS_URL', trailingslashit(ABP_URL . 'assets'));

define('ABP_ADMIN_PAGE', 'abp');

//Loading files
require_once ABP_INCLUDES_DIR . 'helpers.php';
require_once ABP_INCLUDES_DIR . 'libraries/class-abp-author-list-table.php';
require_once ABP_INCLUDES_DIR . 'class-abp.php';

// Bootstrap
ABP();
