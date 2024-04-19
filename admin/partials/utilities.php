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

  <div>
    <h2>Utilities</h2>
  </div>

  <div>
    <h3>Execute Query</h3>
    <p><em>This is very dangerous. The database could very easily be corrupted by running arbitrary queries. Use with extreme caution.</em></p>
    <form id="ada-aba-execute-query-form">
      <textarea id="ada-aba-execute-query-query" cols="80" rows="10" name="query"></textarea>
      <div>
        <input type="submit" value="Execute">
      </div>
    </form>
    <div id="ada-aba-execute-query-result"></div>
  </div>

</div>