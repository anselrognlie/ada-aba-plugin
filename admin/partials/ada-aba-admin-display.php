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
 * @subpackage Ada_Aba/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
	<form method="post" action="options.php">
		<?php
			settings_errors();
			settings_fields( 'ada-aba-settings' );
			do_settings_sections( 'ada-aba-settings' );
			submit_button();
		?>
	</form>
</div>

<div>
	<p>A useful snippet to generate a good private key is
		<p>
		<code>
		python -c 'import secrets; print(secrets.token_hex())'
		</code>
</p>
	</p>
</div>