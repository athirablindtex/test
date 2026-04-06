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
        echo "sssss";
        exit;
         
          $this->connection = ftp_connect($this->ftp_server) or die("❌ Cannot connect");

$login_result = ftp_login($this->connection, $this->ftp_user, $this->ftp_password) or die("❌ Cannot login");

ftp_pasv($this->connection, true);

// 👇 Show debug FTP status and system
echo "<pre>";
echo "📡 FTP System Info:\n";
print_r(ftp_raw($this->connection, "SYST"));

echo "\n📊 FTP Status:\n";
print_r(ftp_raw($this->connection, "STAT"));

echo "\n📦 FTP Passive Mode Info (PASV):\n";
print_r(ftp_raw($this->connection, "PASV"));
echo "</pre>";

// Your file logic
$myfile = fopen($local_source_file, "r") or die("Unable to open file!");

echo "remote destination file = " . $remote_destination_file . "<br/>";
echo "local source file = " . $local_source_file . "<br/>";

if (ftp_put($this->connection, $remote_destination_file, $local_source_file, FTP_ASCII)) {
    return "✅ Successfully uploaded $remote_destination_file\n";
} else {
    echo "<pre>🛠 Debug: ";
    print_r(ftp_raw($this->connection, "STAT"));
    echo "</pre>";
    return "❌ There was a problem while uploading $remote_destination_file\n";
}

ftp_close($this->connection);

     }
     
     function test(){
         return "reched FTP Library . FTP SERVER =".$this->ftp_server;
     }
     
}
 