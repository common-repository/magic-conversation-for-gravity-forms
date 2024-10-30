<?php

if(!function_exists('mcfgf_tooltip_demo_add_admin_menu')) :

add_action( 'admin_menu', 'mcfgf_tooltip_demo_add_admin_menu' );


function mcfgf_tooltip_demo_add_admin_menu(  ) { 

	add_submenu_page( 
          'magic_conversation_for_gravity_forms' 
        , 'Demos' 
        , 'Demos'
        , 'manage_options'
        , 'mcfgf_tooltip_demo'
        , 'mcfgf_tooltip_demo_page'
    );
//plugins_url( 'assets/img/icon-17.png', __FILE__ )
}




function mcfgf_tooltip_demo_page(  ) {
	$url_base = Settings_MagicConversationForGravityForms::$base_url; 
	?>
		<div class="wrap"><h1>Magic Conversation For Gravity Forms Pro Demos</h1></div>
		<p>Please review the plugin documentation and frequently asked questions (FAQ) first. If you still can't find the answer <a target="_blank" href="<?php echo $url_base; ?>/contact/" target="_blank">open a support ticket</a> and we will be happy to answer your questions and assist you with any problems. Please note: If you have not purchased a license from us, you will not have access to these demo resources.</p>
		<h3>Documentation</h3>
		<ul>
			<li><a href="<?php echo $url_base; ?>/documentation/" target="_blank">Online Documentation</a></li>
			<li><a target="_blank" href="<?php echo $url_base; ?>/faq/">FAQ</a></li>
		</ul>
		<h3>More</h3>
		<ul>
			<li><a href="<?php echo $url_base; ?>/contact/" target="_blank">Open a support ticket</a></li>
			<li><a  target="_blank" href="<?php echo $url_base; ?>/pricing/">Purchase Pro or Developer version</a></li>
		</ul>
	<?php
}
endif;
?>