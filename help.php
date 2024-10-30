<?php

if(!function_exists('mcfgf_tooltip_help_add_admin_menu')) :

add_action( 'admin_menu', 'mcfgf_tooltip_help_add_admin_menu' );


function mcfgf_tooltip_help_add_admin_menu(  ) { 

	add_submenu_page( 
          'magic_conversation_for_gravity_forms' 
        , 'Help' 
        , 'Help'
        , 'manage_options'
        , 'mcfgf_tooltip_help'
        , 'mcfgf_tooltip_help_page'
    );
//plugins_url( 'assets/img/icon-17.png', __FILE__ )
}




function mcfgf_tooltip_help_page(  ) {
	$url_base = Settings_MagicConversationForGravityForms::$base_url; 
	?>
		<style>
			pre.mcfgf_code {
			  /*! background: #eee; */
			/*! background-image: -webkit-linear-gradient(#eee 50%,#e0e0e0 50%); */
			/*! background-image: -moz-linear-gradient(#eee 50%,#e0e0e0 50%); */
			/*! background-image: -ms-linear-gradient(#eee 50%,#e0e0e0 50%); */
			/*! background-image: -o-linear-gradient(#eee 50%,#e0e0e0 50%); */
			background-image: linear-gradient(#fff 50%,#fcfcfc 50%);
			background-position: 0 0;
			background-repeat: repeat;
			background-size: 4.5em 4.5em;
			color: #555;
			font-family: monospace, serif;
			/*font-size: 16px;*/
			line-height: 2.25em;
			margin: 1em 0em;
			overflow: auto;
			padding: 0 1.25em;
			max-width:100%;
			white-space: pre-wrap;       /* css-3 */
			 white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
			 white-space: -pre-wrap;      /* Opera 4-6 */
			 white-space: -o-pre-wrap;    /* Opera 7 */
			 word-wrap: break-word;       /* Internet Explorer 5.5+ */
			}
		</style>
		<div class="wrap"><h1>Magic Conversation For Gravity Forms Help</h1></div>
		<p>Please review the plugin documentation and frequently asked questions (FAQ) first. If you still can't find the answer <a target="_blank" href="<?php echo $url_base; ?>/contact/" target="_blank">open a support ticket</a> and we will be happy to answer your questions and assist you with any problems. Please note: If you have not purchased a license from us, you will not have access to these help resources.</p>


		<h3>Quick Start</h3>

		<p>Show a Conversation Button on the Home page or the entire website:</p>

		<p><pre class="mcfgf_code">Settings -> Conversation Form -> Choose a form</pre></p>

		<p>Embed a conversation into a post/page with a short code:</p>

		<p><pre class="mcfgf_code">[magic-conversation id="1" width="100%" height="395px"]</pre></p>

		<p>Add a Floating Conversation Button to a specific page with a short code:</p>

		<p><pre class="mcfgf_code">[magic-conversation-button id="1"]</pre></p>

		<p>Trigger a conversation with a link:</p>

		<p><pre class="mcfgf_code">&lt;a href="/open-magic-conversation?form_id=1"&gt;Open Conversation&lt;/a&gt;</pre></p>

		<p>Trigger a conversation with JavaScript code:</p>

		<p><pre class="mcfgf_code">window.mcfgf_open_magic_conversation("/open-magic-conversation?form_id=1");</pre></p>

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