<?php



//Variablen Vorbereiten
 $time = time();
 $assign = array();
require('header.inc.php');

 $assign["db_sources"] = $cfg_reporting_dbs;

//Wird mit Hosts oder Services gearbeitet
 $assign['what'] = (isset($_GET['was'])) ? $_GET['was'] : "host";
 
 // Verfügbare Zeiträume auslesen
 $assign['jahre'] = $report->getReportYears();




if(isset($_GET['nach_Jahr']))
{
  $assign['nach_jahr'] = $_GET['jahr'];
  $report->setByYear($assign['nach_jahr']);
}elseif(isset($_GET['Zeitraum']))
{
  $assign["nach_zeitraum"] = $_GET['last'];
  
  $report->setByTimeperiod($_GET['last']);
}else
{ 
 $report->setByTimeperiod("24hours");

}
 
//Status Filter für Ausgabe auf GUI
 if(isset($_GET['soft_stats']))
 {
   $assign['soft_stats'] = "checked";
 }else
 {
   $report->setNoSoftStats();
   $assign['soft_stats'] = "";
 }


 if(isset($_GET['warnings']))
 {
    $assign['warning_stats'] = "checked"; 
 }else
 {
   $report->setNoWarnings();
   $assign['warning_stats'] = "";
 }
 
  $what 		= $assign['what'];
 
 
   
 $as_wert = $report->getAvg($what);
 

 



  $eintraege = array();
  $entries = $report->getEntries($what);
//   echo "<pre>";
//   var_dump($entries);
//   echo "</pre>";
  if(is_array($entries[0]))
  {
      foreach($entries AS $row)
      {
        $row['color'] = get_color($row["n_events"], $as_wert["avg"], $as_wert["stddev"]);
        $eintraege[] = $row;
      }
  }else
  {
     $entries['color'] = get_color($entries["n_events"], $as_wert["avg"], $as_wert["stddev"]);
     $eintraege[] = $entries;
  }
  
 
  
  $assign['eintraege'] = $eintraege;
  $assing['java_script'] = "/plugins/report/system.js";
  
  $output->smartyDirect($assign,'plugins/report/index.html');
  

  function get_color($n, $avg, $stddev)
  {
//       print "$n:$avg:$stddev<br>";
	if ($n >= ($avg + $stddev))
		return " BGCOLOR='#ff6633'";

	if ($n >= $avg)
		return " BGCOLOR='#ffff99'";

	if ($n < ($avg - $stddev))
		return " BGCOLOR='#95FF7A'";
	
	return '';
  }

?>