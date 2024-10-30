<?php

/**
 * Magic Conversation For Gravity Forms Url Preview class
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
// $input =  file_get_contents("php://input");
// $json = json_decode($input, true);

// if(isset($json['url'])) {
// 	$preview = new MCFGFPUrlPreview($json['url']);
// 	echo json_encode($preview->result);
// 	die();
// }
// else {
// 	echo 'what';
// }

// $input =  file_get_contents("php://input");
// $json = json_decode($input, true);

if(isset($_GET['q'])) {
	$preview = new MCFGFPUrlPreview($_GET['q']);
    // echo json_encode($preview->debugInfo);
	echo json_encode($preview->result);
	die();
}
else {
	echo 'ERROR';
}

class MCFGFPUrlPreview {

    // var $description;
    // var $title;
    // var $image = array();
    var $url;
    // var $html;
    // var $parsemode;
    // var $curlerrno;
    // var $curlerr;
    // var $curlinf = array();
    // var $htmlblank;
    var $result;
    var $debugInfo;

    function __construct($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url . '/';
        }
        $this->result = array(); //$this->getUrlData($url);
        $this->url = $url;

        require_once dirname(__FILE__).'/lib/FaviconDownloader.class.php';

        $ext = FaviconDownloader::getExtension($url);

        $key = array_search($ext, array("", "html", "shtml", "html", "do"));
        if($key!==false) {
        	$video_info = $this->get_video_info( $url );
        	if($video_info !== null) {
	        	$this->result = $video_info;
	        }
	        else {
	        	$favicon = new FaviconDownloader($url, false);
		        $favicon->getFaviconUrl();
		        $this->result['favicon'] = $favicon->icoUrl;

		        $this->debugInfo = $favicon->debugInfo;
		        $this->pageUrl = $favicon->pageUrl;
		        $this->html = $favicon->html; ///$this->getUrlContents($url);
		        

		        $this->result['title'] = $this->getTitle();
		        $this->result['description'] = $this->getDescription();
		        $this->result['thumbnail_url'] = $this->getImage();
		        
		        $this->result['pageUrl'] = $this->pageUrl;
		        // $this->result['html'] = $this->html;
	        }
        }
        
    }

    /**
	 * Get the URL of a thumbnail for a video on a 3rd-party service
	 *
	 * @param       string		$url	Currently supports YouTube and Vimeo
	 * @return	string
	 */
	function get_video_info( $url ) {
		$thumb_url = null;
		$service = null;
		$url_parts = parse_url( $url );
		// Which service?
		if ( strpos( $url_parts['host'], 'youtube' ) !== false ) {
			$service = 'youtube';
		} else if ( strpos( $url_parts['host'], 'youtu.be' ) !== false ) {
			$service = 'youtube';
		} else if ( strpos( $url_parts['host'], 'vimeo' ) !== false ) {
			$service = 'vimeo';
		}
		switch ( $service ) {
			case 'youtube': {
				$qs_parts = explode( '&', $url_parts['query'] );
				foreach ( $qs_parts as $qs_part ) {
					$qs_part = explode( '=', $qs_part );
					if ( $qs_part[0] = 'v' ) {
						$api_url = "https://www.youtube.com/oembed?url=".urlencode($url)."&format=json";
						$json_str = FaviconDownloader::downloadAs($api_url);
						$json = json_decode($json_str, true);
						$json['favicon'] = 'https://s.ytimg.com/yts/img/favicon-vfl8qSV2F.ico';
						return $json;
						// $thumb_url = 'http://img.youtube.com/vi/' . $qs_part[1] . '/default.jpg';
					}
				}
				break;
			}
			case 'vimeo': {
				//https://vimeo.com/187844287/bbf8361f2e
				$api_url = "https://vimeo.com/api/oembed.json?url=".urlencode($url);
				$json_str = FaviconDownloader::downloadAs($api_url);
				$json = json_decode($json_str, true);
				$json['favicon'] = 'https://f.vimeocdn.com/images_v6/favicon.ico?DEV';
				return $json;
				// $image = unserialize( file_get_contents( 'http://vimeo.com/api/v2/video/' . $url_parts['query'] . '.php' ) );
				// $thumb_url = $image[0]['thumbnail_small'];
				break;
			}
		}
		return $thumb_url;
	}

   	function getUrlContents($url, $maximumRedirections = null, $currentRedirection = 0)
    {
    	// echo $url;
        $result = false;
        $opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
        $context = stream_context_create($opts);
        $contents = @file_get_contents($url,false,$context);
        // $contents = @file_get_contents($url);
        // Check if we need to go somewhere else
        if (isset($contents) && is_string($contents))
        {
            preg_match_all('/<[\s]*meta[\s]*http-equiv="?REFRESH"?' . '[\s]*content="?[0-9]*;[\s]*URL[\s]*=[\s]*([^>"]*)"?' . '[\s]*[\/]?[\s]*>/si', $contents, $match);
            if (isset($match) && is_array($match) && count($match) == 2 && count($match[1]) == 1)
            {
                if (!isset($maximumRedirections) || $currentRedirection < $maximumRedirections)
                {
                    return $this->getUrlContents($match[1][0], $maximumRedirections, ++$currentRedirection);
                }
                $result = false;
            }
            else
            {
                $result = $contents;
            }
        }
        // $this->html = $contents;
        // var_dump($contents);
        return $contents;
    }

    function getUrlData($url)
    {
        $result = false;
        $contents = $this->getUrlContents($url);
        if (isset($contents) && is_string($contents))
        {
            $title = null;
            $metaTags = null;
            preg_match('/<title>([^>]*)<\/title>/si', $contents, $match );
            if (isset($match) && is_array($match) && count($match) > 0)
            {
                $title = strip_tags($match[1]);
            }
            $metaTags = array();
            preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $contents, $match);
            if (isset($match) && is_array($match) && count($match) == 3)
            {
                $originals = $match[0];
                $names = $match[1];
                $values = $match[2];
                if (count($originals) == count($names) && count($names) == count($values))
                {
                    for ($i=0, $limiti=count($names); $i < $limiti; $i++)
                    {
                        $metaTags[$names[$i]] = array (
                            'html' => htmlentities($originals[$i]),
                            'value' => $values[$i]
                        );
                    }
                }
            }
            preg_match_all('/<[\s]*meta[\s]*property="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $contents, $match);
            if (isset($match) && is_array($match) && count($match) == 3)
            {
                $originals = $match[0];
                $names = $match[1];
                $values = $match[2];
                if (count($originals) == count($names) && count($names) == count($values))
                {
                    // if(!isset($i)) $i=0;
                    for ($j=0, $limiti=count($names); $j < $limiti; $j++)
                    {
                        $metaTags[$names[$j]] = array (
                            'html' => htmlentities($originals[$j]),
                            'value' => $values[$j]
                        );
                    }
                }
            }
            $result = array (
                'title' => $title,
                'metaTags' => $metaTags
            );
        }

        return $result;
    }

    function getDescription() {
        if (preg_match_all('/<meta(?=[^>]*name="description")\s[^>]*content="([^>]*)"/si', $this->html, $matches)) {
            foreach ($matches[1] as $key => $content) {
                return $content;
            }
        } else if (preg_match_all('/<meta(?=[^>]*name="og:description")\s[^>]*content="([^>]*)"/si', $this->html, $matches)) {
            foreach ($matches[1] as $key => $content) {
                return $content;
            }
        }
    }

    function getTitle() {
        // if (preg_match("/<title>(.+)<\/title>/si", $this->html, $matches)) { // Changed due to issue with BBC news
        if (preg_match("/<h1[\s\S]+?>([^<]+)<\/h1>/i", $this->html, $matches)) {
            return trim($matches[1]);
        } else if (preg_match("/<title[\s\S]*?>([\s\S]+?)<\/title>/i", $this->html, $matches)) {
            return $matches[1];
        } else {
            return '';
        }
    }

    function getFullUrl($urlType, $ico_href) {
    	$pageUrlInfo = parse_url($this->pageUrl);
    	$base_href = $this->debugInfo['base_href'];
    	switch($urlType){
			case 'absolue_full':
				$icoUrl = $ico_href;
				break;
			case 'absolute_scheme':
				$icoUrl = $pageUrlInfo['scheme'].':'.$ico_href;
				break;
			case 'absolute_path':
				if(isset($base_href)){
					$icoUrl = $base_href.$ico_href;
					$this->findMethod .= ' with base href';
				} else {
					$icoUrl = rtrim($this->siteUrl, '/').'/'.ltrim($ico_href, '/');
					$this->findMethod .= ' without base href';
				}
				break;
			case 'relative':
				$path = preg_replace('#/[^/]+?$#i', '/', $pageUrlInfo['path']);
				if(isset($base_href)){
					$icoUrl = $base_href.$ico_href;
					$this->findMethod .= ' with base href';
				} else {
					$icoUrl = $pageUrlInfo['scheme'].'://'.$pageUrlInfo['host'].$path.$ico_href;
					$this->findMethod .= ' without base href';
				}
				break;
		}
		return $icoUrl;
    }

    function getImage() {
    	if (preg_match_all('/<h1[\s\S]+?<img [^>]*src=["|\']([^"|\']+)/si', $this->html, $matches)) {
            foreach ($matches[1] as $key => $value) {
            	$urlType = FaviconDownloader::urlType($value);
            	return $this->getFullUrl($urlType, $value);
                // if (strpos($value, 'http') === false) {
                //     // If trailing slash is missing from domain AND image path does not start with slash, insert one - technically should check for base href, but later :-)
                //     if ((substr($this->url, -1) != "/") && (substr($value, 0, 1) != "/")) {
                //         $image[] = $this->url . '/' . preg_replace("/&#?[a-z0-9]{2,8};/i", "", $value);
                //     } else {
                //         $image[] = $this->url . preg_replace("/&#?[a-z0-9]{2,8};/i", "", $value);
                //     }
                // } else {
                //     $image[] = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $value);
                // }

                if ($key == 5)
                    break;
            }
        }
        /* First we will check if facebook opengraph image tag exist */
        else if (preg_match_all('/<meta(?=[^>]*property="og:image")\s[^>]*content="([^>]*)"/si', $this->html, $matches)) {
            foreach ($matches[1] as $key => $content) {
                $image[] = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $content);
                if ($key == 5)
                    break;
            }
        }

        /* If not then we will get the first image from the html source */
        else if (preg_match_all('/<img [^>]*src=["|\']([^"|\']+)/i', $this->html, $matches)) {
            foreach ($matches[1] as $key => $value) {
                if (strpos($value, 'http') === false) {
                    // If trailing slash is missing from domain AND image path does not start with slash, insert one - technically should check for base href, but later :-)
                    if ((substr($this->url, -1) != "/") && (substr($value, 0, 1) != "/")) {
                        $image[] = $this->url . '/' . preg_replace("/&#?[a-z0-9]{2,8};/i", "", $value);
                    } else {
                        $image[] = $this->url . preg_replace("/&#?[a-z0-9]{2,8};/i", "", $value);
                    }
                } else {
                    $image[] = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $value);
                }

                if ($key == 5)
                    break;
            }
        }
        $image_index = (isset($_GET['image_no'])) ? $_GET['image_no'] - 1 : 0;
        return $image[$image_index];
    }

}