<?php
if (!defined("ABSPATH")) exit;

/**
 * Class ABP_Template_Loader
 *
 * @since 1.0
 */
class ABP_Template_Loader
{
    /**
     * Include templates
     * @param $template_name string
     * @return string
     */
    public static function _include($template_name)
    {
        $is_file = self::load($template_name);
        return ($is_file) ? return_include_once($is_file) : '';
    }

    /**
     * Check if the template exist
     * @param $template_name string
     * @return string absolute directory path
     */
    public static function load($template_name)
    {
        $template_dir = ABP()->template_path() . $template_name;
        if (file_exists($template_dir)) {
            return $template_dir;
        }

        return ABP_TEMPLATES . $template_name;
    }
}
