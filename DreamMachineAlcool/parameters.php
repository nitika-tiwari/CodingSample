<?php
/**
 * After t f's comment about putting global before the variable.
 * Not necessary (http://php.net/manual/en/language.variables.scope.php)
 */
//------------- Insert new consommation Type--------//
if (isset($_POST['add-consommationType'])) {
    do_action("wp_ajax_add_consommationType", $_POST);
}

if (isset($_POST['delete-consommationType'])) {
    global $wpdb;
    $typetable = $wpdb->prefix . "consumptie_type";
    $wpdb->delete($typetable, array('id' => $_POST['typeId']));
}

//------------- Insert new consommation partner--------//
if (isset($_POST['add_consommationPartner'])) {
    do_action("wp_ajax_add_consommationPartner", $_POST);
}

if (isset($_POST['delete-consommationPartner'])) {
    global $wpdb;
    $typetable = $wpdb->prefix . "consumptie_partner";
    $wpdb->delete($typetable, array('id' => $_POST['partnerId']));
}

//------------- Insert new consommation Place--------//
if (isset($_POST['add_consommationPlace'])) {
    do_action("wp_ajax_add_consommationPlace", $_POST);
}

if (isset($_POST['delete-consommationPlace'])) {
    global $wpdb;
    $typetable = $wpdb->prefix . "consumptie_place";
    $wpdb->delete($typetable, array('id' => $_POST['placeId']));
}


//------------- Insert new consommation Reason--------//
if (isset($_POST['add_consommationReason'])) {
    do_action("wp_ajax_add_consommationReason", $_POST);
}

if (isset($_POST['delete-consommationReason'])) {
    global $wpdb;
    $typetable = $wpdb->prefix . "consumptie_reason";
    $wpdb->delete($typetable, array('id' => $_POST['reasonId']));
}

//------------- Insert new consommation Drink--------//
if (isset($_POST['add_consommationDrink'])) {
    do_action("wp_ajax_add_consommationDrink", $_POST);
}

if (isset($_POST['delete-consommationDrink'])) {
    global $wpdb;
    $typetable = $wpdb->prefix . "consumptie_drinks";
    $wpdb->delete($typetable, array('id' => $_POST['drinkId']));
}

//------------- Insert new consommation Glasses / sizes --------//
if (isset($_POST['add_consommationGlass'])) {
    do_action("wp_ajax_add_consommationGlass", $_POST);
}

if (isset($_POST['delete-consommationGlass'])) {
    global $wpdb;
    $typetable = $wpdb->prefix . "consumptie_glasses";
    $wpdb->delete($typetable, array('id' => $_POST['glassId']));
}
?>
<?php
global $wpdb;
$user_id = get_current_user_id();
$result = $wpdb->get_results("SELECT *, t.id as consumptie_typeId FROM wp_consumptie_type t "
        . " inner join wp_consumptie_drinks d on t.type_category = d.id"
        . " inner join wp_consumptie_glasses g on t.type_size = g.id"
        . " where t.user_id = $user_id", ARRAY_A);
$partners = $wpdb->get_results("SELECT * FROM wp_consumptie_partner where user_id = $user_id", ARRAY_A);
$places = $wpdb->get_results("SELECT * FROM wp_consumptie_place where user_id = $user_id", ARRAY_A);
$reasons = $wpdb->get_results("SELECT * FROM wp_consumptie_reason where user_id = $user_id", ARRAY_A);
$drinks = $wpdb->get_results("SELECT * FROM wp_consumptie_drinks where user_id in(0, $user_id)", ARRAY_A);
$glasses = $wpdb->get_results("SELECT * FROM wp_consumptie_glasses where user_id in(0, $user_id)", ARRAY_A);
?>	

