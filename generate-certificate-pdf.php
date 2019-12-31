<?php
/*
 Template Name: certificate-template
 */
cpd_generate_pdf();
function cpd_generate_pdf() {
ob_start();
//exit('nitika');
global $wpdb;
$certificate_id = $_GET['id'];
if($certificate_id ){
	$wpdb->query("UPDATE `escp_cpd_completion` SET cpd_certification_date= now() WHERE cpd_user_id = $certificate_id AND cpd_certification_date = '0000-00-00 00:00:00'");
}

  $generation_date = $wpdb->get_results("SELECT * FROM `escp_cpd_completion` WHERE cpd_user_id = $certificate_id");
	 foreach ($generation_date as $date) { 
	//$certificate_date = $date->cpd_certification_date;
	$certificate_date = mysql2date('l, jS F, Y', $date->cpd_certification_date);
	
//$certificate_date = date("l, jS F, Y");

}
         $users = $wpdb->get_results("SELECT * FROM `escp_users` WHERE ID = $certificate_id");
		
		 foreach ($users as $user_info) { 
		
		
		
		 $firstName = get_user_meta($user_info->ID, 'first_name', true);
    $lastName = get_user_meta($user_info->ID, 'last_name', true);
    
    $username = $firstName." ". $lastName ;

		if($username == " "){
			
        $username = $user_info->display_name;
		}
        
       // $email = $user_info->user_email;
        
	}
//echo get_template_directory();
	// Include the main TCPDF library (search for installation path).
require_once( get_template_directory().'/tcpdf/tcpdf.php');
//exit("test");
//~ exit( plugin_dir_path( __FILE__ ).'cpd-email/tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RD');
$pdf->SetTitle('CPD Certification');
$pdf->SetSubject('CPD Certification');
$pdf->SetKeywords('CPD, PDF, example, test, guide');


// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print 
$htmlCustom = '<html>
   <head>
      <meta content="text/html; charset=UTF-8" http-equiv="content-type">
      <style type="text/css">@import url("https://themes.googleusercontent.com/fonts/css?kit=fpjTOVmNbO4Lz34iLyptLTi9jKYd1gJzj5O2gWsEpXpj_OG7X-gir6BHqmyKj4qWMMBmuJogSv5Hdgfwmi0hB1tXRa8TVwTICgirnJhmVJw");ol{margin:0;padding:0}table td,table th{padding:0}
    
    .c49{border-right-style:none;padding:0pt 5.4pt 0pt 5.4pt;border-bottom-color:#ffffff;border-top-width:1pt;border-right-width:1pt;border-left-color:#ffffff;vertical-align:middle;border-right-color:#ffffff;border-left-width:1pt;border-top-style:solid;border-left-style:solid;border-bottom-width:1pt;width:127.3pt;border-top-color:#ffffff;border-bottom-style:solid}.c18{border-right-style:solid;padding:0pt 5.4pt 0pt 5.4pt;border-bottom-color:#ffffff;border-top-width:0pt;border-right-width:0pt;border-left-color:#ffffff;vertical-align:top;border-right-color:#ffffff;border-left-width:0pt;border-top-style:solid;border-left-style:solid;border-bottom-width:0pt;width:518pt;border-top-color:#ffffff;border-bottom-style:solid}.c6{border-right-style:solid;padding:0pt 5.4pt 0pt 5.4pt;border-bottom-color:#ffffff;border-top-width:1pt;border-right-width:1pt;border-left-color:#ffffff;vertical-align:top;border-right-color:#ffffff;border-left-width:1pt;border-top-style:solid;border-left-style:solid;border-bottom-width:1pt;width:127.3pt;border-top-color:#ffffff;border-bottom-style:solid}.c48{border-right-style:solid;padding:0pt 5.4pt 0pt 5.4pt;border-bottom-color:#ffffff;border-top-width:0pt;border-right-width:0pt;border-left-color:#ffffff;vertical-align:top;border-right-color:#ffffff;border-left-width:0pt;border-top-style:solid;border-left-style:solid;border-bottom-width:0pt;width:536.2pt;border-top-color:#ffffff;border-bottom-style:solid}.c21{border-right-style:none;padding:0pt 5.4pt 0pt 5.4pt;border-bottom-color:#ffffff;border-top-width:1pt;border-right-width:1pt;border-left-color:#ffffff;vertical-align:top;border-right-color:#ffffff;border-left-width:1pt;border-top-style:solid;border-left-style:solid;border-bottom-width:1pt;width:134.7pt;border-top-color:#ffffff;border-bottom-style:solid}.c3{border-right-style:solid;padding:0pt 5.4pt 0pt 5.4pt;border-bottom-color:#ffffff;border-top-width:1pt;border-right-width:1pt;border-left-color:#ffffff;vertical-align:top;border-right-color:#ffffff;border-left-width:1pt;border-top-style:solid;border-left-style:solid;border-bottom-width:1pt;width:113.5pt;border-top-color:#ffffff;border-bottom-style:solid}
          
          .c38{border-right-style:none;padding:0pt 5.4pt 0pt 5.4pt;border-bottom-color:#ffffff;border-top-width:1pt;border-right-width:1pt;border-left-color:#ffffff;vertical-align:top;border-right-color:#ffffff;border-left-width:1pt;border-top-style:solid;border-left-style:solid;border-bottom-width:1pt;width:142pt;border-top-color:#ffffff;border-bottom-style:solid}.c40{border-right-style:none;padding:5.4pt 5.4pt 5.4pt 5.4pt;border-bottom-color:#ffffff;border-top-width:1pt;border-right-width:1pt;border-left-color:#ffffff;vertical-align:middle;border-right-color:#ffffff;border-left-width:1pt;border-top-style:solid;border-left-style:solid;border-bottom-width:1pt;width:113.5pt;border-top-color:#ffffff;border-bottom-style:solid}
      .c36{-webkit-text-decoration-skip:none;color:#000000;font-weight:700;text-decoration:underline;vertical-align:baseline;text-decoration-skip-ink:none;font-size:18pt;font-family:"Calibri";font-style:normal}.c1{-webkit-text-decoration-skip:none;color:#000000;font-weight:700;text-decoration:underline;vertical-align:baseline;text-decoration-skip-ink:none;font-size:16pt;font-family:"Calibri";font-style:normal}.c25{padding-top:0pt;padding-bottom:0pt;line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:left;height:24pt}.c5{padding-top:0pt;text-indent:-36pt;padding-bottom:6pt;line-height:1.0;orphans:2;widows:2;text-align:center}.c8{color:#000000;font-weight:400;text-decoration:none;vertical-align:baseline;font-size:11pt;font-family:"Gill Sans";font-style:normal}.c30{padding-top:0pt;padding-bottom:0pt;line-height:1.0;orphans:2;widows:2;text-align:left}.c0{padding-top:0pt;padding-bottom:0pt;line-height:1.0;orphans:2;widows:2;text-align:center}.c11{padding-top:0pt;padding-bottom:6pt;line-height:1.0;orphans:2;widows:2;text-align:center}.c47{font-weight:400;vertical-align:baseline;font-size:11pt;font-family:"Calibri";font-style:normal}.c2{background-color:#ffff00;font-size:18pt;font-family:"Calibri";color:#000000;font-weight:700}.c42{color:#595959;font-weight:400;font-size:18pt;font-family:"Calibri"}.c33{color:#000000;text-decoration:none;vertical-align:baseline;font-style:italic}.c19{border:none;margin-left:auto;border-spacing:0;border-collapse:collapse;margin-right:auto}.c28{color:#0070c0;font-weight:700;font-size:12pt;font-family:"Calibri"}.c45{padding-top:0pt;padding-bottom:0pt;line-height:1.15;text-align:left}.c39{-webkit-text-decoration-skip:none;color:#0000ff;text-decoration:underline;text-decoration-skip-ink:none}.c9{color:#000000;text-decoration:none;vertical-align:baseline;font-style:normal}.c43{font-size:18pt;font-family:"Calibri";font-weight:700}.c15{font-weight:400;font-size:11pt;font-family:"Calibri"}.c7{font-size:6pt;font-family:"Calibri";font-weight:700}.c20{font-weight:400;font-size:8pt;font-family:"Calibri"}.c16{font-weight:400;font-size:16pt;font-family:"Calibri"}.c13{font-weight:400;font-size:12pt;font-family:"Cambria"}.c35{background-color:#ffffff;max-width:538.6pt;padding:28.3pt 28.3pt 28.3pt 28.3pt}.c31{font-weight:700;font-size:14pt;font-family:"Calibri"}.c44{font-weight:700;font-size:9pt;font-family:"Calibri"}.c10{font-weight:700;font-size:10pt;font-family:"Calibri"}.c24{text-decoration:none;vertical-align:baseline;font-style:normal}.c17{font-weight:400;font-size:10pt;font-family:"Calibri"}.c14{font-weight:400;font-size:6pt;font-family:"Calibri"}.c26{font-weight:400;font-size:8pt;font-family:"Cambria"}.c34{font-weight:700;font-size:12pt;font-family:"Calibri"}.c22{font-weight:700;font-size:9pt;font-family:"Calibri"}.c32{margin-left:-7.1pt;text-indent:7.1pt}.c27{color:inherit;text-decoration:inherit}.c12{height:11pt}.c37{text-indent:-36pt}.c23{background-color:#ffff00}.c41{page-break-after:avoid}.c46{color:#0070c0}.c29{height:66pt}.c4{height:0pt}.c50{height:34pt}.title{padding-top:24pt;color:#000000;font-weight:700;font-size:36pt;padding-bottom:6pt;font-family:"Gill Sans";line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:left}.subtitle{padding-top:18pt;color:#666666;font-size:24pt;padding-bottom:4pt;font-family:"Georgia";line-height:1.0;page-break-after:avoid;font-style:italic;orphans:2;widows:2;text-align:left}li{color:#000000;font-size:11pt;font-family:"Gill Sans"}p{margin:0;color:#000000;font-size:11pt;font-family:"Gill Sans"}h1{padding-top:0pt;color:#000000;font-weight:700;font-size:24pt;padding-bottom:0pt;font-family:"Times New Roman";line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:center}h2{padding-top:18pt;color:#000000;font-weight:700;font-size:18pt;padding-bottom:4pt;font-family:"Gill Sans";line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:left}h3{padding-top:0pt;color:#000000;font-size:16pt;padding-bottom:0pt;font-family:"Times New Roman";line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:center}h4{padding-top:12pt;color:#000000;font-weight:700;font-size:12pt;padding-bottom:2pt;font-family:"Gill Sans";line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:left}h5{padding-top:11pt;color:#000000;font-weight:700;font-size:11pt;padding-bottom:2pt;font-family:"Gill Sans";line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:left}h6{padding-top:10pt;color:#000000;font-weight:700;font-size:10pt;padding-bottom:2pt;font-family:"Gill Sans";line-height:1.0;page-break-after:avoid;orphans:2;widows:2;text-align:left}</style>
   </head>
   <body class="c35"> 
      <div>
      
         <table>
            <tbody>
               <tr>
                  <td colspan="1" rowspan="1">
                   
                     <table>
                        <tbody>
                           <tr> 
                              <td colspan="1" rowspan="1">
                               
                                 <table class="c19">
                                    <tbody>
                                       <tr class="c4">
                                          <td colspan="1" rowspan="1">
                                          <table>
                                          <tr>
                                          <td>
                                           <img alt="" src="/wp-content/uploads/2019/12/image5.png" style="width: 170px; height: 29px; margin-left: 0.00px; margin-top: 0.00px; transform: rotate(0.00rad) translateZ(0px); -webkit-transform: rotate(0.00rad) translateZ(0px);" title="">


</td>
<td align="right">
<img alt="" src="/wp-content/uploads/2019/12/image1.png" style="width: 150px; height: 45px; margin-left: -0.00px; margin-top: -0.00px; transform: rotate(0.00rad) translateZ(0px); -webkit-transform: rotate(0.00rad) translateZ(0px);" title="">

</td>
                                          </tr>
                                          </table>
                                             <span class="c39 c47" style="text-align:center;"><a class="c27" href="http://www.escp.eu.com">www.escp.eu.com</a></span>
                                          </td>
                                       </tr>
                                       <tr class="c29">
                                          <td colspan="1" rowspan="1">
                                             <p class="c0"><span class="c7"><b>Executive Committee:</b></span><br/>
  <span class="c7"><b>President: </b></span><span class="c14">Prof Willem Bemelman</span><span class="c26">&nbsp;</span><span class="c7"><b>President Elect:</b> </span><span class="c14">Prof Evangelos Xynos</span><span class="c7">&nbsp;<b>President in Waiting:</b></span><span class="c14">&nbsp;Prof Klaus Matzel</span><br/>
                                          <span class="c7"><b>Immediate Past President:</b> </span><span class="c14">Prof Per J Nilsson</span><span class="c7">&nbsp;<b>Secretary:</b></span><span class="c14">&nbsp;Prof Gabriela M&ouml;slein</span><span class="c7">&nbsp;<b>Ass. Secretary:</b> </span><span class="c14">Prof. Antonino Spinelli </span><span class="c7"><br/><b>Treasurer:</b> </span><span class="c14">Dr Miguel Pera</span>
                                             <span class="c7"><b>Communications Committee Chair:</b></span><span class="c14">&nbsp;Mr</span><span class="c7">&nbsp;</span><span class="c14">Richard Brady </span><span class="c7"><b>Education Committee Chair</b></span><span class="c14">:</span><span class="c7">&nbsp;</span><span class="c14">Prof Dieter Hahnloser </span><span class="c7"><b>Guidelines</b></span><span class="c14">&nbsp;</span><span class="c7"><b>Committee Chair:</b> </span><span class="c14">Miss Carolynne Vaizey </span><span class="c7"><b>Ass. Guidelines Chair:</b></span><span class="c14">&nbsp;St&eacute;phanie Breukink </span><span class="c7"><b>Journal Representative:</b></span><span class="c14">&nbsp;Mr Baljit Singh </span><span class="c7"><b>Membership Committee Chair:</b></span><span class="c14">&nbsp;Dr Roland Scherer </span><span class="c7"><b>Programme Committee Chair</b></span><span class="c14">: Prof Des Winter </span><span class="c7"><b>Research Committee Chair:</b> </span><span class="c14">Prof Charles Knowles </span><span class="c7"><b>Young</b></span><span class="c14">&nbsp;</span><span class="c7"><b>ESCP Working Group Chair:</b></span><span class="c14">&nbsp;Dr Matteo Frasson </span>
                                             <br/>
                                             <span class="c7"><b>UEMS Representative:</b> </span>
                                             <span class="c14">Dr David Zimmerman</span></p>
                                          </td>
                                       </tr>
                                    </tbody>	
                                 </table>
                               
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  
                  </td>
               </tr>
            </tbody>
         </table>
       
      </div>
   
      <h1 class="c0 c41"><span class="c36"><b>CERTIFICATE OF COMPLETION</b></span></h1>
     
      <p class="c0"><span class="c9 c15">This is to certify that:</span>
       <br/><br/>
      <span class="c43">'.$username.'</span>
      <br/><br/>
      <span class="c9 c15">_____________________________________________</span>
       <br/><br/> 
      <span class="c9 c15">completed the online educational course for the EAGLE Study</span>
      <br/><br/>
      <span class="c24 c28"><b>EAGLE</b></span><span class="c9 c34"><b>: ESCP sAfe-anastomosis proGramme in colorectaL surgEry</b></span>
      <br/>
      <span class="c33 c15">An international, cluster randomised-sequence study of a&nbsp;&lsquo;Safe-anastomosis&rsquo;&nbsp;Quality Improvement Intervention to reduce anastomotic leak following right colectomy and ileocaecal resection</span></p>
   
      <p class="c0">  <span class="c9 c15">On: '.$certificate_date.'</span></p>
   
      <table class="c19">
         <tbody>
            <tr class="c4">
               <td class="c40" colspan="1" rowspan="1">
               <p>
                  <span>
                  <img alt="" src="/wp-content/uploads/2019/12/image2-e1575528479957.jpg" style="width: 294px; height: 109x; margin-left: -0.00px; margin-top: -0.00px; transform: rotate(0.00rad) translateZ(0px); -webkit-transform: rotate(0.00rad) translateZ(0px);" title="">
                  </span>
                  </p>
               </td>
               <td class="c49" colspan="1" rowspan="1">
                  <p class="c11"><span>
                  <img alt="" src="/wp-content/uploads/2019/12/image4-e1575528894138.png" style="width: 160px; height: 73px; margin-left: -0.00px; margin-top: -0.00px; transform: rotate(0.00rad) translateZ(0px); -webkit-transform: rotate(0.00rad) translateZ(0px);" title=""></span></p>
               </td>
               <td class="c38" colspan="1" rowspan="1">
                  <p class="c11"><span>
                  <img alt="" src="/wp-content/uploads/2019/12/signature4-e1576582345254.png" style="width: 100px; height: 48.96px; margin-left: -0.00px; margin-top: -0.00px; transform: rotate(0.00rad) translateZ(0px); -webkit-transform: rotate(0.00rad) translateZ(0px);" title=""></span></p>
               </td>
               <td class="c21" colspan="1" rowspan="1">
                  <p class="c11"><span>
                  <img alt="" src="/wp-content/uploads/2019/12/image6.jpg" style="width: 100px; height: 58.18px; margin-left: -0.00px; margin-top: -0.00px; transform: rotate(0.00rad) translateZ(0px); -webkit-transform: rotate(0.00rad) translateZ(0px);" title=""></span></p>
               </td>
            </tr>
            <tr class="c4">
               <td class="c3" colspan="1" rowspan="1" align="center">
                 <span class="c9 c10"><b>Prof. Dion Morton</b></span><br/>
                 <span class="c17">Chief Investigator</span>
               </td>
               <td class="c6" colspan="1" rowspan="1" align="center">
                 <span class="c9 c10"><b>Prof. Charles Knowles</b></span><br/>
                 <span class="c17">Research Committee Chair</span>
               </td>
               <td class="c38" colspan="1" rowspan="1" align="center">
                  <span class="c9 c10"><b>Prof. Gabriela M&ouml;slein </b></span><br/>
                  <span class="c17">ESCP Secretary</span>
               </td>
               <td class="c21" colspan="1" rowspan="1" align="center">
               
                  <span class="c9 c10" ><b>Antonino Spinelli</b></span><br/>
                  <span class="c9 c17" >ESCP Ass. Secretary</span>
               </td>
            </tr>
         </tbody>
      </table>
      
      <p class="c0">
      <span class="c24 c28"><b>EAGLE</b></span><span class="c9 c34"><b>: ESCP sAfe-anastomosis proGramme in colorectaL surgEry</b></span>
      <br/>
      <span class="c9 c15" style="display:inline-block;padding-top:10px;margin-top:10px;">is Accredited by the Royal College of Surgeons of England for 3 CPD points.</span> <br/><br/><br/></p>
    
      <span> 
      
         <h3 class="c0 c41"><span class="c9 c22">ESCP Secretariat: c/o Integrity International Events Ltd</span></h3>
         <p class="c0"><span class="c9 c20">The Coach House, 7 St Alban&rsquo;s Road, Edinburgh, EH9 2PA</span>
         <br/> 
        <span class="c20">T: +44 (0) 131 624 6040 &nbsp;F: +44 (0) 131 624 6045 &nbsp;E: </span><span class="c20 c39"><a class="c27" href="mailto:info@escp.eu.com">info@escp.eu.com</a></span></p>
      </span>
   </body>
</html> 
 
 

';


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $htmlCustom, '', 1, '', true, '', true);
//$pdf->writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
// output the HTML content

//$pdf->writeHTML($htmlCustom, true, true, true, true, '');// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
//$upload_dir = wp_upload_dir();
//echo $upload_dir['baseurl'];
//ob_clean();
//ob_flush();
$pdf->Output("escp_certificate.pdf", 'D');

}
?>
