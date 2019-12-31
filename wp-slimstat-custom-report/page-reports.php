<?php 
function timestampdiff($qw,$saw)
{
    $datetime1 = new DateTime("@$qw");
    $datetime2 = new DateTime("@$saw");
    $interval = $datetime1->diff($datetime2);
    return $interval->format('%Hh %Im %Ss');
}
//exit;
?>
<div class="reports">
	<form class="frmSearch" name="frmSearch" method="post" action="">
	<p id="date_filter">
		<span id="date-label-from" class="date-label">From: </span><input class="date_range_filter date" type="date" name="datepicker_from" id="datepicker_from" />
		<span id="date-label-to" class="date-label">To: </span><input class="date_range_filter date" type="date" name="datepicker_to" id="datepicker_to" />
	</p>
	<p id="radio_date_filter">
	   <input type="radio" name="filtertable" value="week" />This Week
	   <input type="radio" name="filtertable"  value="month"/>This Month
	</p>
	<input type="submit" name="go" value="Search" >
	</form>
<?php 	
echo "<table id='page-report' class='cpd-table'><thead><tr><th class='cpd-heading'>Institutional code</th>
<th class='cpd-heading'>Personal code</th>
<th class='cpd-heading'>Role</th>
<th class='cpd-heading'>FirstName</th>
<th class='cpd-heading'>Surname</th>
<th class='cpd-heading'>Username</th>
<th class='cpd-heading'>Email</th><th class='cpd-heading'>Pages</th><th class='cpd-heading'>Time on page</th><th class='cpd-heading'>Last time on page</th></tr></thead>";
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
		$reports = $wpdb->get_results("SELECT resource, username, dt, dt_out FROM escp_slim_stats WHERE username != '' AND dt_out != '' AND dt BETWEEN $weekstart AND $weekend");
		} 
		 if($_POST['filtertable'] == 'month') {
	$reports = $wpdb->get_results("SELECT resource, username, dt, dt_out FROM escp_slim_stats WHERE username != '' AND dt_out != '' AND dt BETWEEN $monthstart AND $monthend");
		} 
	}
	else {
	//$reports = $wpdb->get_results("SELECT MAX(dt) AS Maxdate, COUNT(DISTINCT visit_id) AS session_id, username, SUM(dt) AS intime, SUM(dt_out) AS outtime FROM escp_slim_stats WHERE dt BETWEEN $startdate AND $enddate GROUP BY username");
	$reports = $wpdb->get_results("SELECT resource, username, dt, dt_out FROM escp_slim_stats WHERE username != '' AND dt_out != '' AND dt BETWEEN $startdate AND $enddate");
	} 
	}
	else {
	$reports = $wpdb->get_results("SELECT resource, username, dt, dt_out FROM escp_slim_stats WHERE username != '' AND dt_out != ''");
	}
	
	//~ $time = array_sum(array_column($reports, 'dt'));
	//~ $outtime = array_sum(array_column($reports, 'dt_out'));
			//~ $onPagetime = date('H:i:s', $time);
			//~ $offPagetime = date('H:i:s', $outtime);
			//~ echo $onPagetime;
			//~ echo $offPagetime;
			//~ $totalpagetime = timestampdiff($outtime, $time);
			//~ echo $totalpagetime;

//	$count_page_results = count($reports);
//	$visit_id = $report->visit_id;
    foreach( $reports as $report ) {
        $username = $report->username;
		$pages = $report->resource;
		$pageIntime = $report->dt;
        $pageOuttime = $report->dt_out;
         $latestDate = date('m/d/Y H:i:s', $pageIntime);        
		$total_time = timestampdiff($pageOuttime, $pageIntime);
		$user = get_user_by( 'login',  $username );
		 $firstname = $user->first_name;	
		 $lastname = $user->last_name;	
		 $useremail = $user->user_email;	 
		  $user_info = get_userdata( $user->ID );
		$user_roles = implode(', ', $user_info->roles);	
		$user_code = get_user_meta( $user->ID, 'user_code' , true );
		$associated_center_code = get_user_meta( $user->ID, 'associated_center_code' , true );
		
      
        echo "<tr><td>$user_code</td>
			<td>$associated_center_code</td>
			<td>$user_roles</td>			
			<td>$firstname</td>
			<td>$lastname</td>
			<td>$username</td>
			<td>$useremail</td><td>$pages</td><td>$total_time</td><td>$latestDate</td></tr>";
        

    }
   	echo "</tbody> <tfoot> </tfoot>";

    echo "</table>";
    
    ?>
</div>
