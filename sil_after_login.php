<?php
/*
Plugin Name: Execute After Login
Plugin URI: http://www.silencesoft.net/
Description: Run php code after user login.
Version: 0.1
Author: Byron Herrera
Author URI: http://byronh.axul.net
				
CHANGELOG
0.1 - First release

Copyright 2008  Byron Herrera  (email : bh at axul dot net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function sil_after_install() {
	global $wpdb;
	add_option("sil_after_code", '// "Hello world";');
}

function sil_after_deinstall() {
	delete_option("sil_after_code");
}

function sil_after_options() {
  	if (function_exists('add_options_page'))
	{
		add_options_page('Execute Options', 'Execute after login', 9, basename(__FILE__), 'sil_after_options_page');
	}
}

function sil_after_options_page() {
	global $wpdb;
?>
<div class="wrap">
<h2>Execute after login - <?php _e('Options', 'sil_rss'); ?></h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Code', 'sil_after'); ?>:</th>
</tr>
<tr valign="top">
<td><textarea name="sil_after_code" rows="20" cols="75"><?php echo get_option('sil_after_code'); ?></textarea>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="sil_after_code" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
</p>

</form>

</div>
<?php
}

function sil_after_run($user_login) {
	global $wpdb;
	$user = get_userdatabylogin($user_login);
	$user_id = $user->ID;
 	// $user_id = wp_cache_get($user_login, 'userlogins');
	if ($user_id) {
	 	$code = get_option('sil_after_code');
// echo $code;
		eval($code);
	}
// exit;
}

register_activation_hook(__FILE__, 'sil_after_install');
register_deactivation_hook(__FILE__, 'sil_after_deinstall');
add_action('admin_menu', 'sil_after_options');
add_action('wp_login', 'sil_after_run');
