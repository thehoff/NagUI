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

class output
{
  private $template;
  
  public function __construct($template)
  {
    $this->template = $template;
  }
  
  public function smarty($data,$columns,$template_file)
  {
     $smarty = $this->smarty_header();
     $smarty->assign('fields', $this->renderOutput($data,$columns));
     $smarty->display($template_file);
  }
  
  public function smartyDirect($data,$template_file)
  {

    $smarty = $this->smarty_header();
    $smarty->assign($data);
    $smarty->display($template_file);
  
  }
  
  private function smarty_header()
  {
    global $cfg;
    require_once('./ext/smarty/Smarty.class.php');
    $smarty = new Smarty;
    $smarty->template_dir = "./templates/$this->template/";
    $smarty->compile_dir  = "./templates/$this->template/cache/";
    $smarty->caching  = false;
    $smarty->assign('logged_in_user', $cfg['env_user']);
    $smarty->assign('plugins', $cfg['plugins']);
    
    return $smarty;
  
  }
  
  public function json($data,$columns)
  {
    header('Content-type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  
    if($columns)
    {
      echo json_encode($this->renderOutput($data,$columns));
    }else
    {
      echo json_encode($data);
    }
  }
   
  private function renderOutput($input,$columns)
  {
    $columns[] = "sitename";
    foreach($input AS $index => $value)
    {
      $sub = array();
      foreach($value AS $id=>$val)
      {
        $sub[$columns[$id]] =  $val;
      }
      $input[$index] = $sub;
    }
    return $input;
  }   
}