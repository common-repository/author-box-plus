<?php
if (!defined("ABSPATH")) exit;

/**
 * Class Admin for admin dashboard settings
 *
 * @since 1.0
 */
class ABP_Admin
{
    /**
     * @var ABP_Author_List_Table
     */
    public $abp_author_list_table;

    /**
     * Tabs displayed on Authors Menu
     * @var array
     */
    private $tabs = array(
        'author-settings' => 'Settings',
        'author-list' => 'Authors List'
    );

    public function __construct()
    {
        add_action('admin_menu', array($this, 'dashboard_menus'));
        add_action('admin_init', array($this, 'settings_fields'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

        // Add author fields
        add_action('show_user_profile', array($this, 'add_author_fields'));
        add_action('edit_user_profile', array($this, 'add_author_fields'));

        // Save author fields
        add_action('edit_user_profile_update', array($this, 'update_author_fields'));
        add_action('personal_options_update', array($this, 'update_author_fields'));
    }

    /**
     * Dashboard menu under (User > Authors)
     *
     * @return void
     */
    public function dashboard_menus()
    {
        add_users_page('Authors', 'Authors', 'manage_options', ABP_ADMIN_PAGE, array($this, 'author_menu'));
    }

    /**
     * Author menu template
     *
     * @return void
     */
    public function author_menu()
    {
        include_once ABP_ADMIN_TEMPLATES . 'main_menu.php';
    }

    /**
     * Template for author fields to user edit screen
     *
     * @param $user WP_User
     * @return void
     */
    public function add_author_fields($user)
    {
        if (!abp_is_user_author($user->roles))
            return;

        $cu = wp_get_current_user();
        if (!$cu->has_cap('edit_users') && !current_user_can('edit_user', $user->ID))
            return;

        include_once ABP_ADMIN_TEMPLATES . 'author_user_fields.php';
    }

    /**
     * Update author fields
     *
     * @param $user_id int
     * @return void
     */
    public function update_author_fields($user_id)
    {
        $cu = wp_get_current_user();
        $cap_to_edit_users = $cu->has_cap('edit_users');
        $current_user_can_do = current_user_can('edit_user', $user_id);

        if (!$cap_to_edit_users && !$current_user_can_do)
            return;

        foreach(self::build_author_fields() as $fields) {
            if (!isset($fields['fields']))
                continue;

            foreach($fields['fields'] as $field_key => $field) {
                if (isset($field['has_cap'])) {
                    if (!$cu->has_cap($field['has_cap']))
                        continue;
                }

                if ($current_user_can_do) {
                    $post_field = isset($_POST[$field_key]) ? $_POST[$field_key] : '';
                    update_user_meta($user_id, $field_key, $post_field);
                }
            }
        }
    }

    /**
     * Update settings fields
     *
     * @return void
     */
    public function settings_fields()
    {
        foreach(self::build_setting_fields() as $group_key => $groups) {
            foreach($groups as $group) {
                if (!isset($group['fields']))
                    continue;

                foreach ($group['fields'] as $field_key => $field) {
                    $callback = (isset($field['callback'])) ? $field['callback'] : '';
                    register_setting($group_key, $field_key, $callback);
                }
            }
        }
    }

    /**
     * Admin assets css/js
     */
    public function admin_scripts() {}

    /**
     * Alias of Author::build_author_fields()
     *
     * @return array
     */
    public static function build_author_fields()
    {
        return ABP::build_author_fields();
    }

    /**
     * Alias of Author::build_setting_fields()
     *
     * @return array
     */
    public static function build_setting_fields()
    {
        return ABP::build_setting_fields();
    }
}

// Let it be alone
new ABP_Admin();
