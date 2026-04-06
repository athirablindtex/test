 <?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ediftp{
     
     private $ftp_server;
     private $ftp_user;
     private $ftp_password;
     private $connection;
     
      function __construct($server = "innov8.blindata.co.uk",$user="blindtexinno",$pass="aO_hGAU13di"){
         
         $this->ftp_server =$server;
         $this->ftp_user =$user;
         $this->ftp_password =$pass;
     }
     
      function upload_file($local_source_file,$remote_destination_file){
         
          $this->connection = ftp_connect($this->ftp_server) or die("cannot connect");
            $login_result = ftp_login($this->connection, $this->ftp_user, $this->ftp_password)or die("cannot login");
            ftp_pasv($this->connection, true);
            
            $myfile = fopen($local_source_file,"r")or die("Unable to open file!");
           // echo "remote destination file  = " .$remote_destination_file."<br/>";
          // echo "local sorce file  = " .$local_source_file."<br/>";
         if (ftp_put($this->connection, $remote_destination_file,$local_source_file , FTP_ASCII)) {
            return "successfully uploaded $remote_destination_file\n";
         } else {
            return "There was a problem while uploading11 $remote_destination_file\n";
           
            }
         // close the connection
         ftp_close($this->connection);
         //fclose($myfile);
     }
     
     function test(){
         return "reched FTP Library . FTP SERVER =".$this->ftp_server;
     }
     
}
 