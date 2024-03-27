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

<input type="<?php echo $type ?>" name="<?php echo $question_slug ?><?php echo ($type === 'checkbox') ? '[]' : '' ?>" value="<?php echo $option ?>" id="<?php echo $option_id ?>" class="ada-aba-survey-survey-option"<?php echo $checked ? ' checked' : '' ?>>
<label for="<?php echo $option_id ?>"><?php echo $option_html ?></label>
