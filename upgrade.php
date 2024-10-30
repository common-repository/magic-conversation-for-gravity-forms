<?php 

if(!function_exists('mcfgf_wordpress_version_notice')) {
	$mcfgfBaseDir = dirname(__FILE__);

	add_action( 'admin_notices', 'mcfgf_upgrade_notices' );
	add_filter('plugin_action_links_' . plugin_basename($mcfgfBaseDir.'/plugin-index.php'), 'mcfgf_upgrade_premium_action_link');

	function mcfgf_upgrade_premium_action_link($links) {
		return array_merge(array('mcfgfPluginCallout' => '<a href="'.Settings_MagicConversationForGravityForms::$base_url.'/pricing/" target="_blank"><strong style="color: #6A9A22; display: inline;">Upgrade To Premium</strong></a>'), $links);
	}

	function mcfgf_upgrade_notices() {
		global $pagenow;
		global $current_user;
		$userid = $current_user->ID;
		$settings = get_option('mcfgf_settings',false);

		if ( $settings ) { // already done.
			// Check if plugin install date is set in database
			$opt_install = get_option('mcfgf_install');
			
			if($opt_install === false) {
				// Set install date to today
				update_option('mcfgf_install', date('Y-m-d'));
			}


			// Compare install date with today
			$date_install = isset($opt_install) ? $opt_install : date('Y-m-d');

			
			// If install date is more than 10 days old...
			if(strtotime($date_install) <= strtotime('-10 days')){
				// If the user clicked to dismiss notice...
				if ( isset( $_GET['dismiss_mcfgf_ug_notice'] ) && 'yes' == $_GET['dismiss_mcfgf_ug_notice'] ) {
					
					// Update user meta
					add_user_meta( $userid, 'ignore_mcfgf_ag_notice', 'yes', true );
				}
				if ( !get_user_meta( $userid, 'ignore_mcfgf_ag_notice' ) ) {
					mcfgf_wordpress_version_notice();
				}
			}
			return;
		}
	}

	// Alert plugin update message
	function mcfgf_wordpress_version_notice() {
		
		global $pagenow;

		$baseUrl = Settings_MagicConversationForGravityForms::$base_url;
		
		echo '<style type="text/css">';
		echo '.mcfgf_plugins_page_banner {
			border: 1px solid #d4d4d4;
			margin: 12px 0;
			background: #FFF;
			position: relative;
			overflow: hidden;
		}
		.mcfgf_plugins_page_banner .text {
			color: #000;
			font-size: 15px;
			line-height: 26px;
			margin: 18px 18px 14px;
			float: left;
			width: auto;
			max-width: 80%;
		}
		.mcfgf_plugins_page_banner .text span {
			font-size: 12px;
			opacity: 0.7;
		}
		.mcfgf_plugins_page_banner .button {
			float: left;
			border: none; 
			font-size: 14px;
			margin: 18px 0 18px 16px;
			padding: 12px 0;
			color: #FFF;
			text-shadow: none;
			font-weight: bold;
			background: #0074A2;
			-moz-border-radius: 3px;
			border-radius: 3px;
			-webkit-border-radius: 3px;
			text-decoration: none;
			height: 50px;
			text-align: center;
			text-transform: uppercase;
			width: 147px;
			box-shadow: none;
			line-height: 26px;
		}
		.mcfgf_plugins_page_banner .button:hover,
		.mcfgf_plugins_page_banner .button:focus {    
			background: #222;
			color: #FFF;
		}
		.mcfgf_plugins_page_banner .icon {
			float: right;
			margin: 12px 8px 8px 0;
		}
		.mcfgf_plugins_page_banner .close_icon {
			float: right;
			margin: 8px;
			cursor: pointer;
		}
		.mcfgf_plugins_page_banner .close_icon:before {
			background: 0 0;
			color: #b4b9be;
			content: "\f153";
			display: block;
			font: 400 16px/20px dashicons;
			speak: none;
			height: 20px;
			text-align: center;
			width: 20px;
			-webkit-font-smoothing: antialiased;
		}';
		echo '</style>';
		
		echo '<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
				<div class="mcfgf_plugins_page_banner">
					<a href="'.$pagenow.'?dismiss_mcfgf_ug_notice=yes" class="close_icon" data-repository="wpml" style="text-decoration:none"><span class="screen-reader-text">Dismiss</span></a>
					<div class="button_div">
						<a class="button" target="_blank" href="'.$baseUrl.'/pricing/">Learn More</a>				
					</div>
					<div class="text">
						It\'s time to consider upgrading <strong>Magic Conversation For Gravity Forms</strong> to the <strong>PRO</strong> version.<br />
						<span>Extend standard plugin functionality with new, enhanced options.</span>
					</div>
				</div>  
			</div>';
	}
}

?>