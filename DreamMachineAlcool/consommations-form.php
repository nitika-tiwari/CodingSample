<?php
/**
 * After t f's comment about putting global before the variable.
 * Not necessary (http://php.net/manual/en/language.variables.scope.php)
 */
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
global $wpdb;
$user_id = get_current_user_id();
$result = $wpdb->get_results("SELECT * FROM wp_consumptie_type where user_id = $user_id", ARRAY_A);
$partners = $wpdb->get_results("SELECT * FROM wp_consumptie_partner where user_id = $user_id", ARRAY_A);
$places = $wpdb->get_results("SELECT * FROM wp_consumptie_place where user_id = $user_id", ARRAY_A);
$reasons = $wpdb->get_results("SELECT * FROM wp_consumptie_reason where user_id in(0, $user_id)", ARRAY_A);
$drinks = $wpdb->get_results("SELECT * FROM wp_consumptie_drinks where user_id in(0, $user_id)", ARRAY_A);
$glasses = $wpdb->get_results("SELECT * FROM wp_consumptie_glasses where user_id in(0, $user_id)", ARRAY_A);

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_data = array();
    $result_consommation = $wpdb->get_results("SELECT * FROM wp_consumpties where user_id = $user_id AND id = $edit_id", ARRAY_A);

    if (!empty($result_consommation)) {
        $edit_data = $result_consommation[0];
    } else {
        echo '<center>You are not authorized to access this page!</center>';
        die;
    }
} else {
    $edit_id = '';
}
if (isset($_GET['date']) && $_GET['date'] != '0') {
    $datum = $_GET['date'];
} else {
    $datum = date('Y-m-d');
}
if (strtotime($datum) > strtotime(date('Ymd'))) {
    $datum = date('Y-m-d');
}
?>	

<div class="container consommation-form" id="consumptie">

    <form action="" method="post">

        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label>Combien de verres?</label>
                </div>
                <div class="col-md-6">
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="<?php echo isset($edit_data['quantity']) ? $edit_data['quantity'] : ''; ?>" required="">
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label>Quelle type de consommation</label>
                </div>
                <div class="col-md-6">
                    <select name="consumptie_type" id="consumptie_type">
                        <?php foreach ($result as $type) { ?>
                            <option value="<?php print $type['id']; ?>" <?php
                            if (isset($edit_data['consumptie_type']) && $edit_data['consumptie_type'] == $type['id']) {
                                echo "selected=='selected'";
                            }
                            ?>><?php echo $type['type_name']; ?></option>
                                <?php } ?>
                    </select>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addConsommation">+</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label>Avec qui?</label>
                </div>
                <div class="col-md-6">
                    <select name="consumptie_with" id="consumptie_with">
                        <?php foreach ($partners as $partner) { ?>
                            <option <?php
                            if (isset($edit_data['consumptie_with']) && $edit_data['consumptie_with'] == $partner['partner_name']) {
                                echo "selected=='selected'";
                            }
                            ?>><?php echo $partner['partner_name']; ?></option>
                            <?php } ?>
                    </select>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addPartner">+</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label>Où?</label>
                </div>
                <div class="col-md-6">
                    <select name="consumptie_where" id="consumptie_where">
                        <?php foreach ($places as $place) { ?>
                            <option <?php
                            if (isset($edit_data['consumptie_where']) && $edit_data['consumptie_where'] == $place['place_name']) {
                                echo "selected=='selected'";
                            }
                            ?>><?php echo $place['place_name']; ?></option>
                            <?php } ?>
                    </select>

                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addPlace">+</button>		
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label>pourquoi?</label>
                </div>
                <div class="col-md-6">
                    <select name="consumptie_why" id="consumptie_why">
                        <?php foreach ($reasons as $reason) { ?>
                            <option <?php
                            if (isset($edit_data['consumptie_why']) && $edit_data['consumptie_why'] == $reason['reason_name']) {
                                echo "selected=='selected'";
                            }
                            ?>><?php echo $reason['reason_name']; ?></option>
                            <?php } ?>
                    </select>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addReason">+</button>		
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label>A quelle heure (0-24)</label>
                </div>
                <div class="col-md-6">
                    <?php
                    if (isset($edit_data['consumptie_hour'])) {
                        $time = date("H:i", strtotime($edit_data['consumptie_hour']));
                    } else {
                        $time = date("H:i");
                    }
                    ?>

                    <input type="time" name="consumptie_hour" id="consumptie_hour" required="required" value="<?php echo $time; ?>"/>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-3">
                    <label>Date de la consommation</label>
                </div>

                <div class="col-md-6">
                    <input type="text" name="consumptie_date"  class="consumptie_date" id="consumptie_date" required="required" value="<?php print $datum; ?>" readonly="readonly"/>
                </div>
            </div>
        </div>

        <div class="form-group">

            <input type="checkbox" name="terms" id="terms" class="form-control" required="required" /><span>J'accepte les conditions du service</span>

        </div>

        <input type="button" id="addConsumptie" class="btn save-btn" name="add_consommation" value="<?php print (isset($edit_data['id'])) ? 'Adaptez votre consommation' : 'Ajouter votre consommation'; ?>" />


    </form>

    <br/>


