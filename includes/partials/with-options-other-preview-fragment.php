<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes/partials
 */
?>

<p><input type="<?php echo $control_type ?>" name="ada-aba-question-preview-panel-wo-option-input"></p><div><label><?php echo (!empty($other_html) ? $other_html : 'Other')?>:</label> <input type="text"></div>