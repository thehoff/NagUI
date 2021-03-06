<?php



//Variablen Vorbereiten
 $time = time();
 $assign = array();
require('header.inc.php');
 $assign["java_script"] = 'plugins/report/system.js';
 $sources = $plugin_cfg;
 unset($sources['main_cfg']);
 $assign["sources"] = $sources;
 $assign["source"]  = $source;

//Wird mit Hosts oder Services gearbeitet
 $assign['what'] = (isset($_GET['was'])) ? $_GET['was'] : "host";
 
 // Verfügbare Zeiträume auslesen
 $assign['avail_years'] = $report->getReportYears();

 $assign['for_year'] 	= '';
 $assign["timeperiod"] 	= '';
if(isset($_GET['for_year']))
{
	$assign['for_year'] = $_GET['year'];
	$report->setByYear($assign['for_year']);
}elseif(isset($_GET['Zeitraum']))
{
	$assign["timeperiod"] = $_GET['last'];
	$report->setByTimeperiod($_GET['last']);
}else
{ 
	$assign["timeperiod"] = $plugin_cfg['main_cfg']['default_time_period'];
	$report->setByTimeperiod($plugin_cfg['main_cfg']['default_time_period']);

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
 
  $what = $assign['what'];
 
 
   
 
 
  $eintraege = array();
  $entries = $report->getEntries($what);
  $as_wert = $report->getAvg($what);

	if(isset($entries[0]) && is_array($entries[0]))
    {
        foreach($entries AS $row)
        {
			$row['color'] = get_color($row["n_events"], $as_wert["avg"], $as_wert["stddev"]);
			$eintraege[] = $row;
        }
    }else
    {
        if($entries !== False)
        {
            $entries['color'] = get_color($entries["n_events"], $as_wert["avg"], $as_wert["stddev"]);
            $eintraege[] = $entries;    
        }
    }

 
  
  $assign['eintraege'] = $eintraege;
  $assing['java_script'] = "/plugins/report/system.js";
  
  $output->smartyDirect($assign,'plugins/report/index.html');
  

  function get_color($n, $avg, $stddev)
  {
	if ($n >= ($avg + $stddev))
		return " BGCOLOR='#ff6633'";

	if ($n >= $avg)
		return " BGCOLOR='#ffff99'";

	if ($n < ($avg - $stddev))
		return " BGCOLOR='#95FF7A'";
	
	return '';
  }
  
  
  //Statistik Funktionen
  function sd($array)
  {
     return sqrt(array_sum(array_map("sd_square",$array, array_fill(0,count($array), (array_sum($array) / count($array) )))) / (count($array)-1) );
  }
  
  function sd_square($x, $mean)
  {
        return pow($x - $mean,2);
  }

?>