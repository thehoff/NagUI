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
		
		$query      = "SELECT DISTINCT(YEAR(event_time)) AS wert FROM  $this->table ORDER BY wert DESC";
		$results    = mysql_query($query,$this->db);
		$return 	= array();
		while($erg = mysql_fetch_object($results))
		{
			$return[] = $erg->wert;
		}
		return $return;
	}
	
	
	public function setByYear($year)
	{
		$this->query_where = " YEAR(event_time) = '$year'";
	
	}
  
	public function setByTimeperiod($period)
	{
		switch($period)
		{
			case "lastyear":
				$this->query_where   = " TIMESTAMPDIFF(MONTH,event_time,NOW()) < 12";
			break;
			
			case "last6months":
				$this->query_where   = " TIMESTAMPDIFF(MONTH,event_time,NOW()) < 6";
			break;
			
			case "lastmonth":
				$this->query_where   = " TIMESTAMPDIFF(DAY,event_time,NOW()) < 31";
			break;
			
			case "last2weeks":
				$this->query_where   = " TIMESTAMPDIFF(DAY,event_time,NOW()) < 14";
			break;
			
			case "lastweek":
				$this->query_where   = " TIMESTAMPDIFF(DAY,event_time,NOW()) < 7";
			break;
			
			case "24hours":
				$this->query_where   = " TIMESTAMPDIFF(HOUR,event_time,NOW()) < 24";
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
	
	
	private function getReportCount($field=False, $item=False)
	{	
		$where = ($field) ? "AND $field = '$item'" : "" ;
		
		$query     = "SELECT COUNT(*) AS count FROM $this->table WHERE $this->query_where $where AND status != 'OK'";
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
						MIN(DATE_FORMAT(event_time,'%d.%m.%Y %H:%i')) AS first, 
						MAX(DATE_FORMAT(event_time,'%d.%m.%Y %H:%i')) AS last,
						((COUNT(*) * 100.0) / $total) AS percentage
					FROM $this->table WHERE  $this->query_where AND status != 'OK' GROUP BY $grouping ORDER BY n_events DESC";
		$return = array();
		$results = mysql_query($query);
		
		while($erg = mysql_fetch_array($results,MYSQL_ASSOC))
		{
			$return[] = $erg;
		}
		return $return;	
		
		
	}
	
	public function getEvents($item, $grouping)
	{
		$query = "SELECT service, host, description, COUNT(*) AS n_events FROM $this->table WHERE $this->query_where AND status != 'OK' AND $grouping = '$item' GROUP BY host, description ORDER BY n_events DESC";
        //$n = $tot_n_events = $dtot_n_events = 0;
        
        
        $return = array();
		$results = mysql_query($query);
		while($erg = mysql_fetch_array($results,MYSQL_ASSOC))
		{
			$return[] = $erg;
		}
		return $return;	
		
		//  $avg_n_events = $tot_n_events / $n;
        //echo "Error Average ".round($avg_n_events,2);
//          $tot_n_events += $row['n_events'];
//             $dtot_n_events += $row['n_events'] * $row['n_events'];
//             $n++;
	}
	
	public function getHostStats($host)
	{
		$total_n = $this->getReportCount('host',$host);
		
		$query = "SELECT service, COUNT(*) AS n_events, ((COUNT(*) * 100.0) / $total_n) AS percentage FROM $this->table 
		WHERE $this->query_where AND status != 'OK' AND host='$host' GROUP BY service";
	    $return = array();
		$results = mysql_query($query);
		while($erg = mysql_fetch_array($results,MYSQL_ASSOC))
		{
			$return[] = $erg;
		}
		return $return;	
	}
	
	public function getTimeline($item, $grouping)
	{
		$query = "SELECT host, service, status, description, DATE_FORMAT(event_time,'%d.%m.%Y %H:%i') AS time FROM $this->table 
		WHERE $this->query_where AND status != 'OK' AND $grouping = '$item'";
		
		$return = array();
		$results = mysql_query($query);
		while($erg = mysql_fetch_array($results,MYSQL_ASSOC))
		{
			$return[] = $erg;
		}
		return $return;	
	}
}


?>