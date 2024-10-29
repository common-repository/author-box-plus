<?php
/**
 * Author fields under (User > Authors > Authors list > Edit)
 *
 * @since 1.0
 */

if (!defined("ABSPATH")) exit;

foreach(self::build_author_fields() as $fields) {
    if (!isset($fields['fields']) || !is_array($fields['fields']))
        continue;

    echo '<h3>' . $fields['title'] . '</h3>';

    echo '<table class="form-table">';

    foreach($fields['fields'] as $field_key => $field) {
        if (isset($field['has_cap'])) {
            if (!$cu->has_cap($field['has_cap']))
                continue;
        }

        $attrs = isset($field['attrs']) ? abp_html_attrs($field['attrs'], array('name', 'id', 'value', 'type')) : '';
    ?>
        <tr>
            <th><label for="<?php echo $field_key; ?>"><?php echo $field['title']; ?></label></th>
            <td>
                <?php
                switch($field['type']) {
                    case 'wp_editor':
                        $content = get_the_author_meta($field_key, $user->ID);
                        $value = ($content) ? $content : $field['content'];
                        wp_editor($value, $field_key, $field['settings']);
                        break;
                    default:
                        $value = isset($field['value']) ? $field['value'] : esc_attr(get_the_author_meta($field_key, $user->ID));
                        ?>
                        <input
                            type="<?php echo $field['type']; ?>"
                            name="<?php echo $field_key; ?>"
                            id="<?php echo $field_key; ?>"
                            value="<?php echo $value; ?>"
                            <?php echo $attrs; ?>
                            <?php echo (isset($field['class'])) ? ('class="' . $field['class'] . '"') : ''; ?>
                            <?php echo (in_array($field['type'], array('checkbox', 'radio'))) ? checked(get_the_author_meta($field_key, $user->ID), 1, false) : ''; ?>
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
