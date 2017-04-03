<?php
/*
Plugin Name: LH HSTS
Plugin URI: https://lhero.org/plugins/lh-hsts/
Description: Adds HTTP Strict Transport Security to wordpress, options can be changed by filters
Author: Peter Shaw
Version: 1.11
Author URI: https://shawfactor.com/
*/


if (!class_exists('LH_hsts_plugin')) {

class  LH_hsts_plugin{




public function add_header(){


	$uri=$_SERVER['REQUEST_URI'];
		$domain=$_SERVER['HTTP_HOST'];
		$wpDomain=get_site_url();
		if($wpDomain=="http://".$domain || $wpDomain=="https://".$domain){
			//echo "You are at the correct domain";


if (isset($_SERVER['HTTPS'])){


//default max-age in seconds (equivalent to 185 days to allow pre-loading)
$maxage = 15984000;
$maxage = apply_filters( 'lh_hsts_maxage_filter', $maxage );


$string = "max-age=".$maxage.";";


//default includeSubDomains is true
$includesubdomains = true;
$includesubdomains = apply_filters( 'lh_hsts_includesubdomains_filter', $includesubdomains );

if ($includesubdomains){

$string .= " includeSubDomains;";

}


//default preload is true
$preload = true;
$preload = apply_filters( 'lh_hsts_preload_filter', $preload );

if ($preload){

$string .= " preload";

}

header("Strict-Transport-Security: ".$string);

} else {

//default redirect is true
$redirect = true;
$redirect = apply_filters( 'lh_hsts_redirect_filter', $redirect );

if ($redirect){
		
header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);

}


}

} else {
			Header( "HTTP/1.1 301 Moved Permanently" );
			Header( "Location: ".$wpDomain.$uri );
			//echo $domain." isn't the right domain: ".$wpDomain;
			die();
			
		}

}

function __construct() {

add_action( 'send_headers', array($this,"add_header"));

}

}


$lh_hsts_plugin_instance = new LH_hsts_plugin();

}

?>