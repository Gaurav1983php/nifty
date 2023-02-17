<?php /*
Plugin Name: Nifty Marketing
Plugin URI: https://niftymarketing.com.au/
Description: Remove pages by name
Author: Vivek M
Version: 1.8
*/


function remove_page_by_name(){
    
    	include('remove_page_by_name.php');
}


add_action('admin_menu', 'nifty_plugin_settings');



function nifty_plugin_settings() {

	add_submenu_page('options-general.php', 'Nifty Remove Pages', 'Nifty Remove Pages', 'administrator', 'nifty_remove_page', 'remove_page_by_name');
	



}

?>