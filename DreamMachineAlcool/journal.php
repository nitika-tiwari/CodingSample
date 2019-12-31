<?php
// find correct page with shortcode for editing a consommation item
$editURL = '';
foreach (get_pages() as $k => $v) {
    if (strstr($v->post_content, '[consumptie_form]')) {
        $editURL = get_page_link($v->ID);
    }
}


global $wpdb;
$user_id = get_current_user_id();

//setlocale(LC_TIME, 'fr_FR' . '.utf8');
//*****************************
if (isset($_GET['week']) && $_GET['week'] != '0') {
    $datum = strtotime($_GET['week']);
} else {
    $datum = strtotime(date('Ymd'));
}

// avoid future date
if ($datum > strtotime(date('Ymd'))) {
    $datum = strtotime(date('Ymd'));
}

// week functions
$week = week_start_end_by_date($datum);
$previousWeek = week_start_end_by_date(strtotime($week['first_day_of_week'] . ' -1 day'));
$nextWeek = week_start_end_by_date(strtotime($week['last_day_of_week'] . ' +1 day'));

for ($i = 0; $i < 7; $i++) {
    $datum = date('Y-m-d', strtotime($week['first_day_of_week'] . " +" . $i . " days"));
    if (strtotime($datum) <= strtotime(date('Ymd'))) {
        $arr['dagen'][$datum][] = array('date' => $datum);
    }
}
//*****************************

$records = $wpdb->get_results('SELECT *, c.id as consumptie_id, YEAR(c.consumptie_date) as jaar, MONTH(c.consumptie_date) as maand, DAY(c.consumptie_date) as dag '
        . '     FROM wp_consumpties c '
        . '     INNER JOIN wp_consumptie_type t on c.consumptie_type = t.id '
        . '     INNER JOIN wp_consumptie_drinks d on t.type_category = d.id '
        . '     INNER JOIN wp_consumptie_glasses g on t.type_size = g.id '
        . '     WHERE c.user_id = ' . $user_id . ' '
        . '         AND c.consumptie_date > "' . $previousWeek['last_day_of_week'] . '" '
        . '         AND c.consumptie_date < "' . $nextWeek['first_day_of_week'] . '" '
        . '     ORDER BY c.consumptie_date asc');