</div>

<!------------- New Consommation Type ------>

<div class="modal fade" id="addConsommation" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <h3>Configurez une nouvelle type de consommation</h3>
                <form action="" method="post" id="consommation_type">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Quel type de boisson ?</label>
                            </div>
                            <div class="col-md-6">
                                <select id="consumptie_category" name="consumptie_category">
                                    <?php
                                    foreach ($drinks as $k => $v) {
                                        print '<option value="' . $v['id'] . '">' . $v['drinks_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12 small'>
                                Configurez une nouvelle type de boisson à la page des paramètres
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Taille du verre</label>
                            </div>
                            <div class="col-md-6">
                                <select name="consumptie_size" id="consumptie_size">
                                    <?php
                                    foreach ($glasses as $k => $v) {
                                        print '<option value="' . $v['id'] . '">' . $v['glasses_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12 small'>
                                Configurez une nouvelle taille de verre à la page des paramètres
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="consumptie_favourite"  id="consumptie_favourite" class="form-control" value="favourite" /><span>je conserve cette consommation comme consommation favori</span>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Nom de la consommation:</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="consumptie_name" id="consumptie_name" value="" required="required" />
                            </div>
                        </div>
                    </div>

                    <input type="button" id="consommation_type_btn" class="btn save-btn" name="add-consommationType" value="Aujouter un nouveau type de consumptie" />

                </form>
            </div>

        </div>

    </div>
</div>


<!------------- New Consommation Partner ------>

<div class="modal fade" id="addPartner" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <h3>Configurez vos compagnons de consommation</h3>
                <form action="" method="post">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Ajouter une personne</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="consumptie_person" id="consumptie_person" value="" required="required" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="favouritePartner" id="favouritePartner" class="form-control" value="favourite" /><span>je conserve cette consommation comme personne favori</span>
                    </div>

                    <input type="button" id="consommation_partner_btn" class="btn save-btn" name="add_consommationPartner" value="Ajouter" />

                </form>
            </div>

        </div>

    </div>
</div>

<!------------- New Consommation Place ------>

<div class="modal fade" id="addPlace" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <h3>Configurez vos lieux de consommation</h3>
                <form action="" method="post">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Ajouter un lieu de consumptie</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="consumptie_place" id="consumptie_place" value="" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="favouritePlace" id="favouritePlace" class="form-control" value="favourite" /><span>je conserve cette consommation comme lieu favori</span>
                    </div>

                    <input type="button" id="consommation_place_btn" class="btn save-btn" name="add_consommationPlace" value="Ajouter" />

                </form>
            </div>

        </div>

    </div>
</div>

<!------------- New Consommation Reason ------>

<div class="modal fade" id="addReason" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-body">
                <h3>Configurez vos raisons de consommation</h3>
                <form action="" method="post">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Ajouter une raison</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="consumptie_reason" id="consumptie_reason" value="" required="required" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="favouriteReason" id="favouriteReason" class="form-control" value="favourite" /><span>je conserve cette consommation comme raison favori</span>
                    </div>

                    <input type="button" id="consommation_reason_btn" class="btn save-btn" name="add_consommationReason" value="Ajouter" />

                </form>
            </div>

        </div>

    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery(function () {
            var currentYear = (new Date).getFullYear();
            var currentMonth = (new Date).getMonth();
            var currentDay = (new Date).getDate();

            jQuery("#consumptie_date").datepicker({
                minDate: new Date((currentYear - 1), 12, 1),
                dateFormat: 'yy-mm-dd',
                maxDate: new Date(currentYear, currentMonth, currentDay)
            });

        });


        jQuery("#addConsumptie").click(function (e) {
            e.preventDefault();

            var quantity = $('#quantity').val(),
                    consumptie_type = $('#consumptie_type').val(),
                    consumptie_with = $('#consumptie_with').val(),
                    consumptie_where = $('#consumptie_where').val(),
                    consumptie_why = $('#consumptie_why').val(),
                    consumptie_hour = $('#consumptie_hour').val(),
                    consumptie_date = $('#consumptie_date').val(),
                    edit_id = <?php echo!empty($edit_id) ? $edit_id : '""'; ?>,
                    nonce = '<?php echo wp_create_nonce("register_user_nonce") ?>',
                    ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

            if (quantity == '') {
                alert('Please fill the number of glasses');
                return false;
            }
            if (!($('#terms').is(':checked'))) {
                alert('conditions du service');
                return false;
            }
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {action: "add_consommation", nonce: nonce, quantity: quantity, consumptie_type: consumptie_type, consumptie_with: consumptie_with, consumptie_where: consumptie_where, consumptie_why: consumptie_why, consumptie_hour: consumptie_hour, consumptie_date: consumptie_date, edit_id: edit_id},
                success: function (data) {
                    $('#consumptie').html(data);
                }
            });
        });

        jQuery("#consommation_type_btn").click(function (e) {
            e.preventDefault();

            var category = $('#consumptie_category').val(),
                    size = $('#consumptie_size').val(),
                    name = $('#consumptie_name').val(),
                    nonce = '<?php echo wp_create_nonce("register_user_nonce") ?>',
                    ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

            if ($('#consumptie_favourite').is(':checked')) {
                favourite = $('#consumptie_favourite').val();
            } else {
                favourite = '';
            }

            if (name == '') {
                alert('Please fill the Nom de la consommation');
                return false;
            }
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {action: "add_consommationType", nonce: nonce, category: category, size: size, favourite: favourite, name: name},
                success: function (data) {
                    $('#consumptie_type').html(data);
                    $('#addConsommation').modal('hide');

                }
            });
        });

        jQuery("#consommation_partner_btn").click(function (e) {
            e.preventDefault();

            var name = $('#consumptie_person').val(),
                    nonce = '<?php echo wp_create_nonce("register_user_nonce") ?>',
                    ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

            if ($('#favouritePartner').is(':checked')) {
                favourite = $('#favouritePartner').val();
            } else {
                favourite = '';
            }

            if (name == '') {
                alert('Please fill friends name');
                return false;
            }
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {action: "add_consommationPartner", nonce: nonce, name: name, favourite: favourite},
                success: function (data) {
                    $('#consumptie_with').html(data);
                    $('#addPartner').modal('hide');

                },
                error: function (data) {
                    $('#consumptie_with').html(data);
                    $('#addPartner').modal('hide');

                }
            });
        });

        jQuery("#consommation_place_btn").click(function (e) {
            e.preventDefault();

            var place = $('#consumptie_place').val(),
                    nonce = '<?php echo wp_create_nonce("register_user_nonce") ?>',
                    ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

            if ($('#favouritePlace').is(':checked')) {
                favourite = $('#favouritePlace').val();
            } else {
                favourite = '';
            }

            if (place == '') {
                alert('Please fill place name');
                return false;
            }
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {action: "add_consommationPlace", nonce: nonce, place: place, favourite: favourite},
                success: function (data) {
                    $('#consumptie_where').html(data);
                    $('#addPlace').modal('hide');

                },
                error: function (data) {
                    $('#consumptie_where').html(data);
                    $('#addPlace').modal('hide');
                }
            });
        });

        jQuery("#consommation_reason_btn").click(function (e) {
            e.preventDefault();

            var reason = $('#consumptie_reason').val(),
                    nonce = '<?php echo wp_create_nonce("register_user_nonce") ?>',
                    ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

            if ($('#favouriteReason').is(':checked')) {
                favourite = $('#favouriteReason').val();
            } else {
                favourite = '';
            }

            if (reason == '') {
                alert('Please fill reason');
                return false;
            }
            jQuery.ajax({
                type: "post",
                url: ajaxurl,
                data: {action: "add_consommationReason", nonce: nonce, reason: reason, favourite: favourite},
                success: function (data) {
                    $('#consumptie_why').html(data);
                    $('#addReason').modal('hide');

                },
                error: function (data) {
                    $('#consumptie_why').html(data);
                    $('#addReason').modal('hide');

                }
            });
        });
    });
