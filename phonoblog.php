<?php

/*
Plugin Name: PhonoBlog
Plugin URI: https://github.com/ninnypants/PhonoBlog
Description: PhonoBlog is a simple plugin that allows you to call and record voice posts that are transcribed and posted to your blog. This plugin uses Twillio for call handling which costs money.
Version: 0.5
Author: ninnypants
Author URI: http://ninnypants.com
License: GPL2


Copyright 2010  Tyrel "ninnypants" Kelsey  (email : tyrel@ninnypants.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// settings page
add_action('admin_menu', 'pb_add_menus');
function pb_add_menus(){
add_submenu_page('options-general.php', 'PhonoBlog Settings', 'PhonoBlog Settings', 'publish_posts', 'phonoblog', 'phonoblog_settings');
}

function phonoblog_settings(){
	
	// save settings
	if($_POST['save'] && wp_verify_nonce($_POST['_wpnonce'], 'phonoblogsavesettings')){
		$settings = array();

		$settings['sid'] = $_POST['sid'];
		$settings['token'] = $_POST['token'];
		$settings['number'] = $_POST['number'];
		$settings['user'] = $_POST['user'];
		if(get_option('phonoblogsettings')){
			update_option('phonoblogsettings', $settings, '', 'no');
		}else{
			add_option('phonoblogsettings', $settings, '', 'no');
		}
	}
	$settings = get_option('phonoblogsettings');
	?>
	<div class="wrap">
	<form method="post" action="">
		<p>Account SID: <input type="text" name="sid" id="sid" value="<?php if($settings){ echo $settings['sid']; } ?>" /></p>
		<p>Auth Token: <input type="text" name="token" id="token" value="<?php if($settings){ echo $settings['token']; } ?>" />
		<p>Associate a user with their phone number.<br />
			<select name="user">
				<option value="">Select User</option>
				<?php
				$users = get_users(array('who' => 'authors'));
				
				foreach($users as $user){
					echo '<option value="'.$user->ID.'" '.($settings ? selected($settings['user'], $user->ID) : '').'>'.$user->user_login.'</option>';
				}
				?>
			</select>
			<input type="text" name="number" id="number" value="<?php if($settings){ echo $settings['number']; } ?>" /> 
		</p>
		<input type="submit" value="Save" id="save" name="save" class="button-primary" />
		<?php wp_nonce_field('phonoblogsavesettings'); ?>
	</form>
	</div>
	<?php

}
?>