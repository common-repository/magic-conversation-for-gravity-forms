<?php
define( 'GF_YAKKER_ADDON_VERSION', '2.1.32' );

add_action( 'gform_loaded', array( 'GF_Yakker_AddOn_Bootstrap', 'load' ), 5 );

class GF_Yakker_AddOn_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'class-gfyakkeraddon.php' );

        GFAddOn::register( 'GFYakkerAddOn' );
    }

}

function gf_yakker_addon() {
    return GFYakkerAddOn::get_instance();
}