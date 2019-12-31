<?php

/**
 * Plugin Name: Dream Machine Alcohol Calculator
 * Description: This is for form dependent data.
 * Version: 1.0
 * Author: VCO

 * */
// factor to calculate unites
define("FACTOR_ALCOOL", 0.789);

// function to create the DB / Options / Defaults					
function plugin_install() {
    global $wpdb;
    $consommations = $wpdb->prefix . 'consumpties';
    $consumptie_type = $wpdb->prefix . 'consumptie_type';
    $consumptie_partner = $wpdb->prefix . 'consumptie_partner';
    $consumptie_place = $wpdb->prefix . 'consumptie_place';
    $consumptie_reason = $wpdb->prefix . 'consumptie_reason';
    $consumptie_glasses = $wpdb->prefix . 'consumptie_glasses';
    $consumptie_drinks = $wpdb->prefix . 'consumptie_drinks';


// create the ECPT metabox database table
    if ($wpdb->get_var("show tables like '$consommations'") != $consommations) {
        $sql = "CREATE TABLE " . $consommations . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
                `user_id` int(10) NOT NULL,
		`quantity` int(10) NOT NULL,
		`consumptie_type` int(10) NOT NULL,
		`consumptie_with` int(10) NOT NULL,
		`consumptie_where` int(10) NOT NULL,
                `consumptie_why` int(10) NOT NULL,
		`consumptie_hour` time NOT NULL,
		`consumptie_date` date NOT NULL,
		UNIQUE KEY id (id)
		);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

// create the ECPT metabox database table
    if ($wpdb->get_var("show tables like '$consumptie_type'") != $consumptie_type) {
        $sql = "CREATE TABLE " . $consumptie_type . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`user_id` int(10) NOT NULL,
		`type_category` tinytext NOT NULL,
		`type_size` tinytext NOT NULL,
		`type_favourite` tinytext NOT NULL,
		`type_name` tinytext NOT NULL,		
		UNIQUE KEY id (id)
		);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

// create the ECPT metabox database table
    if ($wpdb->get_var("show tables like '$consumptie_partner'") != $consumptie_partner) {
        $sql = "CREATE TABLE " . $consumptie_partner . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`user_id` int(10) NOT NULL,
		`partner_name` tinytext NOT NULL,	
                `partner_favourite` tinytext NOT NULL,
		UNIQUE KEY id (id)
		);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

// create the ECPT metabox database table
    if ($wpdb->get_var("show tables like '$consumptie_place'") != $consumptie_place) {
        $sql = "CREATE TABLE " . $consumptie_place . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`user_id` int(10) NOT NULL,
		`place_name` tinytext NOT NULL,	
                `place_favourite` tinytext NOT NULL,
		UNIQUE KEY id (id)
		);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

// create the ECPT metabox database table    
    if ($wpdb->get_var("show tables like '$consumptie_reason'") != $consumptie_reason) {
        $sql = array();
        $sql[] = "CREATE TABLE " . $consumptie_reason . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`user_id` int(10) NOT NULL,
		`reason_name` tinytext NOT NULL,	
                `reason_favourite` tinytext NOT NULL,
		UNIQUE KEY id (id)
		);";
        $sql[] = "INSERT INTO " . $consumptie_reason . " (`id`, `user_id`, `reason_name`, `reason_favourite`) VALUES
                    (1, 0, 'Pour se relaxer', ''),
                    (3, 0, 'Par habitude', ''),
                    (4, 0, 'Comme médicament', ''),
                    (5, 0, 'Pour se sentir mieux', ''),
                    (8, 0, 'Pour faire la fête', ''),
                    (9, 0, 'Par plaisir', ''),
                    (10, 0, 'Parce que je n\'ose pas dire non', ''),
                    (18, 0, 'Pour être plus sociable', ''),
                    (19, 0, 'Autres', '');";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

// create the ECPT metabox database table
    if ($wpdb->get_var("show tables like '$consumptie_glasses'") != $consumptie_glasses) {
        $sql = array();
        $sql[] = "CREATE TABLE " . $consumptie_glasses . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
                `user_id` int(10) NOT NULL,
		`glasses_name` tinytext NOT NULL,		
                `glasses_size` tinytext NOT NULL,		
		UNIQUE KEY id (id)
		);";
        
        $sql[] = "INSERT INTO `wp_consumptie_glasses` (`id`, `glasses_name`, `glasses_size`, `user_id`) VALUES
                (1, 'Coupe à Champagne 150 ml', '150', 0),
                (2, 'Porto petit verre 50 ml', '50', 0),
                (3, 'Mini verre de boisson forte 35 ml', '35', 0),
                (4, 'Verre à cocktail 200 ml', '200', 0),
                (5, 'Petit verre à vin 100 ml', '100', 0),
                (6, 'Verre à vin moyen 150 ml', '150', 0),
                (7, 'Verre à pils 250 ml', '250', 0),
                (8, 'Bidon de 330 ml', '330', 0),
                (9, 'Verre à bière spécial 330 ml', '330', 0),
                (10, '1/4 bouteille de vin 187 ml', '187', 0),
                (11, '1/3 bouteille de vin 250 ml', '250', 0),
                (12, '1/2 bouteille de vin 375 ml', '375', 0),
                (13, 'Bouteille de vin 750 ml', '750', 0);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

// create the ECPT metabox database table
    if ($wpdb->get_var("show tables like '$consumptie_drinks'") != $consumptie_drinks) {
        $sql = array();
        $sql[] = "CREATE TABLE " . $consumptie_drinks . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
                `user_id` int(10) NOT NULL,
		`drinks_name` tinytext NOT NULL,		
                `drinks_degree` tinytext NOT NULL,		
		UNIQUE KEY id (id)
		);";
        
        $sql[] = "INSERT INTO `wp_consumptie_drinks` (`id`, `drinks_name`, `drinks_degree`, `user_id`) VALUES
                (1, 'Bière extra-légère (Radler, par exemple) - 2%', '2', 0),
                (2, 'Pils ou cidre - 5%', '5', 0),
                (3, 'Bière légère spéciale - 6 ou 7%', '6.5', 0),
                (4, 'Bière spéciale moyenne - 8 ou 9%', '8.5', 0),
                (5, 'Bière de spécialité lourde - 10 ou 11%', '10.5', 0),
                (6, 'Vin léger - 12%', '12', 0),
                (7, 'Vin rouge - 14%', '14', 0),
                (8, 'Vermouth - 15%', '15', 0),
                (9, 'Porto, Sherry - 20%', '20', 0),
                (10, 'Boisson forte - 35%', '35', 0),
                (11, 'Boisson forte - 40%', '40', 0);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'plugin_install');

