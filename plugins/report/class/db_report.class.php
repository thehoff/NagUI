<?php

class db_report extends report {

  private $db;
  private $table;
  private $query_where = "";
  
  function __construct($db,$table)
  {
     $this->db = $db;
     $this->table = $table;
  }

  
  public function getReportYears()
  {
    
     $query      = "SELECT DISTINCT(YEAR(zeit)) AS wert FROM  $this->table ORDER BY wert";
     $results    = mysql_query($query,$this->db);
     return mysql_fetch_array($results,MYSQL_NUM);
  }
  
  
  public function setByYear($year)
  {
    $this->query_where = " YEAR(zeit) = '$year'";
  
  }
  
  public function setByTimeperiod($period)
  {
    switch($period)
    {
      case "lastyear":
        $this->query_where   = " TIMESTAMPDIFF(MONTH,zeit,NOW()) < 12";
      break;
      
      case "last6months":
        $this->query_where   = " TIMESTAMPDIFF(MONTH,zeit,NOW()) < 6";
      break;
      
      case "lastmonth":
        $this->query_where   = " TIMESTAMPDIFF(DAY,zeit,NOW()) < 31";
      break;
      
      case "last2weeks":
        $this->query_where   = " TIMESTAMPDIFF(DAY,zeit,NOW()) < 14";
      break;
      
      case "lastweek":
        $this->query_where   = " TIMESTAMPDIFF(DAY,zeit,NOW()) < 7";
      break;
      
      case "24hours":
        $this->query_where   = " TIMESTAMPDIFF(HOUR,zeit,NOW()) < 24";
      break;
    }
  }
  
  public function setNoSoftStats()
  {
     $this->query_where      .= " AND status_type = 'HARD'";
  }
  
  public function setNoWarnings()
  {
      $this->query_where     .= " AND status != 'WARNING'";
  }
  
  
  private function getReportCount()
  {
     $query     = "SELECT COUNT(*) AS count FROM $this->table WHERE $this->query_where AND status != 'OK'";
     $res       = mysql_query($query,$this->db);
     $count = mysql_fetch_object($res);
     return $count->count;
  
  }
  
  public function getAvg($grouping)
  {
    $result     = mysql_query("SELECT 
                                AVG(after_where.nr) AS avg, 
                                STDDEV(after_where.nr) AS stddev 
                               FROM (SELECT COUNT(*) AS nr FROM $this->table WHERE $this->query_where AND status != 'OK' GROUP BY $grouping) after_where",$this->db);
    return mysql_fetch_array($result,MYSQL_ASSOC);
  }
  
  
  public function getEntries($grouping)
  {
      $total = $this->getReportCount();
      $query  = "SELECT $grouping, 
                    COUNT(*) AS n_events, 
                    MIN(DATE_FORMAT(zeit,'%d.%m.%Y %H:%i')) AS first, 
                    MAX(DATE_FORMAT(zeit,'%d.%m.%Y %H:%i')) AS last,
                    ((COUNT(*) * 100.0) / $total) AS percentage
                  FROM $this->table WHERE  $this->query_where AND status != 'OK' GROUP BY $grouping ORDER BY n_events DESC";
                                   
     $results       = mysql_query($query,$this->db);
     return mysql_fetch_array($results,MYSQL_ASSOC);
  }
}


?>