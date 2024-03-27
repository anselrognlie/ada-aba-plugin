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

<?php echo $base_content ?>
<textarea name="<?php echo $question_slug ?>"><?php echo ($value !== null) ? $value : '' ?></textarea>
