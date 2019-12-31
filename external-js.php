<?php
/**
 * Plugin Name: Cache for External Scripts
 * Description: This plugin allows you to cache the Google Analytics JavaScript file to be cached for more than 2 hours, for a better PageSpeed score
 * Version: 0.4

 */
 
//Define uploads directory to store cached scripts
$wp_upload_dir = wp_upload_dir();
define('UPLOAD_BASE_DIR',$wp_upload_dir['basedir']);
define('UPLOAD_BASE_URL',$wp_upload_dir['baseurl']);

// create a scheduled event (if it does not exist already)
function ces_cronstarter_activation() {
	if( !wp_next_scheduled( 'cache-external-scripts-cron' ) ) {  
	   wp_schedule_event( time(), 'daily', 'cache-external-scripts-cron' );  
	}
}
// and make sure it's called whenever WordPress loads
add_action('wp', 'ces_cronstarter_activation');


// unschedule event upon plugin deactivation
function ces_cronstarter_deactivate() {	
	// find out when the last event was scheduled
	$timestamp = wp_next_scheduled ('cache-external-scripts-cron');
	// unschedule previous event if any
	wp_unschedule_event ($timestamp, 'cache-external-scripts-cron');
} 
register_deactivation_hook (__FILE__, 'ces_cronstarter_deactivate');

function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

// here's the function we'd like to call with our cron job
function function_cache_external_scripts() {
	
	$dir = UPLOAD_BASE_DIR.'/cached-scripts';
	if (!file_exists($dir) && !is_dir($dir)) {
		mkdir($dir);         
	}
	
	$analytics_data = get_data('http://www.google-analytics.com/analytics.js');
	if($analytics_data AND (!file_exists(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js') OR $analytics_data!=file_get_contents(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js'))){
		$fp = fopen(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js',"wb");
		fwrite($fp,$analytics_data);
		fclose($fp);
	}
	
	$ga_data = get_data('http://www.google-analytics.com/ga.js');
	if($ga_data AND (!file_exists(UPLOAD_BASE_DIR.'/cached-scripts/ga.js') OR $ga_data!=file_get_contents(UPLOAD_BASE_DIR.'/cached-scripts/ga.js'))){
		$fp = fopen(UPLOAD_BASE_DIR.'/cached-scripts/ga.js',"wb");
		fwrite($fp,$ga_data);
		fclose($fp);
	}
	
	$ga_data = get_data('http://www.googletagmanager.com/gtag/js');
	if($ga_data AND (!file_exists(UPLOAD_BASE_DIR.'/cached-scripts/gtag.js') OR $ga_data!=file_get_contents(UPLOAD_BASE_DIR.'/cached-scripts/gtag.js'))){
		$fp = fopen(UPLOAD_BASE_DIR.'/cached-scripts/gtag.js',"wb");
		
		//add extra function to Google gtag.js to retreive the UA-id dynamically
		$ga_data = str_replace('kc.o=""', 'var url=document.getElementById("cached-script").src.toLowerCase();kc.o=/id=([^&]+)/.exec(url)[1];',$ga_data);
		
		//replace analytics.js with our cached version
		if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js')){
			$ga_data = preg_replace('#(http:|https:|)//www.google-analytics.com/analytics.js#',UPLOAD_BASE_URL.'/cached-scripts/analytics.js',$ga_data);
		}
		fwrite($fp,$ga_data);
		fclose($fp);
	}
	
	
	$gtm_data = get_data('https://www.googletagmanager.com/gtm.js');
	if($gtm_data AND (!file_exists(UPLOAD_BASE_DIR.'/cached-scripts/gtm.js') OR $gtm_data!=file_get_contents(UPLOAD_BASE_DIR.'/cached-scripts/gtm.js'))){
		$fp = fopen(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js',"wb");
		
		//add extra function to Google gtag.js to retreive the UA-id dynamically
		$gtm_data = str_replace('kc.o=""', 'var url=document.getElementById("cached-script").src.toLowerCase();kc.o=/id=([^&]+)/.exec(url)[1];',$gtm_data);
		
		//replace analytics.js with our cached version
		if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js')){
			$gtm_data = preg_replace('#(http:|https:|)//www.google-analytics.com/analytics.js#',UPLOAD_BASE_URL.'/cached-scripts/analytics.js',$gtm_data);
		}
		fwrite($fp,$gtm_data);
		fclose($fp);
	}
	
		$fb_events = get_data('https://connect.facebook.net/en_US/fbevents.js');
	if($fb_events AND (!file_exists(UPLOAD_BASE_DIR.'/cached-scripts/fbevents.js') OR $fb_events!=file_get_contents(UPLOAD_BASE_DIR.'/cached-scripts/fbevents.js'))){
		$fp = fopen(UPLOAD_BASE_DIR.'/cached-scripts/fbevents.js',"wb");
		fwrite($fp,$fb_events);
		fclose($fp);
	}
}
// hook that function onto our scheduled event:
add_action ('cache-external-scripts-cron', 'function_cache_external_scripts'); 


