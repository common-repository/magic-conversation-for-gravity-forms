<?php

/**
 * Magic Conversation For Gravity Forms Side Form class
 *
 * @author Flannian Feng
 */

if ( !class_exists('SideForm_MagicConversationForGravityForms' ) ):
class SideForm_MagicConversationForGravityForms {

    function __construct() {
        // add_action( 'init', array($this, 'showSideForm') );
	}
	
	public function change_ajax_submit_action_url($form_tag, $form) {
		$form_tag = preg_replace( "|action='(.*?)'|", "action='".(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/')."'", $form_tag );
    	return $form_tag;
	}

    public function showEmbedForm($form_id, $avatar_robot) {
		if(!class_exists('GFAPI')) return;
    	$form = GFAPI::get_form($form_id);
    	?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<title><?php echo esc_html( $form['title'] ); ?></title>
<style>
	html, body {height: 100px; overflow: hidden;}
</style>
<script type="text/javascript">
	var mcfgf_is_free = <?php echo mcfgf_is_free() ? 'true' : 'false' ?>;
</script>
<?php do_action( 'magic_conversation_iframe_head', $form_id, $form, dirname(__FILE__) ); ?>
</head>
<body>
	<div class="yakker-container"></div>
	<?php
	global $wp_scripts;
    $scripts = $wp_scripts->registered;
    foreach ( $scripts as $script ){
        wp_dequeue_script($script->handle);
    }
	do_action( 'magic_conversation_iframe_foot', $form_id, $form, dirname(__FILE__) ); 

	wp_print_scripts();
	?>
</body>
</html>
    	<?php
    }

    public function showSideFormButton($form_id, $avatar_robot) {
    	// $this->showSideFormButtonOrEmbed($form_id, $avatar_robot, false);
    	$showGlobalButton = !(isset($_GET['is_global']) && $_GET['is_global']=='no');
    	$this->showSideFormButtonV3($form_id, $avatar_robot, $showGlobalButton);
    }

    public function parseMergeTags($form, $text = false) {
    	return mcfgf_parseMergeTags($form, $text);
    }

    public function showSideFormButtonV3($form_id, $avatar_robot, $showGlobalButton = false) {
    	global $mcfgf_settings_basics;
    	unset($_POST);
		$form = GFAPI::get_form($form_id);
		
		$is_form_mode = isset($mcfgf_settings_basics['enable_only_as_form']) && $mcfgf_settings_basics['enable_only_as_form'] === 'on';

    	$welcom_message_title = $this->parseMergeTags($form, isset($form['gf_conversation']['conversation_welcome_message_title']) ? $form['gf_conversation']['conversation_welcome_message_title'] : (isset($mcfgf_settings_basics['conversation_tooltip_title']) ? $mcfgf_settings_basics['conversation_tooltip_title'] : false));
    	
    	

    	$welcom_message_sub_title = $this->parseMergeTags($form, isset($form['gf_conversation']['conversation_welcome_message_sub_title']) ? $form['gf_conversation']['conversation_welcome_message_sub_title'] : (isset($mcfgf_settings_basics['conversation_tooltip_sub_title']) ? $mcfgf_settings_basics['conversation_tooltip_sub_title'] : false));

    	$header_title = $this->parseMergeTags($form, isset($form['gf_conversation']['conversation_header_title']) ? $form['gf_conversation']['conversation_header_title'] : (isset($form['title']) ? $form['title'] : false));

    	$header_sub_title = $this->parseMergeTags($form, isset($form['gf_conversation']['conversation_header_sub_title']) ? $form['gf_conversation']['conversation_header_sub_title'] : (isset($form['description']) ? $form['description'] : false));

        $url_base = Settings_MagicConversationForGravityForms::$base_url;

        $domain = Settings_MagicConversationForGravityForms::$domain;
        $mcfgf_is_free = mcfgf_is_free();
    	$enable_notification_message = isset($mcfgf_settings_basics['enable_notification_message']) && $mcfgf_settings_basics['enable_notification_message']=='on';

    	$enable_conversation_header = isset($mcfgf_settings_basics['enable_conversation_header']) && $mcfgf_settings_basics['enable_conversation_header']=='on';

    	$ajax_url = admin_url( 'admin-ajax.php' );
    	$ver = MCFGFP_VER;
    	$exclasses = $showGlobalButton ? '' : ' mcfgfp-pin-app-enabled mcfgfp-pin-app-embed';

    	unset($_GET['action']); // delete edit parameter;
		unset($_GET['form_id']);
		// $_GET['pagenum'] = 5; // change page number
		$qs = http_build_query($_GET);

		$allow_image_button = isset($mcfgf_settings_basics['allow_image_button']) && $mcfgf_settings_basics['allow_image_button']=='on';

		$ex_button_class = $allow_image_button ? ' mcfgfp-pin-image-btn' : ' mcfgfp-pin-icon-btn';

		$image_button_width = isset($mcfgf_settings_basics['image_button_width_num']) && isset($mcfgf_settings_basics['image_button_width_unit']) ? $mcfgf_settings_basics['image_button_width_num'].$mcfgf_settings_basics['image_button_width_unit'] : '100%';
		$image_button_height = isset($mcfgf_settings_basics['image_button_height_num']) && isset($mcfgf_settings_basics['image_button_height_unit']) ? $mcfgf_settings_basics['image_button_height_num'].$mcfgf_settings_basics['image_button_height_unit'] : '100%';

		if($allow_image_button && isset($mcfgf_settings_basics['conversation_button_image_normal'])) {
			$ex_button_image_open = sprintf('<img width="%s" height="%s" src="%s" />', $image_button_width, $image_button_height, $mcfgf_settings_basics['conversation_button_image_normal']);
		} else {
			$ex_button_image_open = '';
		}
		
		if($allow_image_button && isset($mcfgf_settings_basics['conversation_button_image_active'])) {
			$ex_button_image_close = sprintf('<img width="%s" height="%s" src="%s" />', $image_button_width, $image_button_height, $mcfgf_settings_basics['conversation_button_image_active']);
		} else {
			$ex_button_image_close = '';
		}

    	?><div id="mcfgfp-pin-container">
			<?php if($showGlobalButton): ?>
		    <div class="mcfgfp-pin-btn-box<?php echo $ex_button_class; ?>">
		    	<div class="mcfgfp-pin-btn">
		    		<div class="mcfgfp-pin-btn-open"><?php echo $ex_button_image_open; ?></div>
		    		<div class="mcfgfp-pin-btn-close"><?php echo $ex_button_image_close; ?></div>
		    	</div>
		    	
		    		<div data-reactroot="" class="mcfgfp-notifications">
		    			<?php if ($enable_notification_message && ($welcom_message_title || $welcom_message_sub_title)): ?>
						<button class="mcfgfp-notifications-dismiss-button">Clear<span class="mcfgfp-notifications-dismiss-button-icon"></span></button>
						<?php endif ?>
						<div class="mcfgfp-notification">
							<div class="mcfgfp-say-hi">
								<?php if ($enable_notification_message && ($welcom_message_title || $welcom_message_sub_title)): ?>
								<div class="mcfgfp-say-hi-card">
									<?php if($welcom_message_title): ?>
									<div class="mcfgfp-say-hi-title">
										<div class="mcfgfp-say-hi-title-name-from">
											<span class="mcfgfp-say-hi-title-name"><?php echo $welcom_message_title; ?></span>
										</div>
									</div>
									<?php endif; ?>
									<?php if($welcom_message_sub_title): ?>
									<div class="mcfgfp-say-hi-body"><span><?php echo $welcom_message_sub_title; ?></span></div>
									<?php endif; ?>
								</div>
								<?php endif ?>
								<div data-reactroot="" class="mcfgfp-launcher-badge"><img src="<?php echo $avatar_robot; ?>" /></div>
							</div>

						</div>

					</div>
		    	
	    	</div>
			<?php endif; ?>
			<div class="mcfgfp-pin-app<?php echo $exclasses; ?>">
		    	<div class="mcfgfp-pin-conversation-form-frame mcfgfp-pin-app-child-div">
			    	<div class="mcfgfp-pin-conversation-form">
						<div class="mcfgfp-pin-conversation-form-background"></div>
						<div class="mcfgfp-pin-conversation">
							<div class="mcfgfp-pin-header-buttons">
								<div class="mcfgfp-pin-header-buttons-close">
									<div class="mcfgfp-pin-header-buttons-close-contents"></div>
								</div>
							</div>
							<div class="mcfgfp-pin-conversation-body-container">
								<div class="mcfgfp-pin-conversation-backgrounds">
									<div class="mcfgfp-pin-conversation-background mcfgfp-pin-conversation-background-1"></div>
								</div>
								<div class="mcfgfp-pin-conversation-body mcfgfp-pin-conversation-body-snapped" style="transform: translateY(0px); bottom: 0px;">
									<?php if($enable_conversation_header): ?>
									<div class="mcfgfp-pin-conversation-body-profile">
										<div class="mcfgfp-pin-conversation-profile mcfgfp-pin-conversation-profile-expanded">
											<div class="mcfgfp-pin-team-profile">
												<div class="mcfgfp-pin-team-profile-full" style="opacity: 1;">
													<div class="iconfont icon-close mcfgfp-pin-team-close">
													</div>
													<!-- <div class="iconfont icon-shuaxin mcfgfp-pin-team-refresh">
													</div> -->
													<div class="mcfgfp-pin-team-profile-full-team-name">
														<?php echo $header_title; ?>
														<?php //echo $mcfgf_settings_basics['conversation_header_title']; ?>
													</div>
													<?php if($header_sub_title): ?>
													<div class="mcfgfp-pin-team-profile-full-response-delay">
														<?php echo $header_sub_title; ?>
														<?php // echo $mcfgf_settings_basics['conversation_header_sub_title']; ?>
													</div>
													<?php endif;?>
													<div class="mcfgfp-pin-team-profile-full-avatar-container" style="display: none">
														<div class="mcfgfp-pin-team-profile-full-avatar mcfgfp-pin-index-0">
															<div class="mcfgfp-pin-avatar"><img src="<?php echo $avatar_robot; ?>">
															</div>
															<div class="mcfgfp-pin-team-profile-full-admin-name">
																<?php echo $mcfgf_settings_basics['conversation_header_username']; ?>
															</div>
														</div>
													</div>
													<div class="mcfgfp-pin-team-profile-full-intro" style="display: none">
														<span><?php echo $mcfgf_settings_basics['conversation_header_welcome_message']; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php else: ?>
										<div class="iconfont icon-close mcfgfp-pin-team-close mcfgfp-pin-team-close-ex">
													</div>
									<?php endif; ?>
									<div class="mcfgfp-pin-conversation-body-parts" style="top: 195px; bottom: 0px;">
										<div class="mcfgfp-pin-conversation-body-parts-wrapper">
											<div id="mcfgfp-pin-conversation-parts11" class="mcfgfp-pin-conversation-parts magic_conversation_embed_container<?php if($is_form_mode) echo ' magic_conversation_embed_container_form'; ?>" style="transform: translateY(0px);">
												<div class="magic_conversation_toggle_fullscreen"><i class="icon iconfont icon-shuaxin"></i><i class="icon iconfont icon-fullscreen"></i></div>
												
												<?php if($is_form_mode): ?>
													<?php
														add_filter( 'gform_form_tag', array($this, 'change_ajax_submit_action_url'), 10, 2 );
														gravity_form($form_id, true, true, false, null, $ajax = true); 
													?>
												<?php else: ?>
													<iframe data_src="<?php echo mcfgf_get_conversation_permalink($form_id, 'ver='.$ver.'&'.$qs); ?>" style="width: 100%; height: 100%;overflow:hidden;border: none;"></iframe>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</div>
								<?php if (false && $mcfgf_is_free): ?>
									<div class="mcfgfp-pin-link-container"><a href="<?php echo $url_base; ?>" class="mcfgfp-pin-link" target="_blank">Powered by <?php echo $domain; ?></a></div>
								<?php endif ?>
							</div>
							<div class="mcfgfp-pin-conversation-footer">
								<div class="mcfgfp-pin-composer">
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
	    </div>
    	<?php
    }

    public function showSideFormButtonOrEmbed($form_id, $avatar_robot, $isEmbed = false) {
    	global $mcfgf_settings_basics;
    	unset($_POST);
        $url_base = Settings_MagicConversationForGravityForms::$base_url;

        $domain = Settings_MagicConversationForGravityForms::$domain;
        $mcfgf_is_free = !file_exists(dirname(__FILE__).'/license.php');
    	$enable_notification_message = isset($mcfgf_settings_basics['enable_notification_message']) && $mcfgf_settings_basics['enable_notification_message']=='on';
    	if(!$isEmbed) :
        ?>
		<div id="mcfgfp-pin-container">
			
		    <div class="mcfgfp-pin-btn-box">
		    	<div class="mcfgfp-pin-btn">
		    		<div class="mcfgfp-pin-btn-open"></div>
		    		<div class="mcfgfp-pin-btn-close"></div>
		    	</div>
		    	<?php if ($enable_notification_message): ?>
		    		<div data-reactroot="" class="mcfgfp-notifications">
						<button class="mcfgfp-notifications-dismiss-button">Clear<span class="mcfgfp-notifications-dismiss-button-icon"></span></button>
						<div class="mcfgfp-notification">
							<div class="mcfgfp-say-hi">
								<div class="mcfgfp-say-hi-card">
									<div class="mcfgfp-say-hi-title">
										<div class="mcfgfp-say-hi-title-name-from">
											<span class="mcfgfp-say-hi-title-name"><?php echo $mcfgf_settings_basics['conversation_header_title']; ?></span>
										</div>
									</div>
									<div class="mcfgfp-say-hi-body"><span><?php echo $mcfgf_settings_basics['conversation_header_welcome_message']; ?></span></div>
								</div>
								<div data-reactroot="" class="mcfgfp-launcher-badge"><img src="<?php echo $avatar_robot; ?>" /></div>
							</div>

						</div>

					</div>
		    	<?php endif ?>
	    	</div>

			<div class="mcfgfp-pin-app">
		    	<div class="mcfgfp-pin-conversation-form-frame">
			    	<div class="mcfgfp-pin-conversation-form">
						<div class="mcfgfp-pin-conversation-form-background"></div>
						<div class="mcfgfp-pin-conversation">
							<div class="mcfgfp-pin-header-buttons">
								<div class="mcfgfp-pin-header-buttons-close">
									<div class="mcfgfp-pin-header-buttons-close-contents"></div>
								</div>
							</div>
							<div class="mcfgfp-pin-conversation-body-container">
								<div class="mcfgfp-pin-conversation-backgrounds">
									<div class="mcfgfp-pin-conversation-background mcfgfp-pin-conversation-background-1"></div>
								</div>
								<div class="mcfgfp-pin-conversation-body mcfgfp-pin-conversation-body-snapped" style="transform: translateY(0px); bottom: 0px;">
									<div class="mcfgfp-pin-conversation-body-profile">
										<div class="mcfgfp-pin-conversation-profile mcfgfp-pin-conversation-profile-expanded">
											<div class="mcfgfp-pin-team-profile">
												<div class="mcfgfp-pin-team-profile-full" style="opacity: 1;">
													<div class="iconfont icon-close mcfgfp-pin-team-close mcfgfp-mobile">
													</div>
													<div class="mcfgfp-pin-team-profile-full-team-name">
														<?php echo $mcfgf_settings_basics['conversation_tooltip_title']; ?>
													</div>
													<div class="mcfgfp-pin-team-profile-full-response-delay">
														<?php echo $mcfgf_settings_basics['conversation_tooltip_sub_title']; ?>
													</div>
													<div class="mcfgfp-pin-team-profile-full-avatar-container">
														<div class="mcfgfp-pin-team-profile-full-avatar mcfgfp-pin-index-0">
															<div class="mcfgfp-pin-avatar"><img src="<?php echo $avatar_robot; ?>">
															</div>
															<div class="mcfgfp-pin-team-profile-full-admin-name">
																<?php echo $mcfgf_settings_basics['conversation_header_username']; ?>
															</div>
														</div>
													</div>
													<div class="mcfgfp-pin-team-profile-full-intro">
														<span><?php echo $mcfgf_settings_basics['conversation_header_welcome_message']; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="mcfgfp-pin-conversation-body-parts" style="top: 195px; bottom: 0px;">
										<div class="mcfgfp-pin-conversation-body-parts-wrapper">
											<div id="mcfgfp-pin-conversation-parts" class="mcfgfp-pin-conversation-parts" style="transform: translateY(0px);">
												
											</div>
										</div>
									</div>
								</div>
								<?php if ($mcfgf_is_free): ?>
									<div class="mcfgfp-pin-link-container"><a href="<?php echo $url_base; ?>" class="mcfgfp-pin-link" target="_blank">Powered by <?php echo $domain; ?></a></div>
								<?php endif ?>
							</div>
							<div class="mcfgfp-pin-conversation-footer">
								<div class="mcfgfp-pin-composer">
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	    </div>
		
	    <div id="mcfgfp-gf-container">
	    <?php endif; ?>
	    <?php 
	    	$formhtml = gravity_form( $form_id,false, false, false, '', true, 1, false); 
			// $formhtml = str_replace('<form ', '<form cf-prevent-autofocus ' , $formhtml);
			echo $formhtml;
			?>
		<?php if(!$isEmbed) :?>
        </div>
        <?php endif; ?>
        <?php
    }
    
}
endif;


