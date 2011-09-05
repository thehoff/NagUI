<?php

class livestatus_report extends report {

  private $site;
  
  public function __construct($site)
  {
     $this->site = $site;
  }

  public function getReportYears()
  { 
    global $livestatus;
    $livestatus->query("GET HOSTS\n");
    return false;
  }
  
  public function getAvg($group)
  {
    return array("av" => 10, "stddev" => 20);
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
    
    $query = "GET log\nFilter: state = 1\nFilter: state = 2\nFilter: state = 3\nOr: 3\n";
    
    $query_exec = $query."Stats: state != 9999\nStatsGroupBy: $group2\n";
    $columns = array($group,"n_events");
    $return = $output->renderOutput($livestatus->query($query_exec),$columns);
    
    $query_exec = $query."Columns: max time\nStatsGroupBy: $group2\n";
    $columns = array($group,"last");
    $return = array_merge($return,$output->renderOutput($livestatus->query($query_exec),$columns));
    
    
    
    return $return;
    
  }

}


?>