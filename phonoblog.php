<?php

/*
Plugin Name: PhonoBlog
Plugin URI: http://phonoblog.com
Description: Voice to blog posts.
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
add_submenu_page('options-general.php', 'PhonoBlog Settings', 'publish_posts', 'phonoblog', 'phonoblog_settings');

function phonoblog_settings(){
	
	// save settings
	if($_POST['save'] && wp_verify_nonce($_POST['_wpnonce'], 'phonoblogsavesettings')){
		
	}

}