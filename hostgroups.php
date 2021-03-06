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
$columns      = (isset($_GET['columns'])) ? str_replace(","," ",$_GET['columns']) : str_replace(","," ",$fields_hostgroups) ;
$columns_array = (isset($_GET['columns'])) ? explode(',',$_GET['columns']) : explode(',',$fields_hostgroups);
$output_format = (isset($_GET['output_format'])) ? $_GET['output_format'] : $output_format;

$query = "GET hostgroups\nColumns: $columns\n";


$erg = $livestatus->query($query);

if($output_format == "smarty")
{
   $output->smarty($erg,$columns_array,"hostgroups.html");
}elseif($output_format == "json")
{
   $output->json($erg,$columns_array);
}