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

class livestatus
{
  private $sockets;
  private $open_sockets = array();
  private $auth_user;
  public function __construct($sockets)
  {
     global $cfg;
     $this->auth_user = $cfg['env_user'];
     $this->sockets = $sockets;
  }
   
   function __destruct()
   {
     foreach($this->open_sockets AS $socket)
     {
       fclose($socket);
     }
   }
   
   private function connectSocket($path,$port,$timeout) 
   {
      return fsockopen($path, $port, $errid, $errstr, $timeout);
   }
   
   public function query($query)
   {
      $return = "";

      foreach($this->sockets AS $entries)
      {
        $open_sockets[$entries['path']] = $this->connectSocket($entries['path'],$entries['port'],$entries['timeout']);
        $return .= $this->queryDo($open_sockets[$entries['path']],$query,$entries['auth']);
      }
      
      //remove last newline
       $return = substr($return,0, -1);
      return explode("\n",$return);
   }
    
   private function queryDo($socket,$query,$auth) 
   {
     $query  .= ($auth === TRUE) ? "AuthUser: $this->auth_user\n" :"";   
     $query  .= "ColumnHeaders:off\nOutputFormat:csv\nKeepAlive: on\nResponseHeader: fixed16\n\n";
     
     if(isset($_GET['debug_show_query']))
     {
       echo "<pre class=\"debug\">$query</pre>";
     }
     $write 	= fwrite($socket, $query);   
     $read  	= $this->readSocket($socket,16);
     $status 	= substr($read, 0, 3);
     if($status == 200)
     {
       $len 	= intval(trim(substr($read, 4, 11)));
       $read 	= $this->readSocket($socket,intval($len));
       return $read;
     }
     //empty string
     return "";
    }
    
    /**
    * Function taken from Nagvis
    */
    private function readSocket($socket,$len) 
    {
      $offset 		= 0;
      $socketData 	= '';
      while($offset < $len) 
      {
        if(($data = @fread($socket, $len - $offset)) === false) 
          return false;
        
        if(($dataLen = strlen($data)) === 0) 
          break;
          
        $offset += $dataLen;
        $socketData .= $data;
       }
       return $socketData;
     }
     
     public function renderOutput($input,$colum_array)
     {
       foreach($input AS $index => $value)
       {
         $sub = array();
         foreach(explode(";",$value) AS $id=>$val)
         {
           $sub[$colum_array[$id]] =  $val;
         }
         $input[$index] = $sub;
       }
      
      return $input;
     }

    
    
}