<?php

class livestatus_report extends report {

  private $site;
  private $filter_time;
  private $filter_warnings = "Filter: state = 1\nOr: 2\n";
  
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
    return array("av" => 10, "stddev" => 20);
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
//     $query .= "Stats: min time\n"; # Did not work with livestatus yet
//     $query .= "Stats: max time\n"; # Did not work with livestatus yet
    $query .= "StatsGroupBy: $group2\n";
//     print "<pre>$query</pre>";
    $columns = array($group,"n_events");
    
    $query_out = $output->renderOutput($livestatus->query($query),$columns);
    $sort_array = array();
    foreach($query_out AS $key => $inhalt)
    {
       $sort_array[$key] = $inhalt['n_events'];
       
    }
    
    array_multisort($sort_array,SORT_NUMERIC,SORT_DESC,$query_out);
    return $query_out;

    
  }

}


?>