</script>
<!--

  
<!--

<script type="text/javascript">


   

    //~ $("form").submit(function(e){

        //~ e.preventDefault();

        //~ var name = $("input[name='name']").val();

        //~ var email = $("input[name='email']").val();

     

        //~ $(".data-table tbody").append("<tr data-name='"+name+"' data-email='"+email+"'><td>"+name+"</td><td>"+email+"</td><td><button class='btn btn-info btn-xs btn-edit'>Edit</button><button class='btn btn-danger btn-xs btn-delete'>Delete</button></td></tr>");

    

        //~ $("input[name='name']").val('');

        //~ $("input[name='email']").val('');

    //~ });

   

    //~ $("body").on("click", ".btn-delete", function(){

        //~ $(this).parents("tr").remove();

    //~ });

    

    //~ $("body").on("click", ".btn-edit", function(){

        //~ var name = $(this).parents("tr").attr('data-name');

        //~ var email = $(this).parents("tr").attr('data-email');

    

        //~ $(this).parents("tr").find("td:eq(0)").html('<input name="edit_name" value="'+name+'">');

        //~ $(this).parents("tr").find("td:eq(1)").html('<input name="edit_email" value="'+email+'">');

    

        //~ $(this).parents("tr").find("td:eq(2)").prepend("<button class='btn btn-info btn-xs btn-update'>Update</button><button class='btn btn-warning btn-xs btn-cancel'>Cancel</button>")

        //~ $(this).hide();

    //~ });

   

    //~ $("body").on("click", ".btn-cancel", function(){

        //~ var name = $(this).parents("tr").attr('data-name');

        //~ var email = $(this).parents("tr").attr('data-email');

    

        //~ $(this).parents("tr").find("td:eq(0)").text(name);

        //~ $(this).parents("tr").find("td:eq(1)").text(email);

   

        //~ $(this).parents("tr").find(".btn-edit").show();

        //~ $(this).parents("tr").find(".btn-update").remove();

        //~ $(this).parents("tr").find(".btn-cancel").remove();

    //~ });

   

    //~ $("body").on("click", ".btn-update", function(){

        //~ var name = $(this).parents("tr").find("input[name='edit_name']").val();

        //~ var email = $(this).parents("tr").find("input[name='edit_email']").val();

    

        //~ $(this).parents("tr").find("td:eq(0)").text(name);

        //~ $(this).parents("tr").find("td:eq(1)").text(email);

     

        //~ $(this).parents("tr").attr('data-name', name);

        //~ $(this).parents("tr").attr('data-email', email);

    

        //~ $(this).parents("tr").find(".btn-edit").show();

        //~ $(this).parents("tr").find(".btn-cancel").remove();

        //~ $(this).parents("tr").find(".btn-update").remove();

    //~ });

    

</script>
-->




