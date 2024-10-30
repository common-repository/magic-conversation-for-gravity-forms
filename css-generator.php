<?php

if(!function_exists('mcfgf_conversation_load_wp_media_files')) :

add_action( 'admin_menu', 'mcfgf_conversation_generator_add_admin_menu' );
add_action( 'admin_init', 'mcfgf_conversation_generator_init' );


// UPLOAD ENGINE
function mcfgf_conversation_load_wp_media_files() {
	// $mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
 //    $modes = array( 'grid', 'list' );
 //    if ( isset( $_GET['mode'] ) && in_array( $_GET['mode'], $modes ) ) {
 //        $mode = $_GET['mode'];
 //        update_user_option( get_current_user_id(), 'media_library_mode', $mode );
 //    }
 //    if( ! empty ( $_SERVER['PHP_SELF'] ) && 'upload.php' === basename( $_SERVER['PHP_SELF'] ) && 'grid' !== $mode ) {
 //        wp_dequeue_script( 'media' );
 //    }
 //    wp_enqueue_script('thickbox');
    wp_enqueue_media();
 //    wp_enqueue_script( 'media-grid' );
	// wp_enqueue_script( 'media' );
	// die();
	// 
	// wp_enqueue_script('media-upload'); //Provides all the functions needed to upload, validate and give format to files.
	// wp_enqueue_script('thickbox'); //Responsible for managing the modal window.
	// wp_enqueue_style('thickbox'); //Provides the styles needed for this window.
}
// add_action( 'admin_enqueue_scripts', 'mcfgf_conversation_load_wp_media_files' );

function mcfgf_conversation_generator_add_admin_menu(  ) {

	add_submenu_page( 
          'magic_conversation_for_gravity_forms' 
        , 'Conversation Style Generator' 
        , 'Conversation Style Generator'
        , 'manage_options'
        , 'mcfgf_conversation_generator'
        , 'mcfgf_conversation_generator_page'
    );
//plugins_url( 'assets/img/icon-17.png', __FILE__ )
}

function mcfgf_conversation_generator_before_save($options_conversation_generator, $old_value) {
	$license_control = true;
	if($license_control) {
		$options = get_option( 'mcfgf_settings' );
		if(isset($options['license_key']) && $options['license_key']) {
			if(function_exists('mcfgf_check_license')) {
				$is_valid_license = mcfgf_check_license($options['license_key'], true);
				if($options['is_valid_license_key'] != $is_valid_license) {
					$options['is_valid_license_key'] = $is_valid_license;
					update_option('mcfgf_settings', $options);
				}
			}
		}
	}
	return $options_conversation_generator;
}


function mcfgf_conversation_generator_init(  ) { 

	register_setting( 'mcfgf_conversation_generator', 'mcfgf_conversation_generator' );
	add_filter( 'pre_update_option_mcfgf_conversation_generator', 'mcfgf_conversation_generator_before_save', 10, 2 );

	add_settings_section(
		'mcfgf_conversation_generator_section', 
		__( 'Your section description', 'magic_conversation_for_gravity_forms' ), 
		'mcfgf_conversation_generator_section_callback', 
		'mcfgf_conversation_generator'
	);

	add_settings_field( 
		'css_code', 
		__( 'Css Code', 'magic_conversation_for_gravity_forms' ), 
		'mcfgf_conversation_generator_css_code_render', 
		'mcfgf_conversation_generator', 
		'mcfgf_conversation_generator_section' 
	);

	add_settings_field( 
		'css_options', 
		__( 'Css Code', 'magic_conversation_for_gravity_forms' ), 
		'mcfgf_conversation_generator_css_options_render', 
		'mcfgf_conversation_generator', 
		'mcfgf_conversation_generator_section' 
	);

	add_settings_field( 
		'js_code', 
		__( 'Js Code', 'magic_conversation_for_gravity_forms' ), 
		'mcfgf_conversation_generator_js_code_render', 
		'mcfgf_conversation_generator', 
		'mcfgf_conversation_generator_section' 
	);

	add_settings_field( 
		'avatar_robot', 
		__( 'Robot Avatar', 'magic_conversation_for_gravity_forms' ), 
		'mcfgf_conversation_generator_avatar_robot_render', 
		'mcfgf_conversation_generator', 
		'mcfgf_conversation_generator_section' 
	);

	add_settings_field( 
		'avatar_user', 
		__( 'User Avatar', 'magic_conversation_for_gravity_forms' ), 
		'mcfgf_conversation_generator_avatar_user_render', 
		'mcfgf_conversation_generator', 
		'mcfgf_conversation_generator_section' 
	);

	if(isset($_GET['page'])) {
		if (  in_array( $_GET['page'], array( 'mcfgf_conversation_generator' ) ) !== false ) { # load js for options page
			mcfgf_load_css_generator_css_and_js();
		}
	}
}

