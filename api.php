<?php

/**
 * Magic Conversation For Gravity Forms API class
 *
 * @author Flannian Feng
 */

if ( !class_exists('API_MagicConversationForGravityForms' ) ):
class API_MagicConversationForGravityForms {

    private $cpt;
    private $post_type = 'mc-question';

    function __construct() {
        add_action( 'admin_init', array($this, 'getAllQuestions') );
    }

    public function getQuestionsForFieldLabel($label) {
        global $post;
        $questions = array();
        // $name = strtolower($label);
        $name = $label;
        $loop = new WP_Query(array(
            'post_type' => $this->post_type,
            'tax_query' => array(
                array(
                    'taxonomy' => $this->post_type.'-category',
                    'field' => 'name',
                    'terms' => array( $name ),
                    'operator' => 'IN'
                )
            ),
            'posts_per_page' => -1
        ));

        while ( $loop->have_posts() ) : $loop->the_post(); 
            $questions[] = $post->post_title;
        endwhile; 
        $loop = null;
        wp_reset_postdata();

        return count($questions) > 0 ? '<ul><li>'.implode("</li><li>", $questions).'</li></ul>' : '';
    }

    public function getAllQuestions() {
        $positions = get_terms($this->post_type.'-category');
        // echo count($positions);
        global $post;
        // var_dump($positions);
        $questions = array();
        // for ( $myterm = 0; $myterm < count($positions); $myterm++) {
        foreach ($positions as $key => $position) {
            if(!isset($position->name)) continue;
            $name = strtolower($position->name);
            $slug = $position->slug;

            $questions[$name] = array();
            $loop = new WP_Query(array(
                'post_type' => $this->post_type,
                'tax_query' => array(
                    array(
                        'taxonomy' => $this->post_type.'-category',
                        'field' => 'slug',
                        'terms' => array( $slug ),
                        'operator' => 'IN'
                    )
                ),
                'posts_per_page' => -1
            ));

            while ( $loop->have_posts() ) : $loop->the_post(); 
                $questions[$name][] = $post->post_title;
            endwhile; 
            $loop = null;
            wp_reset_postdata();
        }

        return $questions;
    }
    
}
endif;