<div class="container consommation-form">
    <?php //*************************************************************************  ?>
    <h3>Configurez vos types de consommation</h3>

    <input type="hidden" name="posttype" value="type"/>

    <div class="form-group">
        <?php foreach ($result as $k => $v) { ?>
            <div class="row">                
                <div class="col-md-2">
                    <label><?php print $v['type_name']; ?></label>                    
                </div>
                <div class="col-md-4">                        
                    <?php print $v['drinks_name']; ?>                        
                </div>
                <div class="col-md-4">
                    <?php print $v['glasses_name']; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <form action="" method="post">
                        <input type="hidden" name="typeId" value="<?php print $v['consumptie_typeId']; ?>" />
                        <input type="submit" class="btn save-btn" name="delete-consommationType" value="Desactiver" />
                    </form>
                </div>
            </div>
        <?php } ?>
        <form action="" method="post">
            <div class="row">                
                <div class="col-md-2">
                    <input type="text" name="consumptie_name" class='form-control'/>                    
                </div>
                <div class="col-md-4">
                    <select name="consumptie_category" class='form-control'>
                        <?php
                        foreach ($drinks as $k => $v) {
                            print '<option value="' . $v['id'] . '">' . $v['drinks_name'] . '</option>';
                        }
                        ?>                       
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="consumptie_size" class='form-control'>
                        <?php
                        foreach ($glasses as $k => $v) {
                            print '<option value="' . $v['id'] . '">' . $v['glasses_name'] . '</option>';
                        }
                        ?>  
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="btn save-btn" name="add-consommationType" value="Aujouter" />
                </div>
            </div>
        </form>
    </div>

    <br/>
    <?php //*************************************************************************  ?>

    <?php //*************************************************************************   ?>
    <h3>Configurez vos lieux de consommation</h3>

    <input type="hidden" name="posttype" value="type"/>

    <div class="form-group">
        <?php foreach ($places as $k => $v) { ?>
            <div class="row">                
                <div class="col-md-10">
                    <label><?php print $v['place_name']; ?></label>                    
                </div>
                <div class="col-md-2">
                    <form action="" method="post">
                        <input type="hidden" name="placeId" value="<?php print $v['id']; ?>" />
                        <input type="submit" class="btn save-btn" name="delete-consommationPlace" value="Desactiver" />
                    </form>
                </div>
            </div>
        <?php } ?>
        <form action="" method="post">
            <div class="row">                
                <div class="col-md-10">
                    <input type="text" name="consumptie_place" class='form-control'/>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="btn save-btn" name="add_consommationPlace" value="Aujouter" />
                </div>
            </div>
        </form>
    </div>

    <br/>
    <?php //*************************************************************************  ?>

    <?php //*************************************************************************   ?>
    <h3>Configurez vos compagnons de consommation</h3>

    <input type="hidden" name="posttype" value="type"/>

    <div class="form-group">
        <?php foreach ($partners as $k => $v) { ?>
            <div class="row">                
                <div class="col-md-10">
                    <label><?php print $v['partner_name']; ?></label>                    
                </div>
                <div class="col-md-2">
                    <form action="" method="post">
                        <input type="hidden" name="partnerId" value="<?php print $v['id']; ?>" />
                        <input type="submit" class="btn save-btn" name="delete-consommationPartner" value="Desactiver" />
                    </form>
                </div>
            </div>
        <?php } ?>
        <form action="" method="post">
            <div class="row">                
                <div class="col-md-10">
                    <input type="text" name="consumptie_person" class='form-control'/>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="btn save-btn" name="add_consommationPartner" value="Aujouter" />
                </div>
            </div>
        </form>
    </div>

    <br/>
    <?php //*************************************************************************  ?>

    <?php //*************************************************************************   ?>
    <h3>Configurez vos raisons de consommation</h3>

    <input type="hidden" name="posttype" value="type"/>

    <div class="form-group">
        <?php foreach ($reasons as $k => $v) { ?>
            <div class="row">                
                <div class="col-md-10">
                    <label><?php print $v['reason_name']; ?></label>                    
                </div>
                <div class="col-md-2">
                    <form action="" method="post">
                        <input type="hidden" name="reasonId" value="<?php print $v['id']; ?>" />
                        <input type="submit" class="btn save-btn" name="delete-consommationReason" value="Desactiver" />
                    </form>
                </div>
            </div>
        <?php } ?>
        <form action="" method="post">
            <div class="row">                
                <div class="col-md-10">
                    <input type="text" name="consumptie_reason" class='form-control'/>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="btn save-btn" name="add_consommationReason" value="Aujouter" />
                </div>
            </div>
        </form>
    </div>

    <br/>
    <?php //*************************************************************************   ?>
    <?php //*************************************************************************  ?>
    <h3>Configurez vos types de boissons </h3>

    <input type="hidden" name="posttype" value="type"/>

    <div class="form-group">
        <?php foreach ($drinks as $k => $v) { ?>
            <?php if ($v['user_id'] > 0) { ?>    
                <div class="row">                
                    <div class="col-md-7">
                        <label><?php print $v['drinks_name']; ?></label>                    
                    </div>
                    <div class="col-md-3">
                        <label><?php print $v['drinks_degree']; ?></label>                    
                    </div>
                    <div class="col-md-2">
                        <form action="" method="post">
                            <input type="hidden" name="drinkId" value="<?php print $v['id']; ?>" />
                            <input type="submit" class="btn save-btn" name="delete-consommationDrink" value="Desactiver" />
                        </form>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <form action="" method="post">
            <div class="row">                
                <div class="col-md-7">
                    <input type="text" name="drink_name" placeholder='Nom' class='form-control'/>
                </div>
                <div class="col-md-3">
                    <input type="number" name="drink_degree" placeholder='Degree' class='form-control'/>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="btn save-btn" name="add_consommationDrink" value="Aujouter" />
                </div>
            </div>
        </form>
    </div>

    <br/>
    <?php //*************************************************************************  ?>
    <?php //*************************************************************************  ?>
    <h3>Configurez vos tailles de verres  </h3>

    <input type="hidden" name="posttype" value="type"/>

    <div class="form-group">
        <?php foreach ($glasses as $k => $v) { ?>
            <?php if ($v['user_id'] > 0) { ?>    
                <div class="row">                
                    <div class="col-md-7">
                        <label><?php print $v['glasses_name']; ?></label>                    
                    </div>
                    <div class="col-md-3">
                        <label><?php print $v['glasses_size']; ?></label>                    
                    </div>
                    <div class="col-md-2">
                        <form action="" method="post">
                            <input type="hidden" name="glassId" value="<?php print $v['id']; ?>" />
                            <input type="submit" class="btn save-btn" name="delete-consommationGlass" value="Desactiver" />
                        </form>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <form action="" method="post">
            <div class="row">                
                <div class="col-md-7">
                    <input type="text" name="glasses_name" placeholder='Nom' class='form-control'/>
                </div>
                <div class="col-md-3">
                    <input type="number" name="glasses_size" placeholder='Taille' class='form-control'/>
                </div>
                <div class="col-md-2">
                    <input type="submit" class="btn save-btn" name="add_consommationGlass" value="Aujouter" />
                </div>
            </div>
        </form>
    </div>

    <br/>
    <?php //*************************************************************************  ?>


</div>