function mcfgf_load_css_generator_css_and_js() {
    $styles = array(
        array('handle' => 'jquery-ui', 'src' => 'jquery-ui.custom.css', 'deps' => false, 'media'=>"all"),
        array('handle' => 'jquery.miniColors', 'src' => 'jquery.miniColors.css', 'deps' => false, 'media'=>"all"),
        array('handle' => 'mcfgf_css_generator_style', 'src' => 'style.css', 'deps' => false, 'media'=>"all"),
        array('handle' => 'jquery.qtip', 'src' => '../../css/jquery.qtip.min.css', 'deps' => false, 'media'=>"all"),
        array('handle' => 'mcfgf_css_generator_init', 'src' => 'init.css', 'deps' => false, 'media'=>"all"),
        array('handle' => 'mcfgf', 'src' => '../../css/custom.css', 'deps' => false, 'media'=>"all")
    );
    for ($i = 0; $i < sizeof($styles); $i++) {
        wp_enqueue_style($styles[$i]['handle'], plugins_url( "assets/css-generator/css/". $styles[$i]['src'], __FILE__ ) , $styles[$i]['deps'], isset($scripts[$i]['ver']) ? $scripts[$i]['ver'] : MCFGFP_VER, $styles[$i]['media'] );
    }

    $scripts = array(
    	//array('handle' => 'jquery.conversational-form', 'src'=>'../../js/20171025/conversational-form.min.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        // ['handle' => 'jquery.qtip', 'src'=>'../../js/jquery.qtip.min.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true],
        // ['handle' => 'jquery-ui', 'src'=>'libs/jquery-ui-1.8.20.custom.min.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true],
        array('handle' => 'jquery.mousewheel', 'src'=>'libs/jquery.mousewheel.min.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        array('handle' => 'jquery.miniColors', 'src'=>'libs/jquery.miniColors.min.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        array('handle' => 'jquery.cookie', 'src'=>'libs/jquery.cookie.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        array('handle' => 'mcfgf_tinycolor', 'src'=>'tinycolor.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        array('handle' => 'mcfgf_tooltip', 'src'=>'tooltip.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        array('handle' => 'mcfgf_tweaker', 'src'=>'tweaker.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        array('handle' => 'mcfgf_script', 'src'=>'script.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        //array('handle' => 'mcfgf', 'src'=>'../../js/magic-conversation.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        //array('handle' => 'mcfgf_conditional_logic', 'src'=>'../../js/conditional-logic.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
        // array('handle' => 'mcfgf', 'src'=>'../../js/custom.js','dep'=> array( 'jquery' ),'var'=> false,'in_foot'=> true),
    );

    

    for ($i=0; $i < sizeof($scripts); $i++) {
        wp_enqueue_script( $scripts[$i]['handle'], plugins_url( "assets/css-generator/js/". $scripts[$i]['src'], __FILE__ ), $scripts[$i]['dep'], isset($scripts[$i]['ver']) ? $scripts[$i]['ver'] : MCFGFP_VER, $scripts[$i]['in_foot'] );    
    }

    $mcfgf_options = get_option('mcfgf_conversation_generator', false);
	wp_localize_script( 'mcfgf', 'mcfgf', $mcfgf_options);
	
    MagicConversationForGravityForms::mcfgf_localize_settings();
    wp_enqueue_media();
    //required for upload image
    // mcfgf_conversation_load_wp_media_files();
    // wp_enqueue_media();
 //    wp_enqueue_style('thickbox'); // call to media files in wp
	// wp_enqueue_script('thickbox');
	// wp_enqueue_script('media-upload'); 
}


function mcfgf_conversation_generator_css_code_render() { 
	$options = get_option( 'mtfcf7_conversation_generator' );
	?>
	<input id="mcfgf-conversation-generator-css-code" type='text' name='mcfgf_conversation_generator[css_code]' value='<?php echo $options['css_code']; ?>'>
	<?php

}

function mcfgf_conversation_generator_css_options_render() { 
	$options = get_option( 'mcfgf_conversation_generator' );
	?>
	<input id="mcfgf-conversation-generator-css-options" type='text' name='mcfgf_conversation_generator[css_options]' value='<?php echo $options['css_options']; ?>'>
	<?php

}

function mcfgf_conversation_generator_js_code_render() { 
	$options = get_option( 'mcfgf_conversation_generator' );
	?>
	<input id="mcfgf-conversation-generator-js-code" type='text' name='mcfgf_conversation_generator[js_code]' value='<?php echo $options['js_code']; ?>'>
	<?php
}

function mcfgf_conversation_generator_avatar_robot_render() { 
	$options = get_option( 'mcfgf_conversation_generator' );
	?>
	<input id="mcfgf-conversation-generator-avatar-robot" type='text' name='mcfgf_conversation_generator[avatar_robot]' value='<?php echo $options['avatar_robot']; ?>'>
	<?php
}

function mcfgf_conversation_generator_avatar_user_render() { 
	$options = get_option( 'mcfgf_conversation_generator' );
	?>
	<input id="mcfgf-conversation-generator-avatar-user" type='text' name='mcfgf_conversation_generator[avatar_user]' value='<?php echo $options['avatar_user']; ?>'>
	<?php
}


function mcfgf_conversation_generator_section_callback(  ) { 
	echo __( 'Use this tool to generate your custom conversation style.', 'magic_conversation_for_gravity_forms' );
}

function mcfgf_conversation_generator_random_avatar_img($i = 0) {
	if($i==0) {
		$i = rand(1, 20);
	}
	return str_replace(home_url(), "", plugins_url('assets/avatars/'.$i.'.png', __FILE__ ));
}

function mcfgf_conversation_generator_avatar_for_name($name) {
	$options = get_option( 'mcfgf_conversation_generator' );

	return ((!isset($options[$name])) || ($options[$name]=='random')) ?  mcfgf_conversation_generator_random_avatar_img() : $options[$name];
}

function mcfgf_conversation_generator_is_active_avatar_picker_item($name, $i) {
	$options = get_option( 'mcfgf_conversation_generator' );
	return isset($options[$name]) && $options[$name] == mcfgf_conversation_generator_random_avatar_img($i);
}


function mcfgf_render_avatar_picker($label, $name) {
	?>
	<div class="container avatar-picker">
		<div class="avatar-<?php echo $name; ?> avatar-preview">
			<img name="<?php echo $name; ?>" src="<?php echo mcfgf_conversation_generator_avatar_for_name($name); ?>" />
		</div>
		<div class="dropdown">
		    <input type="checkbox" id="dropdown-selector-<?php echo $name; ?>" class="dropdown-input"/>
		    
		    <label for="dropdown-selector-<?php echo $name; ?>" class="label-dropdown"><?php echo $label; ?> <span class="fa fa-caret-down"></span></label>
		    
		    <ul class="dropdown-content">
		    	<li class="dropdown-items">
		    		<a href="#" class="upload p10">Upload your own</a>
		    	</li>
		    	<li class="dropdown-items">
		    		<a href="#" class="random p10">Use random avatar</a>
		    	</li>
		      	<li class="dropdown-items"> 
		      		<span class="p10">Or pick an avatar...</span>
		        	<ul class="dropdown-avatar">
		        	<?php for($i=1; $i < 21; $i++): ?>
		        		<li class="avatar-items<?php mcfgf_conversation_generator_is_active_avatar_picker_item($name, $i) ? ' active' : '' ?>" ><img src="<?php echo mcfgf_conversation_generator_random_avatar_img($i); ?>" /></li>
		        	<?php endfor; ?>
		      		</ul>
		      	</li>
		    </ul>
		</div>
	</div>
	<?php
}


function mcfgf_conversation_generator_page() { 
	mcfgf_conversation_generator_preview_box();
	?>
	<button id="toggle-conversation" type="button" class="btn btn-default">Turn on conversation</button>
	<form action='options.php' id="mcfgf-conversation-generator-form" method='post'>
		<h1>Conversation Style Generator</h1>
		<style>
			#mcfgf-conversation-generator-form .form-table {display: none;}
		</style>
		<div class="mcfgf-conversation-generator-form-box">
		<?php
		settings_fields( 'mcfgf_conversation_generator' );
		do_settings_sections( 'mcfgf_conversation_generator' );
		?>
		</div>
		<div class="main" role="main">
			
			
		  <div class="configurator clearfix">
		    <div class="controls">
		    <h3 class="bold mb15">Robot Side Styles</h3>
		    
		      <div class="clearfix">
		        <div class="text">
		        <h3>Avatar</h3>
		        <?php mcfgf_render_avatar_picker('Pick an avatar', 'avatar_robot'); ?>
		        
		        </div>
		        <div class="font-color">
		        	<h3>Background Color</h3>
		        <input type="text" data-bind="styles.backgroundColor" class="color" id="background-color">
		        	<div class="hidden">
		          <h3>Border Color</h3>
		          <input type="text" data-bind="styles.borderColor" class="color" id="border-color"></div>
		        </div>
		        <div class="font-color">
		          <h3>Font Color</h3>
		          <input type="text" data-bind="styles.fontColor" class="color">
		        </div>
		        <div class="font-color">
		          <h3>Border Color</h3>
		          <input type="text" data-bind="styles.borderColor" class="color">
		        </div>
		      </div>
		      <h3 class="bold mt20 mb15">User Side Styles</h3>

		      <div class=" clearfix">
		        <div class="text">
		        	<h3>Avatar</h3>
		        	<?php mcfgf_render_avatar_picker('Pick an avatar', 'avatar_user'); ?>
		        </div>
		        <div class="font-color">
		        	<h3>Background Color</h3>
		        	<input type="text" data-bind="styles.userBackgroundColor" class="color" id="user-background-color">
		          <div class="hidden">
		          <h3>Border Color</h3>
		          <input type="text" data-bind="styles.userBorderColor" class="color" id="user-border-color">
		          </div>
		        </div>
		        <div class="font-color">
		          <h3>Font Color</h3>
		          <input type="text" data-bind="styles.userFontColor" class="color">
		        </div>
		        <div class="font-color">
		          <h3>Border Color</h3>
		          <input type="text" data-bind="styles.userBorderColor" class="color">
		        </div>
		      </div>
		      <h3 class="bold mt20 mb15">Common Styles</h3>
		      
		<div class="clearfix">
		        <div class="text">
		          <h3>Container Background Color</h3>
		          <input type="text" data-bind="styles.containerBackgroundColor" class="color" id="container-background-color">
		        </div>
		        <div class="font-size">
		          <h3>Font Size</h3>
		          <input type="text" data-bind="styles.fontSize" class="wheel">
		        </div>
		        <div class="font-color">
		          <h3>Line Height</h3>
		          <input type="text" data-bind="styles.lineHeight" class="wheel">
		        </div>
		        <div class="font-color">
		          <h3>Border Width</h3>
		          <input type="text" data-bind="styles.borderWidth" class="wheel">
		        </div>
		      </div>
		      
		      <div class="clearfix second-row-ct hidden">
		        <input type="checkbox" class="second-row" data-bind="secondRow">
		        <div class="text">
		          <input type="text" data-bind="text.secondRow">
		        </div>
		        <div class="font-size">
		          <input type="text" data-bind="styles.secondRowFontSize" class="wheel">
		        </div>
		      </div>



		      <div class="border clearfix">
		        <h3>Border Radius</h3>
		        <div class="slider" id="border-slider"></div>
		        <input type="text" data-bind="styles.borderRadius" class="wheel" id="border">
		      </div>

		      <div class="padding clearfix">
		        <h3>Padding Multiply</h3>
		        <div class="slider" id="padding-slider"></div>
		        <input type="text" data-bind="styles.padding" id="padding">
		      </div>

		    </div>
		    
			</div>
		</div>
		<p class="submit">
		<?php submit_button(null, 'primary', 'submit', false); ?>
		<input type="button" value="Reset" class="button button-info" id="reset-css-cf7" name="reset"></p>
	</form>
	
	<?php
}

function mcfgf_conversation_generator_preview_box() { 
	$ajax_url = admin_url( 'admin-ajax.php' );
	global $mcfgf_settings_basics;
	$form_id = intval($mcfgf_settings_basics['conversation_gravity_form_id']);
	?>
	<div class="mcfgf-preview-container">
		<div class="preview"><div id="form-outer" class="panel panel-default">
        <div class="panel-body" style="padding: 0;height: 500px">
          <iframe src="<?php echo mcfgf_get_conversation_permalink($form_id, 'ver='.$ver); ?>" style="width: 100%; height: 100%;overflow:hidden;border: none;"></iframe>
        </div>
      </div></div>
	</div>
	<?php
	}

function mcfgf_conversation_generator_preview_box_old() { 
	?>
	<div class="mcfgf-preview-container">
		<div class="preview"><div id="form-outer" class="panel panel-default">
        <div class="panel-body">
          <form id="conversational" action="#" method="POST">
            <h2>Feedback form</h2>

            <input type="text" name="_gotcha" style="display: none">
            <input type="hidden" name="_subject" value="Submission on ConversationalForm" />

            <div class="form-group">
              <label for="your-name">Name</label>
              <input cf-questions="Hi there! What's your name?" type="name" class="form-control" name="your-name" id="your-name">
            </div>

            <div class="form-group">
              <label for="your-occupation">Occupation</label>
              <div class="radio">
                <label>
                  <input cf-questions="Great to meet you, {previous-answer}! I'm a web form, what do you do?|Awesome, {previous-answer}! And what do you do?" type="radio" name="your-occupation" id="your-occupation-1" value="developer">
                  Developer
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="your-occupation" id="your-occupation-2" value="designer">
                  Designer
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="your-occupation" id="your-occupation-3" value="curious-mind">
                  Curious mind
                </label>
              </div>
              <div class="radio">
                <label>
                  <input type="radio" name="your-occupation" id="your-occupation-2" value="lost">
                  Lost cause
                </label>
              </div>
            </div>
            <div class="form-group">
              <label for="your-company">Company</label>
              <input cf-questions="Which company are you with?" type="text" class="form-control" name="your-company" id="your-company">
            </div>

            <div class="form-group">
              <label for="your-opinion">Will conversational interfaces be everywhere?</label>
              <input cf-questions="Do you think conversational forms will replace web forms in the future?" type="text" class="form-control" name="your-opinion" id="your-opinion">
            </div>

            <button type="submit" class="btn btn-default">Submit</button>
          </form>
        </div>
      </div></div>
	</div>
	<?php
	}

endif;

?>
