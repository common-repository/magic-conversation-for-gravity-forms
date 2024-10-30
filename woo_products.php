<?php

/**
 * Magic Conversation For Gravity Forms WooCommerce Products query class
 *
 * @author Flannian Feng
 */
$http_origin = $_SERVER['HTTP_ORIGIN'];
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: $http_origin");
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: application/json; charset=utf-8');
    die();
}

header("Access-Control-Allow-Origin: $http_origin");
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Content-Type: application/json; charset=utf-8');


if(isset($_GET['q'])) {
	$query = new MCFGFPWooProductsQuery($_GET['q']);
    // echo json_encode($preview->debugInfo);
	echo json_encode($query->result);
	die();
}
else {
	echo 'ERROR';
}

class MCFGFPWooProductsQuery {
    var $keyword;
    var $result;
    var $debugInfo;

    function __construct($keyword) {
        $this->result = $this->query($keyword); //$this->getUrlData($keyword);
    }

    /**
	 * Query product by keywords
	 *
	 * @param   string		$keyword   the keyword for filter products
	 * @return	string
	 */
	function query( $keyword ) {
		$data_store                   = WC_Data_Store::load( 'product' );
        $product_ids                          = $data_store->search_products( wc_clean( wp_unslash( $query_vars['s'] ) ), '', true, true );

        $products = mcfgf_get_products_by_ids($product_ids);

        return array(
            // 'total' => intval($results->total),
            'products' => $products
        ) ;
	}
}