//$arr = array();
// fill array for easier working
foreach ($records as $key => $value) {
    $totalUnite = ($value->quantity * (($value->glasses_size * $value->drinks_degree * FACTOR_ALCOOL) / 1000));
    $arr['weeklyTotal'] = $arr['weeklyTotal'] + $totalUnite;
    $arr['totalen'][$value->consumptie_date] = $value->quantity + $arr['totalen'][$value->consumptie_date];
    $arr['locatie'][$value->consumptie_where] = $arr['locatie'][$value->consumptie_where] + $totalUnite;
    $arr['wie'][$value->consumptie_with] = $arr['wie'][$value->consumptie_with] + $totalUnite;
    $arr['reden'][$value->consumptie_why] = $arr['reden'][$value->consumptie_why] + $totalUnite;

    if (strtotime($value->consumptie_date) <= strtotime(date('Ymd'))) {
        $arr['dagen'][$value->consumptie_date][] = array(
            'quantity' => $value->quantity,
            'type' => $value->type_name,
            'where' => $value->consumptie_where,
            'with' => $value->consumptie_with,
            'why' => $value->consumptie_why,
            'date' => $value->consumptie_date,
            'hour' => $value->consumptie_hour,
            'id' => $value->consumptie_id,
            'unite' => $totalUnite,
        );
    }
}
?>
<div class="container">
    <div class="mon-journal-full-box">
        <div class="container">
            <h1>
                <a href="?week=<?php print $previousWeek['year'] . 'W' . $previousWeek['week']; ?>">
                    <span><button type="button" class="btn btn-danger mon-journal-left-button">
                            <i class="fa fa-chevron-left" aria-hidden="true"></i></button></span>                    
                </a>

                <?php setlocale(LC_TIME, 'fr_FR' . '.utf8'); ?>
                <strong>Semaine de <?php print date('d', $week['first_day_of_week_timestamp']) . ' ' . strftime('%B', $week['first_day_of_week_timestamp']) . ' ' . date('Y', $week['first_day_of_week_timestamp']); ?></strong>

                <?php if ($week['last_day_of_week_timestamp'] < $nextWeek['current_date_timestamp']) { ?>
                    <a href="?week=<?php print $nextWeek['year'] . 'W' . $nextWeek['week']; ?>">
                        <span><button type="button" class="btn btn-danger mon-journal-right-button"><i class="fa fa-chevron-right" aria-hidden="true"></i></button></span>									
                    </a>
                <?php } ?>

            </h1>
            <?php if (count($arr) > 0) { ?>
                <?php $oldWeek = ''; ?>
                <?php if (is_numeric($arr['weeklyTotal'])) { ?>    
                    <div class="mon-journal-deta-contentTotal">
                        <span class="mon-journal-masege-countTotal"><?php print round($arr['weeklyTotal'], 2); ?></span>
                    </div>
                <?php } ?>

                <?php $totalUnite = 0; ?>
                <?php foreach ($arr['dagen'] as $key => $value) { ?>
                    <div class="mon-journal-deta-content">
                        <?php $totalUniteDay = 0; ?>
                        <?php foreach ($value as $k => $v) { ?>
                            <?php $weekdate = strtotime($v['date']); ?>	
                            <?php if ($oldWeek != $weekdate) { ?>                            
                                <h3><?php echo ucfirst(strftime('%A', $weekdate)) . ' ' . date('d', $weekdate) . ' ' . strftime('%B', $weekdate) . ' ' . date('Y', $weekdate); ?></h3>  
                            <?php } ?>
                            <p><?php
                                if (!empty($v['quantity'])) {
                                    $totalUniteDay += $v['unite'];
                                    $totalUnite += $v['unite'];
                                    print $v['quantity'];
                                    ?> verres (<?php print round($v['unite'], 2); ?>)
                                    - <?php echo $v['type']; ?>  
                                    - avec <?php echo $v['with']; ?> 
                                    - <?php echo $v['hour']; ?> 
                                    - raison: <?php echo $v['why']; ?>
                                    <a href="<?php print $editURL; ?>?edit_id=<?php print $v['id']; ?>&date=<?php print $v['date']; ?>" class="mon-journal-edit" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a delete-id="<?php print $v['id']; ?>" href="" onclick="return false;" class="mon-journal-delete delete_item"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                <?php } ?>
                            <p>
                                <?php $oldWeek = $weekdate; ?>
                            <?php } ?>
                        <p class="mon-journal-add-more"> <a href="<?php print get_page_link(594) . '?date=' . $v['date']; ?>">ADD</a></p>
                        <span class="mon-journal-masege-count"><?php print round($totalUniteDay, 2); ?></span>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>

    <?php if (is_numeric($arr['weeklyTotal'])) { ?>    
        <?php $wie = array_search(max($arr['wie']), $arr['wie']); ?>
        <?php $locatie = array_search(max($arr['locatie']), $arr['locatie']); ?>
        <?php $reden = array_search(max($arr['reden']), $arr['reden']); ?>

        <br/><br/>
        Cette semaine, vous-avez bu <b><?php print round($arr['weeklyTotal'], 2); ?> unités d'alcool</b>. vous avez bu avec <?php print $wie; ?>, et la raison principale était: <?php print $reden; ?>.<br/>
        <b>Nos conseils:</b> surveillez votre consommation d'alcool quand vous êtes avec <?php print $wie; ?>. Pour votre santé nous vous conseillons de ne PAS boire plus que 14 unités par semaine. L'alcool diminue la qualité de votre sommeil.
    <?php } ?>
</div>

<style>
    .mon-journal-full-box{
        width: 100%; 
        float: left;
        margin-bottom: 15px;
        margin-top: 15px;
    }
    .mon-journal-full-box h1{
        font-size: 24px; 
        color: #000;
        font-weight: 600;
        position: relative;
    }
    .mon-journal-full-box .mon-journal-left-button, .mon-journal-full-box .mon-journal-right-button{
        color: #000;
        background-color: transparent;
        border-color: transparent;
    }
    .mon-journal-full-box .mon-journal-left-button{
        padding-left: 0px;
    }
    .mon-journal-deta-content{
        width: 100%;
        float: left;
        padding: 20px 15px 15px 50px;
        background-color: #d6d6d6;  
        position: relative;
    }
    .mon-journal-deta-contentTotal{
        width: 100%;
        float: left;
        margin-top: -70px;
        position: relative;
    }
    .mon-journal-deta-content h3{
        color: #7e7e7e;
        font-size: 18px;
        margin-bottom: 6px;
        font-weight: 400;
        margin-top: 0;
    }
    .mon-journal-deta-content p{
        color: #7e7e7e;
        font-size: 14px;
        margin-bottom: 6px;
    }
    .mon-journal-edit:visited, .mon-journal-delete:visited{
        font-size: 14px;
        margin-left: 10px;
        color: #b80400;
    }
    .mon-journal-edit, .mon-journal-delete{
        font-size: 14px;
        margin-left: 10px;
        color: #b80400;
    }
    .mon-journal-delete .fa-plus-circle{
        transform: rotate(42deg);
    }
    .mon-journal-masege-count{
        background-color: #907c78;
        position: absolute;
        right: 15px;
        top: 25px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50px;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        border: 1px solid #fff;
    }
    .mon-journal-masege-countTotal{
        background-color: #cf0303;
        position: absolute;
        right: 15px;
        top: 25px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50px;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        border: 1px solid #fff;
    }
    .mon-journal-add-more a{
        font-size: 12px !important;
        color: #b80400 !important;
    }
    .mon-journal-total-masege-count{
        background-color: #b80400;
        position: absolute;
        right: 15px;
        top: 0px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50px;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        border: 1px solid #fff;
    }
    .mon-journal-deta-content:nth-child(even) {background: #CCC}
    .mon-journal-deta-content:nth-child(odd) {background: #FFF}
    @media only screen and (max-width: 568px) {
        .mon-journal-deta-content{
            padding: 20px 15px 15px 20px;
        }
    }
    @media only screen and (max-width: 414px) {
        .mon-journal-masege-count{
            top: 10px;
        }
        .mon-journal-full-box h1 {
            font-size: 20px;
        }
        .mon-journal-total-masege-count{
            right: 0
        }
    }
    @media only screen and (max-width: 411px) {

    }
    @media only screen and (max-width: 384px) {
        .mon-journal-full-box h1 {
            font-size: 16px;
        }
    }
</style>

<script>
    jQuery(document).ready(function () {
        jQuery(".delete_item").click(function (e) {
            e.preventDefault();
            var delete_id = $(this).attr('delete-id'),
                    nonce = '<?php echo wp_create_nonce("delete_consommation_nonce") ?>',
                    ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var result = confirm("Are you sure you want to delete this item?");
            if (result) {
                jQuery.ajax({
                    type: "post",
                    url: ajaxurl,
                    data: {action: "delete_consommation", nonce: nonce, delete_id: delete_id},
                    success: function (data) {
                        alert('Consommation deleted successfully');
                        location.reload();
                    }
                });
            }
        });
    });
</script>
<?php

function week_start_end_by_date($date, $format = 'Y-m-d') {

    //om maanden te vertalen naar FR
    //setlocale(LC_TIME, 'fr_FR' . '.utf8');
    //Is $date timestamp or date?
    if (is_numeric($date) AND strlen($date) == 10) {
        $time = $date;
    } else {
        $time = strtotime($date);
    }

    $week['week'] = date('W', $time);
    $week['year'] = date('o', $time);
    $week['year_week'] = date('oW', $time);
    $first_day_of_week_timestamp = strtotime($week['year'] . "W" . str_pad($week['week'], 2, "0", STR_PAD_LEFT));
    $week['first_day_of_week'] = date($format, $first_day_of_week_timestamp);
    $week['first_day_of_week_timestamp'] = $first_day_of_week_timestamp;
    $last_day_of_week_timestamp = strtotime($week['first_day_of_week'] . " +6 days");
    $week['last_day_of_week'] = date($format, $last_day_of_week_timestamp);
    $week['last_day_of_week_timestamp'] = $last_day_of_week_timestamp;
    $week['current_date_timestamp'] = strtotime(date($format));

    return $week;
}
?>