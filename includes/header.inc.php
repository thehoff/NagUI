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
# | Copyright Bastian Kuhn 2012                mail@bastian-kuhn.de | 
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

$include_path	= dirname(__FILE__)."/../";
require("${include_path}config.php");
$HEADER_INCLUDED = True;

//Read Sockets
if(!$sockets = @parse_ini_file($include_path.$nagui_conf_dir."livestatus_sockets.cfg",TRUE))
{
	$sockets = @parse_ini_file($include_path.$nagui_conf_dir."livestatus_sockets.cfg.dist",TRUE);
}

if(!$cfg['plugins'] = @parse_ini_file($include_path.$nagui_conf_dir."plugins.cfg"))
{
	$cfg['plugins'] = @parse_ini_file($include_path.$nagui_conf_dir."plugins.cfg.dist");
}


require("${include_path}class/livestatus.class.php");
require("${include_path}class/output.class.php");

$template 	= (isset($_GET['template'])) ? $_GET['template'] : $template;
$cfg 		+= parse_ini_file($include_path.$template_conf_dir.$template.".cfg",TRUE);
require("${include_path}templates/$template/defaults.php");

$livestatus     = new livestatus($sockets);
$output         = new output($template);
