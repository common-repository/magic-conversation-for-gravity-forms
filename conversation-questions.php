<?php

/**
 * Magic Conversation For Gravity Forms ConversationLibrary class
 *
 * @author Flannian Feng
 */

if ( !class_exists('ConversationQuestions_MagicConversationForGravityForms' ) ):
class ConversationQuestions_MagicConversationForGravityForms {

    private $cpt;
    private $post_type = 'mc-question';

    private $taxonomy_params = array (
            'context'       => array (
                'term name'
            )
        ,   'replacements'  => array ( 
                'Name'          => 'Field Label'
            )
        ,   'taxonomy'      => 'mc-question-category'
    );

    function __construct() {
        
        $this->initCPT();
        // add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );

        add_filter('gettext', array($this, 'custom_enter_title'));

        // Run only in Edit Tags screens
        add_action( 'admin_head-edit-tags.php', array($this, 'wpse_register_filter') );
    }

    function custom_enter_title( $input ) {

        global $post_type;

        if( is_admin() && 'Enter title here' == $input && $this->post_type == $post_type )
            return 'Enter Question for Form Field here.';

        return $input;
    }

    function wpse_register_filter() 
    {
        add_filter( 'gettext_with_context', array($this, 'wpse_translate'), 10, 4 );
    }

    function wpse_translate( $translated, $original, $context, $domain ) 
    {
        // print_r(array( $translated, $original, $context, $domain ));
        // If not our taxonomy, exit early
        if( $this->taxonomy_params['taxonomy'] !== $_GET['taxonomy'] )
            return $translated;

        // Text is not from WordPress, exit early
        if ( 'default' !== $domain ) 
            return $translated;

        // Check desired contexts
        if( !in_array( $context, $this->taxonomy_params['context'] ) )
            return $translated;

        // Finally replace
        return strtr( $original, $this->taxonomy_params['replacements'] );
    }

    function initCPT() {
        $this->cpt = new CPT(array(
            'post_type_name' => 'mc-question',
            'singular' => 'Question',
            'plural' => 'Questions',
            'slug' => 'mc-question'
        ), array(
            'show_in_menu'        => false,   
            'show_in_nav_menus'   => false,
            'supports'      =>  array('title'),    
        ));

        $this->cpt->columns(array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Questions'),
            'mc-question-category' => __('Form Field Label')
        ));
        $this->cpt->register_taxonomy(array(
            'taxonomy_name' => 'mc-question-category',
            'singular' => 'Form Field Label',
            'plural' => 'Form Field Labels',
            'slug' => 'mc-question-category',
            'show_in_menu'        => false,   
            'show_in_nav_menus'   => false
        ), array(
            'show_in_menu'        => false,   
            'show_in_nav_menus'   => false,
            'replacements'  => array ( 
                'Description'   => 'Title'
                ,'Name'          => 'Field Label'
            )
        ));

        // make columns sortable
        $this->cpt->sortable(array(
            'begin_date' => array('begin_date', true),
            'end_date' => array('end_date', true)
        ));

        // $this->cpt->filters(array('mc-question-category'));

        // $this->cpt->set_textdomain('mc-question');
    }

// edit.php?post_type=mc-question
    function admin_menu() {
        add_submenu_page( 
            'magic_conversation_for_gravity_forms' 
            , 'Questions' 
            , 'Questions'
            , 'manage_options'
            , 'edit.php?post_type=mc-question'
            , ''
        );

        add_submenu_page( 
              'magic_conversation_for_gravity_forms' 
            , 'New Question' 
            , 'New Question'
            , 'manage_options'
            , 'post-new.php?post_type=mc-question'
            , ''
        );

        add_submenu_page( 
              'magic_conversation_for_gravity_forms' 
            , 'Form Fields' 
            , 'Form Fields'
            , 'manage_options'
            , 'edit-tags.php?taxonomy=mc-question-category&post_type=mc-question'
            , ''
        );
    }
}
$questions = new ConversationQuestions_MagicConversationForGravityForms();
endif;
