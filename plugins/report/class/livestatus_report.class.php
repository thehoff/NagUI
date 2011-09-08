<?php

class livestatus_report extends report {

  private $site;
  private $filter_time;
  private $filter_warnings = "Filter: state = 1\nOr: 2\n";
  private $avg;
  private $devation;
  
  public function __construct($site)
  {
     $this->site = $site;
  }

  public function getReportYears()
  { 
    global $livestatus;
//     $livestatus->query("GET HOSTS\n");
    return false;
  }
  
  public function setByTimeperiod($period)
  {	  
     switch($period)
      {
// 	case "lastyear":
//           $year = (int)date("Y");
// 	  $year = $year - 1970;
// 	  $year = $year * 31536000;
// 	  $diff   = $year;
// 	break;
// 	
// 	case "last6months":
// 	   $diff   = time() - 86400;
// 	break;
// 	
// 	case "lastmonth":
// 	   $diff   = time() - 86400;
// 	break;
// 	
// 	case "last2weeks":
// 	   $diff   = time() - 86400;
// 	break;
// 	
// 	case "lastweek":
// 	   $diff   = time() - 86400;
// 	break;
	
	case "24hours":
	  $diff   = time() - 86400;
	break;
	
	default:
	   $diff   = time() - 86400;
	break;
      }
   $this->filter_time = "Filter: time > $diff\n";

  }
  
  
  public function getAvg($group)
  {
    //Wurde bereits in getEntries berechnet
    return array("avg" => $this->avg, "stddev" => $this->devation);
  }
  
  
  public function setNoWarnings()
  {
    $this->filter_warnings = "";
  }
  
  public function getEntries($group)
  {
    global $livestatus;
    global $output;
    
    switch($group)
    {
       case "host":
         $group2 = "host_name";
       break;
       
       case "service":
         $group2 = "service_description";
       break;
    }
    
    $query = "GET log\n";
    $query .=  $this->filter_time;
    $query .= "Filter: state = 2\n";
    $query .= $this->filter_warnings;
    
    $query .= "Stats: state != 9999\n";
    $query .= "Stats: min time\n"; # Did not work with livestatus yet
    $query .= "Stats: max time\n"; # Did not work with livestatus yet
    $query .= "StatsGroupBy: $group2\n";
    $columns = array($group,"n_events","first","last");
    
    $query_out = $output->renderOutput($livestatus->query($query,$this->site),$columns);
    
   
    $sort_array = array();
    $total_events = 0;
    $total_host   = 0;
    
    //Vorbereiten um das Array zu Sortieren
    foreach($query_out AS $key => $inhalt)
    {
       $sort_array[$key] = $inhalt['n_events'];
       //Schleife nutzen um die Anzahl der events zu addieren
       $total_events = $total_events + $inhalt['n_events'];
       $total_host++;
    }
    //Array Sortieren
    array_multisort($sort_array,SORT_NUMERIC,SORT_DESC,$query_out);
    
    //Prozentsatz ausrechnen
    foreach($query_out AS $key => $inhalt)
    {
       $var = $inhalt['n_events'] * 100;
       $inhalt['percentage'] =  $var / $total_events;
       $query_out[$key] = $inhalt;
    }
    
    //Schnitt der Fehler pro Hosts rechnen
    $this->avg = $total_host/$total_events;
    $this->devation = sd($sort_array);

    return $query_out;

    
  }
   

}


?>