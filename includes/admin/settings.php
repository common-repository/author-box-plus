<?php
/**
 * Settings fields in under (User > Authors)
 *
 * @since 1.0
 */

if (!defined("ABSPATH")) exit;
?>
<form method="post" action="options.php">
    <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id="message" class="updated">
            <p><strong><?php _e('Settings saved.', 'author') ?></strong></p>
        </div>
    <?php
    }
    foreach(self::build_setting_fields() as $group_key => $groups) {
        if (!is_array($groups))
            continue;

        // Registering setting fields
        settings_fields($group_key);
        do_settings_sections($group_key);

        foreach($groups as $group) {
            if (!isset($group['fields']) || !is_array($group['fields']))
                continue;

            echo '<h3>' . $group['title'] . '</h3>';

            echo '<table class="form-table">';

            foreach ($group['fields'] as $field_key => $field) {
                $value = isset($field['value']) ? $field['value'] : get_option($field_key);
                $attrs = isset($field['attrs']) ? abp_html_attrs($field['attrs'], array('name', 'id', 'value', 'type')) : '';
                ?>
                <tr>
                    <th><label for="<?php echo $field_key; ?>"><?php echo $field['title']; ?></label></th>
                    <td>
                        <?php
                        switch ($field['type']) {
                            case 'wp_editor':
                                $value = ($value) ? $value : $field['content'];
                                wp_editor($value, $field_key, $field['settings']);
                                break;
                            case 'textarea':
                                ?>
                                <textarea
                                    name="<?php echo $field_key; ?>"
                                    id="<?php echo $field_key; ?>"
                                    <?php echo $attrs; ?>
                                ><?php echo $value; ?></textarea>
                                <?php
                                break;
                            default:
                                ?>
                                <input
                                    type="<?php echo $field['type']; ?>"
                                    name="<?php echo $field_key; ?>"
                                    id="<?php echo $field_key; ?>"
                                    value="<?php echo $value; ?>"
                                    <?php echo $attrs; ?>
                                    <?php echo (isset($field['class'])) ? ('class="' . $field['class'] . '"') : ''; ?>
                                    <?php echo (in_array($field['type'], array('checkbox', 'radio'))) ? checked(get_option($field_key), 1, false) : ''; ?>
                                />
                                <?php
                                break;
                        }
                        ?>
                        <?php if (isset($field['desc'])) { ?>
                            <br>
                            <span class="description"><?php echo $field['desc']; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
            echo '</table>';
        }
    }

    submit_button();
    ?>
</form>
