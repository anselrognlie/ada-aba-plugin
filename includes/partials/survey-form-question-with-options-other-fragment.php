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

<input type="<?php echo $type ?>" name="<?php echo $question_slug ?><?php echo ($type === 'checkbox') ? '[]' : '' ?>" value="other" id="<?php echo $option_id ?>" class="ada-aba-survey-survey-option ada-aba-survey-survey-option-other-input"<?php echo $checked ? ' checked' : '' ?>>
<span class="ada-aba-survey-survey-option-other"><label for="<?php echo $option_id ?>"><?php echo (!empty($other_html) ? $other_html : '<p>Other</p>') ?><p>:</p></label>
  <textarea class="ada-aba-one-line" name="<?php echo $question_slug ?>-other"><?php echo ($value !== null) ? $value : '' ?></textarea>
</span>