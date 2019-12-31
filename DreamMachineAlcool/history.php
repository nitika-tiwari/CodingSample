<?php
global $wpdb;
$user_id = get_current_user_id();

$records = $wpdb->get_results('SELECT *, c.id as consumptie_id, YEAR(c.consumptie_date) as jaar, MONTH(c.consumptie_date) as maand, DAY(c.consumptie_date) as dag '
        . '     FROM wp_consumpties c '
        . '     INNER JOIN wp_consumptie_type t on c.consumptie_type = t.id '
        . '     INNER JOIN wp_consumptie_drinks d on t.type_category = d.id '
        . '     INNER JOIN wp_consumptie_glasses g on t.type_size = g.id '
        . '     WHERE c.user_id = ' . $user_id . ' '
        . '     ORDER BY c.consumptie_date asc');
$arr = array();

foreach ($records as $key => $value) {
    $totalUnite = ($value->quantity * (($value->glasses_size * $value->drinks_degree * FACTOR_ALCOOL) / 1000));
    $arr[$value->jaar . '-' . $value->maand]['dagen'][$value->dag] = $totalUnite + $arr[$value->jaar . '-' . $value->maand]['dagen'][$value->dag];
    $arr[$value->jaar . '-' . $value->maand]['locatie'][$value->consumptie_where] = $arr[$value->jaar . '-' . $value->maand]['locatie'][$value->consumptie_where] + $totalUnite;
    $arr[$value->jaar . '-' . $value->maand]['wie'][$value->consumptie_with] = $arr[$value->jaar . '-' . $value->maand]['wie'][$value->consumptie_with] + $totalUnite;
    $arr[$value->jaar . '-' . $value->maand]['reden'][$value->consumptie_why] = $arr[$value->jaar . '-' . $value->maand]['reden'][$value->consumptie_why] + $totalUnite;
}

foreach ($arr as $key => $value) {
    ?>
    <div class="currentmonth-history">
        <div class="row">
            <?php
            ksort($value['dagen']);
            $arrDatum = explode('-', $key);
            $jaar = $arrDatum[0];
            $nrMaand = $arrDatum[1];
            $maand = date('F', strtotime($key . '-01'));
            $aantalDagenInMaand = cal_days_in_month(CAL_GREGORIAN, $nrMaand, $jaar);
            ?>
            <div class="col-md-12">
                <?php print $maand . ' (' . $aantalDagenInMaand . ')'; ?>
            </div>
            <?php
            $totAantal = 0;
            $count = 0;
            foreach ($value['dagen'] as $k => $v) {
                $totAantal += $v;
                $count++;
                ?>

                <?php if ($count == 1) { ?>
                    <div class="col-md-4">
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="history-time">
                            <?php echo $k . ' ' . $maand; ?>
                        </div>
                        <div class="history-units">
                            <?php echo round($v,2); ?> unité<?php print ($v > 1) ? 'es' : ''; ?>
                        </div>

                    </div>
                    <?php if ($count == 3) { ?>
                    </div>
                    <?php $count = 0; } ?>  
                <?php
            }
            ?>
        </div>
    </div>
    <div class="bg-<?php print (($totAantal / $aantalDagenInMaand) < 1) ? 'success' : 'danger'; ?>">
        <?php $wie = array_search(max($value['wie']), $value['wie']); ?>
        <?php $locatie = array_search(max($value['locatie']), $value['locatie']); ?>
        <?php $reden = array_search(max($value['reden']), $value['reden']); ?>
        Au mois de <?php print $maand; ?> vous avez consommé en moyenne <?php print round($totAantal / $aantalDagenInMaand, 1); ?> consommations par jour: Votre lieu fovori de consommation était '<?php print $locatie; ?>', votre compagnon de consommation préféré était '<?php print $wie; ?>' et votre raison de boire était '<?php print $reden; ?>'. 

    </div>
<?php } ?>



<!--
<div class="container">


    <div class="currentmonth-history">
        <div class="row">
<?php foreach ($currentMonths as $currentMonth) { ?>	
                        <div class="col-md-4">
    <?php $month = strtotime($currentMonth['consumptie_date']);
    ?>	
                            <div class="history-time">
    <?php echo date("d,F", $month); ?>
                            </div>
                            <div class="history-units">
    <?php echo $currentMonth['quantity']; ?> unitÃƒÂ©
                            </div>
                        </div>
<?php } ?>
        </div>
<?php if ($currentMonth['quantity'] != "") { ?>
                    <div class="currentmonth-overview">
    <?php echo ceil($avgdrinks[0]['avgdrink']); ?> drinks per day: Your favorite place of consumption was 'Babette', your reference companion p was 'BÃƒÆ’Ã‚Â©bert' and your reason for drinking was 'boredom'.
                    </div>
<?php } else { ?>
                    <p>Aucune consommation n'a encore ÃƒÂ©tÃƒÂ© enregistrÃƒÂ©e.</p>
<?php } ?>
    </div>

</div>
-->
<style>
   .currentmonth-history div[class^="col-"] > div, .previousmonth-history div[class^="col-"] > div {
        display: inherit;
        margin-bottom: 5px;
        width: 100%;
    }
    .history-time,.history-units {
         width: 50% !important;
         float:left;
    }
</style>