add_action('get_header', 'ces_ob_start');
add_action('wp_footer', 'ces_ob_end_flush', 99999);
function ces_ob_start() {
    ob_start('ces_filter_wp_head_output');
}
function ces_ob_end_flush() {
    ob_end_flush();
}
function ces_filter_wp_head_output($output) {
	if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js')){
		$output = preg_replace('#(http:|https:|)//www.google-analytics.com/analytics.js#',UPLOAD_BASE_URL.'/cached-scripts/analytics.js',$output);
	}
	if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/ga.js')){
		$output = str_replace("ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';","ga.src = '".UPLOAD_BASE_URL."/cached-scripts/ga.js';",$output);
	}
	if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/gtag.js')){
		$output = str_replace("src=\"https://www.googletagmanager.com/gtag/js","id=\"cached-script\" src=\"".UPLOAD_BASE_URL."/cached-scripts/gtag.js",$output);
	}
	if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/gtm.js')){
		$output = str_replace("src=\"https://www.googletagmanager.com/gtm.js","id=\"cached-script\" src=\"".UPLOAD_BASE_URL."/cached-scripts/gtm.js",$output);
	}
	if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/fbevents.js')){
		$output = str_replace("src=\"https://connect.facebook.net/en_US/fbevents.js",UPLOAD_BASE_URL.'/cached-scripts/fbevents.js',$output);
	}
    return $output;
}

add_action( 'admin_menu', 'ces_add_admin_menu' );
add_action( 'admin_init', 'ces_settings_init' );

function ces_add_admin_menu(  ) { 
	add_options_page( 'Cache External Scripts', 'Cache External Scripts', 'manage_options', 'cache-external-scripts', 'ces_options_page' );
}


function ces_settings_init(  ) { 
	register_setting( 'pluginPage', 'ces_settings', 'validate_input' );
}

function ces_options_page(  ) { 
	?>
		<h1>Cache External Sources</h1>
	<?php
	if($_GET['action']=='cache-scripts'){
		echo 'Fetching scripts...</p>';
		function_cache_external_scripts();
	}
	if(file_exists(UPLOAD_BASE_DIR.'/cached-scripts/analytics.js') AND file_exists(UPLOAD_BASE_DIR.'/cached-scripts/ga.js')){
		echo '<p>Google Analytics file (analytics.js) succesfully cached on local server!</p><p>In case you want to force the cache to be renewed, click <a href="'.get_site_url().'/wp-admin/options-general.php?page=cache-external-scripts&action=cache-scripts">this link</a>
		
		<div style="margin-top:40px;background-color:#fff;padding:10px;border:1px solid #C42429;display:inline-block;">Did this plugin help you to leverage browser caching and increase your PageSpeed Score? <a href="https://wordpress.org/support/view/plugin-reviews/cache-external-scripts" target="_blank">Please rate the plugin</a>!<br />Did not work for your site? <a href="https://wordpress.org/support/plugin/cache-external-scripts" target="_blank">Please let us know</a>!</div>';
	}else{
		echo '<p>Google Analytics file (analytics.js) is not cached yet on the local server. Please refresh <a href="'.get_site_url().'" target="_blank">your frontpage</a> to start the cron or start it manually by pressing <a href="'.get_site_url().'/wp-admin/options-general.php?page=cache-external-scripts&action=cache-scripts">this link</a>.</p>';
	}
}
