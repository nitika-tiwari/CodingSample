<?php
/*
Plugin Name: WP SlimStat - Custom Reports
Description: User statistics custom report - Page wise report and Institution wise report
Version: 1.0
*/
?>
<?php 
//define( 'CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); 
        wp_enqueue_style('admin-style', 'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css'); 
		wp_enqueue_style('datatable-css', 'https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css'); 
		 wp_enqueue_script('admin-script', 'https://code.jquery.com/jquery-3.3.1.js'); 
		 wp_enqueue_script('admin-datatable', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js'); 
		 wp_enqueue_script('jqueryui', 'https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js'); 
		 wp_enqueue_script('jqueryui-zip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'); 
		 wp_enqueue_script('jqueryui-pdf', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'); 
		wp_enqueue_script('jqueryui-datatable', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js'); 
		wp_enqueue_script('jqueryui-export', 'https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js'); 
	  




function custom_reports() {
add_menu_page('User Reports', 'User Reports', 'manage_options', 'user-reports', 'reports_function');
add_submenu_page( 'user-reports', 'Pagewise Report', 'Pagewise Report', 'manage_options', 'page-reports', 'pagereport_function');
    
}
add_action('admin_menu', 'custom_reports');
function reports_function() {

require_once( plugin_dir_path( __FILE__ ).'user-reports.php' );
    
}


function pagereport_function() {

require_once( plugin_dir_path( __FILE__ ).'page-reports.php' );
    
}
// end of class declaration
?>