//-------------Css and js-------------------------//


function themeslug_enqueue_style() {
    wp_enqueue_style('core', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css', false);
    wp_enqueue_style('custom-css', plugin_dir_url(__FILE__) . '/css/dreamform.css', false);
}

function themeslug_enqueue_script() {
    wp_enqueue_script('my-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js', false);
    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js', false);
}

add_action('wp_enqueue_scripts', 'themeslug_enqueue_style');
add_action('wp_enqueue_scripts', 'themeslug_enqueue_script');

//----------------Admin menu page------------------------//

	function plugin_admin() {
	    	add_menu_page( 'Calculator Options', 'Alchohal Calculator', 'manage_options', 'admin-shortcode', 'admin_shortcode_options' );
	}
	add_action( 'admin_menu', 'plugin_admin' );

	
	function admin_shortcode_options() {
	
	    require_once( 'admin-shortcode.php' );
	  
	}


//------------------------form shortcode-------------//

function my_ads_shortcode($attr) {
    ob_start();
    require_once( 'consommations-form.php' );
    return ob_get_clean();
}

add_shortcode('consumptie_form', 'my_ads_shortcode');

//------------------------History shortcode-------------//

function history_shortcode($attr) {
    ob_start();
    require_once( 'history.php' );
    return ob_get_clean();
}

add_shortcode('consumptie_history', 'history_shortcode');

//------------------------Journal shortcode-------------//

function journal_shortcode($attr) {
    ob_start();
    require_once( 'journal.php' );
    return ob_get_clean();
}

add_shortcode('consumptie_journal', 'journal_shortcode');

//------------------------Parameters shortcode-------------//

function parameters_shortcode($attr) {
    ob_start();
    require_once( 'parameters.php' );
    return ob_get_clean();
}

add_shortcode('consumptie_parameters', 'parameters_shortcode');


//------------- Insert new add consommation--------//
add_action("wp_ajax_add_consommation", "add_consommation");

function add_consommation() {
    global $wpdb;
    $user_id = get_current_user_id();
    $quantity = $_REQUEST['quantity'];
    $edit_id = $_REQUEST['edit_id'];
    $consumptie_type = $_REQUEST['consumptie_type'];
    $consumptie_with = $_REQUEST['consumptie_with'];
    $consumptie_where = $_REQUEST['consumptie_where'];
    $consumptie_why = $_REQUEST['consumptie_why'];
    $consumptie_hour = $_REQUEST['consumptie_hour'];
    $consumptie_date = $_REQUEST['consumptie_date'];

    $table_name = $wpdb->prefix . "consumpties";

    if (!empty($edit_id)) {
        $wpdb->update($table_name, array(
            'user_id' => esc_sql($user_id),
            'quantity' => esc_sql($quantity),
            'consumptie_type' => esc_sql($consumptie_type),
            'consumptie_with' => esc_sql($consumptie_with),
            'consumptie_where' => esc_sql($consumptie_where),
            'consumptie_why' => esc_sql($consumptie_why),
            'consumptie_hour' => esc_sql($consumptie_hour),
            'consumptie_date' => esc_sql($consumptie_date)),
                array('id' => esc_sql($edit_id)));
        echo 'consumptie updated';
    } else {
        $wpdb->insert($table_name, array(
            'user_id' => esc_sql($user_id),
            'quantity' => esc_sql($quantity),
            'consumptie_type' => esc_sql($consumptie_type),
            'consumptie_with' => esc_sql($consumptie_with),
            'consumptie_where' => esc_sql($consumptie_where),
            'consumptie_why' => esc_sql($consumptie_why),
            'consumptie_hour' => esc_sql($consumptie_hour),
            'consumptie_date' => esc_sql($consumptie_date))
        );
        echo 'consumptie toegevoegd';
    }
    die();
}

add_action("wp_ajax_add_consommationType", "add_consommationType", 10, 1);

//------------- Insert new consommation Type--------//
function add_consommationType($arg1) {
    global $wpdb;
    $user_id = get_current_user_id();

    if (empty($arg1)) {
        $consumptie_category = $_REQUEST['category'];
        $consumptie_size = $_REQUEST['size'];
        $consumptie_favourite = $_REQUEST['favourite'];
        $consumptie_name = $_REQUEST['name'];
    } else {
        $consumptie_category = $arg1['consumptie_category'];
        $consumptie_size = $arg1['consumptie_size'];
        $consumptie_name = $arg1['consumptie_name'];
    }

//check if person exist
    $result = $wpdb->get_results("SELECT * FROM wp_consumptie_type where type_name ='" . esc_sql($consumptie_name) . "' AND user_id = $user_id", ARRAY_A);

    if (count($result) == 0) {
        $typetable = $wpdb->prefix . "consumptie_type";
        $wpdb->insert($typetable, array(
            'user_id' => esc_sql($user_id),
            'type_category' => esc_sql($consumptie_category),
            'type_size' => esc_sql($consumptie_size),
            'type_favourite' => esc_sql($consumptie_favourite),
            'type_name' => esc_sql($consumptie_name))
        );
    }

    if (empty($arg1)) {
        $result = $wpdb->get_results("SELECT * FROM wp_consumptie_type where user_id = $user_id", ARRAY_A);
        $select = '';
        foreach ($result as $type) {
            if ($type["type_name"] == $consumptie_name) {
                $select .= '<option value="' . $type['id'] . '" selected="selected">' . $type["type_name"] . '</option>';
            } else {
                $select .= '<option value="' . $type['id'] . '" >' . $type["type_name"] . '</option>';
            }
        }

        echo $select;
    } else {
        wp_redirect($_SERVER['REQUEST_URI']);
    }
    die();
}

//------------- Insert new consommation partner--------//
add_action("wp_ajax_add_consommationPartner", "add_consommationPartner", 10, 1);

function add_consommationPartner($args) {
    global $wpdb;
    $user_id = get_current_user_id();

    if (empty($args)) {
        $consumptie_person = $_REQUEST['name'];
        $favouritePartner = $_REQUEST['favourite'];
    } else {
        $consumptie_person = $args['consumptie_person'];
    }
//check if person exist
    $result = $wpdb->get_results("SELECT * FROM wp_consumptie_partner where partner_name ='" . esc_sql($consumptie_person) . "' AND user_id = $user_id", ARRAY_A);

    if (count($result) == 0) {
        $typetable = $wpdb->prefix . "consumptie_partner";
        $wpdb->insert($typetable, array(
            'user_id' => esc_sql($user_id),
            'partner_name' => esc_sql($consumptie_person),
            'partner_favourite' => esc_sql($favouritePartner))
        );
    }

    if (empty($args)) {
        $result = $wpdb->get_results("SELECT * FROM wp_consumptie_partner where user_id = $user_id", ARRAY_A);
        $select = '';
        foreach ($result as $type) {
            if ($type["partner_name"] == $consumptie_person) {
                $select .= '<option selected="selected">' . $type["partner_name"] . '</option>';
            } else {
                $select .= '<option>' . $type["partner_name"] . '</option>';
            }
        }

        echo $select;
    } else {
        wp_redirect($_SERVER['REQUEST_URI']);
    }
    die();
}

//------------- Insert new consommation Place--------//
add_action("wp_ajax_add_consommationPlace", "add_consommationPlace", 10, 1);

function add_consommationPlace($args) {
    global $wpdb;
    $user_id = get_current_user_id();

    if (empty($args)) {
        $consumptie_place = $_REQUEST['place'];
        $favouritePlace = $_REQUEST['favourite'];
    } else {
        $consumptie_place = $args['consumptie_place'];
    }

//check if person exist
    $result = $wpdb->get_results("SELECT * FROM wp_consumptie_place where place_name ='" . esc_sql($consumptie_place) . "' AND user_id = $user_id", ARRAY_A);

    if (count($result) == 0) {
        $typetable = $wpdb->prefix . "consumptie_place";
        $wpdb->insert($typetable, array(
            'user_id' => esc_sql($user_id),
            'place_name' => esc_sql($consumptie_place),
            'place_favourite' => esc_sql($favouritePlace))
        );
    }


    if (empty($args)) {
        $result = $wpdb->get_results("SELECT * FROM wp_consumptie_place where user_id = $user_id", ARRAY_A);
        $select = '';
        foreach ($result as $type) {
            if ($type["place_name"] == $consumptie_place) {
                $select .= '<option selected="selected">' . $type["place_name"] . '</option>';
            } else {
                $select .= '<option>' . $type["place_name"] . '</option>';
            }
        }
        echo $select;
    } else {
        wp_redirect($_SERVER['REQUEST_URI']);
    }die();
}

//------------- Insert new consommation Reason--------//
add_action("wp_ajax_add_consommationReason", "add_consommationReason", 10, 1);

function add_consommationReason($args) {
    global $wpdb;
    $user_id = get_current_user_id();

    if (empty($args)) {
        $consumptie_reason = $_REQUEST['reason'];
        $favouriteReason = $_REQUEST['favourite'];
    } else {
        $consumptie_reason = $args['consumptie_reason'];
    }

//check if person exist
    $result = $wpdb->get_results("SELECT * FROM wp_consumptie_reason where reason_name ='" . esc_sql($consumptie_reason) . "' AND user_id = $user_id", ARRAY_A);

    if (count($result) == 0) {
        $typetable = $wpdb->prefix . "consumptie_reason";
        $wpdb->insert($typetable, array(
            'user_id' => esc_sql($user_id),
            'reason_name' => esc_sql($consumptie_reason),
            'reason_favourite' => esc_sql($favouriteReason))
        );
    }

    if (empty($args)) {
        $result = $wpdb->get_results("SELECT * FROM wp_consumptie_reason where user_id = $user_id", ARRAY_A);
        $select = '';
        foreach ($result as $type) {
            if ($type["reason_name"] == $consumptie_reason) {
                $select .= '<option selected="selected">' . $type["reason_name"] . '</option>';
            } else {
                $select .= '<option>' . $type["reason_name"] . '</option>';
            }
        }

        echo $select;
    } else {
        wp_redirect($_SERVER['REQUEST_URI']);
    }
    die();
}

//------------- Insert new consommation Drink--------//
add_action("wp_ajax_add_consommationDrink", "add_consommationDrink", 10, 1);

function add_consommationDrink($args) {
    global $wpdb;
    $user_id = get_current_user_id();

    if (empty($args)) {
        // $consumptie_drink = $_REQUEST['drink'];
        //  $favouriteReason = $_REQUEST['favourite'];
    } else {
        $consumptie_drink = $args['drink_name'];
        $consumptie_degree = $args['drink_degree'];
    }

//check if person exist
    $result = $wpdb->get_results("SELECT * FROM wp_consumptie_drinks where drinks_name ='" . esc_sql($consumptie_drink) . "' AND user_id = $user_id", ARRAY_A);

    if (count($result) == 0) {
        $typetable = $wpdb->prefix . "consumptie_drinks";
        $wpdb->insert($typetable, array(
            'user_id' => esc_sql($user_id),
            'drinks_name' => esc_sql($consumptie_drink),
            'drinks_degree' => esc_sql($consumptie_degree))
        );
    }

    if (empty($args)) {
        /*   $result = $wpdb->get_results("SELECT * FROM wp_consumptie_reason where user_id = $user_id", ARRAY_A);
          $select = '';
          foreach ($result as $type) {
          if ($type["reason_name"] == $consumptie_reason) {
          $select .= '<option selected="selected">' . $type["reason_name"] . '</option>';
          } else {
          $select .= '<option>' . $type["reason_name"] . '</option>';
          }
          }

          echo $select; */
    } else {
        wp_redirect($_SERVER['REQUEST_URI']);
    }
    die();
}

//------------- Insert new consommation Size / Glasses --------//
add_action("wp_ajax_add_consommationGlass", "add_consommationGlass", 10, 1);

function add_consommationGlass($args) {
    global $wpdb;
    $user_id = get_current_user_id();

    if (empty($args)) {
        // $consumptie_drink = $_REQUEST['drink'];
        //  $favouriteReason = $_REQUEST['favourite'];
    } else {
        $consumptie_glass = $args['glasses_name'];
        $consumptie_size = $args['glasses_size'];
    }

//check if person exist
    $result = $wpdb->get_results("SELECT * FROM wp_consumptie_glasses where glasses_name ='" . esc_sql($consumptie_glass) . "' AND user_id = $user_id", ARRAY_A);

    if (count($result) == 0) {
        $typetable = $wpdb->prefix . "consumptie_glasses";
        $wpdb->insert($typetable, array(
            'user_id' => esc_sql($user_id),
            'glasses_name' => esc_sql($consumptie_glass),
            'glasses_size' => esc_sql($consumptie_size))
        );
    }

    if (empty($args)) {
        /*   $result = $wpdb->get_results("SELECT * FROM wp_consumptie_reason where user_id = $user_id", ARRAY_A);
          $select = '';
          foreach ($result as $type) {
          if ($type["reason_name"] == $consumptie_reason) {
          $select .= '<option selected="selected">' . $type["reason_name"] . '</option>';
          } else {
          $select .= '<option>' . $type["reason_name"] . '</option>';
          }
          }

          echo $select; */
    } else {
        wp_redirect($_SERVER['REQUEST_URI']);
    }
    die();
}

//------------- Insert new add consommation--------//
add_action("wp_ajax_delete_consommation", "delete_consommation");

function delete_consommation() {
    global $wpdb;
    $delete_id = $_REQUEST['delete_id'];
    echo $delete_id;
    $table_name = $wpdb->prefix . "consumpties";
    $wpdb->delete($table_name, array(
        'id' => esc_sql($delete_id)
            )
    );
    die();
}
