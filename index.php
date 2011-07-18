<?php
# +-----------------------------------------------------------------+
# |                                                                 |
# |        (  ___ \     | \    /\|\     /||\     /|( (    /|        |
# |        | (   ) )    |  \  / /| )   ( || )   ( ||  \  ( |        |
# |        | (__/ /     |  (_/ / | |   | || (___) ||   \ | |        |
# |        |  __ (      |   _ (  | |   | ||  ___  || (\ \) |        |
# |        | (  \ \     |  ( \ \ | |   | || (   ) || | \   |        |
# |        | )___) )_   |  /  \ \| (___) || )   ( || )  \  |        |
# |        |/ \___/(_)  |_/    \/(_______)|/     \||/    )_)        |
# |                                                                 |
# | Copyright Bastian Kuhn 2011                mail@bastian-kuhn.de | 
# +-----------------------------------------------------------------+
#
# NagUI more information: http://nagui.de
#
# This is free software;  you can redistribute it and/or modify it
# under the  terms of the  GNU General Public License  as published by
# the Free Software Foundation in version 2.  NagUI is  distributed
# in the hope that it will be useful, but WITHOUT ANY WARRANTY;  with-
# out even the implied warranty of  MERCHANTABILITY  or  FITNESS FOR A
# PARTICULAR PURPOSE. See the  GNU General Public License for more de-
# ails.  You should have  received  a copy of the  GNU  General Public
# License along with GNU Make; see the file  COPYING.  If  not,  write
# to the Free Software Foundation, Inc., 51 Franklin St,  Fifth Floor,
# Boston, MA 02110-1301 USA.

require("./includes/header.inc.php");

$host_query = "GET hosts\nStats: state >= 0\nStats: state > 0\nStats: scheduled_downtime_depth = 0\nStatsAnd: 2\nStats: state > 0\nStats: scheduled_downtime_depth = 0\nStats: acknowledged = 0\nStatsAnd: 3\n";

$service_query = "GET services\nStats: state >= 0\nStats: state > 0\nStats: scheduled_downtime_depth = 0\nStats: host_scheduled_downtime_depth = 0\nStats: host_state = 0\nStatsAnd: 4\nStats: state > 0\nStats: scheduled_downtime_depth = 0\nStats: host_scheduled_downtime_depth = 0\nStats: acknowledged = 0\nStats: host_state = 0\nStatsAnd: 5\n";

$output_format = (isset($_GET['output_format'])) ? $_GET['output_format'] : $output_format;

$livestatus = new livestatus($sockets);

$erg_hosts = $livestatus->query($host_query);

$array['host_total']     = 0;
$array['host_down']      = 0;
$array['host_unhandled'] = 0;


foreach($erg_hosts AS $lines)
{
  
  $array['host_total']     = $array['host_total'] + $lines[0];
  $array['host_down']      = $array['host_down'] + $lines[1];
  $array['host_unhandled'] = $array['host_unhandled'] + $lines[2];
}


$erg_services = $livestatus->query($service_query);
$array['service_total']     = 0;
$array['service_crit']      = 0;
$array['service_unhandled'] = 0;

foreach($erg_services AS $lines)
{
  $array['service_total']     = $array['service_total'] + $lines[0];
  $array['service_crit']      = $array['service_crit'] + $lines[1];
  $array['service_unhandled'] = $array['service_unhandled'] + $lines[2];
}

if($output_format == "smarty")
{
   $output->smartyDirect($array,"index.html");
}elseif($output_format == "json")
{
   $output->json($array,FALSE);
}
