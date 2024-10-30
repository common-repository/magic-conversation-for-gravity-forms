<?php 

if(!function_exists('mcfgf_rewrite_activation')) {
	register_activation_hook( dirname(__FILE__).'/plugin-index.php', 'mcfgf_rewrite_activation' );
	// register_deactivation_hook( dirname(__FILE__).'/plugin-index.php', 'mcfgf_rewrite_deactivation' );
	function mcfgf_rewrite_activation()
	{
	    mcfgf_rewrite_add_rewrites();
	    flush_rewrite_rules();
	}

	// function mcfgf_rewrite_deactivation() {
	//     flush_rewrite_rules();
	// }

	function mcfgf_rewrite_add_rewrites() {
		add_rewrite_rule(
	        'magic-conversation/([0-9]+)/?$',
	        // 'wp-admin/admin-ajax.php?action=yakker_get_gf&form_id=$matches[1]',
	        'index.php?mcfgf_id=$matches[1]',
	        'top' );
	}
}

?>