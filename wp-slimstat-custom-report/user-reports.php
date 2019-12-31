<?php 
function timestampdiff($qw,$saw)
{
    $datetime1 = new DateTime("@$qw");
    $datetime2 = new DateTime("@$saw");
    $interval = $datetime1->diff($datetime2);
    return $interval->format('%Hh %Im %Ss');
}
//exit;

//~ if(isset($_POST["Export"])){
     //~ global $wpdb; 
     	//~ $reports = $wpdb->get_results("SELECT MAX(dt) AS Maxdate, COUNT(DISTINCT visit_id) AS session_id, username, SUM(dt) AS intime, SUM(dt_out) AS outtime FROM escp_slim_stats WHERE username != '' AND dt_out != '' GROUP BY username",  ARRAY_A);

//~ if ( ! empty( $reports ) ) {

    //~ header( 'Content-Type: text/csv; charset=utf-8' );
    //~ header( 'Content-Disposition: attachment; filename=data.csv' );

    //~ $fp = fopen( 'php://output', 'w' );
    
    //~ fputcsv( $fp, array_keys( $reports[0] ) );

    //~ foreach ( $reports as $row ) {
        //~ fputcsv( $fp, $row );
    //~ }

    //~ fclose( $fp );

//~ }
       
 //~ }  
?>
<!--
<button name="create_excel" id="create_excel">Export</button>

<script>
$(document).ready(function(){
                  $('#create_excel').click(function(){
                                           var excel_data = $('#user-report').html();
                                           
                                           var page = "http://www.riteshdembani.com/excel.php?data=" + excel_data;
                                           //alert(page);
                                           //window.location = page;
                                           
                                           var win = window.open(page, '_blank');
                                         
                                           });
                  });
</script>
-->
<div class="reports">
	<form class="frmSearch" name="frmSearch" method="post" action="">
	<p id="date_filter">
		<span id="date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="date" name="datepicker_from" id="datepicker_from"/>
		<span id="date-label-to" class="date-label">To: </span><input class="date_range_filter date" type="date" name="datepicker_to" id="datepicker_to"/>
	</p>
	<p id="radio_date_filter">
	   <input type="radio" name="filtertable" value="week" />This Week
	   <input type="radio" name="filtertable" value="month" />This Month
	</p>
	<input type="submit" name="go" value="Search" >
	</form>

<?php 	
echo "<table id='user-report' class='cpd-table'>
<thead><tr>
<th class='cpd-heading'>Institutional code</th>
<th class='cpd-heading'>Personal code</th>
<th class='cpd-heading'>Role</th>
<th class='cpd-heading'>FirstName</th>
<th class='cpd-heading'>Surname</th>
<th class='cpd-heading'>Username</th>
<th class='cpd-heading'>Email</th>
<th class='cpd-heading'>Last seen</th>
<th class='cpd-heading'>Total Number of logins</th>
<th class='cpd-heading'>Total time on website</th>
</tr></thead>";
    // echo "<tr><td>".$first_name."</td><td>".$last_name."</td></tr>;
   
     global $wpdb;
    
	echo "<tbody>";
	if(isset($_POST['go'])) {
		$radiofilter = $_POST['filtertable'];
		
		$fromdate = $_POST['datepicker_from'];
		$todate = $_POST['datepicker_to'];
		$startdate = strtotime($fromdate);
		$enddate = strtotime($todate);
		$currentDateTime = date('Y-m-d H:i:s');
	$current_dayname = date("l");             
	$weekstart = strtotime('monday this week');   
	$weekend = strtotime('sunday this week');    
	$current_monthname = date("m"); 
	$monthstart = strtotime('first day of this month');   
	$monthend = strtotime('last day of this month'); 
	
	if($_POST['filtertable'] != '') {  
		 if($_POST['filtertable'] == 'week') {
	$reports = $wpdb->get_results("SELECT MAX(dt) AS Maxdate, COUNT(DISTINCT visit_id) AS session_id, username, SUM(dt) AS intime, SUM(dt_out) AS outtime FROM escp_slim_stats WHERE username != '' AND dt_out != '' AND dt BETWEEN $weekstart AND $weekend GROUP BY username");
		} 
		
		 if($_POST['filtertable'] == 'month') {
	$reports = $wpdb->get_results("SELECT MAX(dt) AS Maxdate, COUNT(DISTINCT visit_id) AS session_id, username, SUM(dt) AS intime, SUM(dt_out) AS outtime FROM escp_slim_stats WHERE username != '' AND dt_out != '' AND dt BETWEEN $monthstart AND $monthend GROUP BY username");
		} 
	}
	else {	
	$reports = $wpdb->get_results("SELECT MAX(dt) AS Maxdate, COUNT(DISTINCT visit_id) AS session_id, username, SUM(dt) AS intime, SUM(dt_out) AS outtime FROM escp_slim_stats WHERE username != '' AND dt_out != '' AND dt BETWEEN $startdate AND $enddate GROUP BY username");
	}
	}
	else {
	$reports = $wpdb->get_results("SELECT MAX(dt) AS Maxdate, COUNT(DISTINCT visit_id) AS session_id, username, SUM(dt) AS intime, SUM(dt_out) AS outtime FROM escp_slim_stats WHERE username != '' AND dt_out != '' GROUP BY username");
	//$reports = $wpdb->get_results("SELECT MAX(dt) AS Maxdate, COUNT(DISTINCT visit_id) AS session_id, username, SUM(dt) AS intime, SUM(dt_out) AS outtime FROM escp_slim_stats GROUP BY username");
	}

    foreach( $reports as $report ) {
        $username = $report->username;
        $visit_id = $report->session_id;
		$host_by_ip = $report->ip;
		$siteIntime = $report->intime;
        $siteOuttime = $report->outtime;
        $lastseen_db =$report->Maxdate;
        $lastseen = date('m/d/Y H:i:s', $lastseen_db);        
		$total_time = timestampdiff($siteOuttime, $siteIntime);
		
		 $user = get_user_by( 'login',  $username );
		 $firstname = $user->first_name;	
		 $lastname = $user->last_name;	
		 $useremail = $user->user_email;
		 $user_info = get_userdata( $user->ID );
		$user_roles = implode(', ', $user_info->roles);	  
		$user_code = get_user_meta( $user->ID, 'user_code' , true );
		$associated_center_code = get_user_meta( $user->ID, 'associated_center_code' , true );
	//	echo 'User is ' .$user_code. ' ' . $associated_center_code;
		
		 	if($username != '') {
        echo "<tr>
			<td>$user_code</td>
			<td>$associated_center_code</td>
			<td>$user_roles</td>			
			<td>$firstname</td>
			<td>$lastname</td>
			<td>$username</td>
			<td>$useremail</td>			
			<td>$lastseen</td>
			<td>$visit_id</td><td>$total_time</td></tr>";        
			}
		
    }
   	echo "</tbody> <tfoot> </tfoot>";

    echo "</table>";
    
    ?>
</div>
