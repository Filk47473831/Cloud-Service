<?php 
/**
 * Cloud Portal Connection Functions.
 * Version 1.0.
 *
 * @author    Chris Groves <chris@thegaff.co.uk>
 * @copyright 2019 Chris Groves
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

function pscmd($commands, $hostTarget = "JSPC-CS1") {

    if(isset($_SESSION['hostTarget'])) { $hostTarget = $_SESSION['hostTarget']; }
    $commandFile = generateRandomString(16) . '.ps1';
    $handle = fopen('..\\..\\..\\Scripts\\' . $commandFile, 'w') or die();
    $data = '
    $password = Get-Content C:\xampp\htdocs\Passwords\\' . $hostTarget . '.txt | ConvertTo-SecureString -Key (Get-Content C:\xampp\htdocs\Passwords\Host.key)
    $credential = New-Object System.Management.Automation.PsCredential("' . $hostTarget . '\Administrator", $password)
    ';

    $data .= 'Invoke-Command -Computername "' . $hostTarget . '" -ScriptBlock { ';

    foreach($commands as $command) {
        $data .= ' ' . $command . ' 2>&1; ';
    }

    $data .= ' } -Credential $credential | ConvertTo-Json';

    fwrite($handle, $data);
    fclose($handle);

    $output = shell_exec(escapeshellcmd('PowerShell.exe -ExecutionPolicy Bypass -File ..\\..\\..\\Scripts\\' . $commandFile));

    unlink('..\\..\\..\\Scripts\\' . $commandFile);

    return $output;
    
}

function sshcmd($commands,$target,$password,$username = "vyos"){
  
    require_once('ssh/Math/BigInteger.php');
    require_once('ssh/Crypt/AES.php');
    require_once('ssh/Crypt/Random.php');
    require_once('ssh/Crypt/Hash.php');
    require_once('ssh/Crypt/RC4.php');
    require_once('ssh/Crypt/Twofish.php');
    require_once('ssh/Crypt/Blowfish.php');
    require_once('ssh/Crypt/TripleDES.php');
    require_once('ssh/Net/SSH2.php');
  
    $fullOutput = "";
  
    $ssh = new Net_SSH2($target, 22);
    if (!$ssh->login($username, $password)) {
        exit('Login Failed');
    }
  
    if($username == "vyos") {
  
      $ssh->setTimeout(1);
      $ssh->write("set terminal length 0\n");
      $ssh->read('$');

      foreach($commands as $command){
          $ssh->write($command . "\n");
          if($command == "commit" || $command == "save") { sleep(20); }
          $fullOutput .= $ssh->read('vyos@vyos#');
      }
      
    } else {
      
      foreach($commands as $command){
              $fullOutput .= $ssh->exec($command); 
      }
      
     $fullOutput .= $ssh->exec('ls');
      
    }
  
  return $fullOutput;

}

function escapeOutput($string) {
     
    return htmlspecialchars($string);
      
}

$db['db_host'] = $_ENV['mysql_DB_Host'];
$db['db_username'] = $_ENV['mysql_DB_Username'];
$db['db_password'] = $_ENV['mysql_DB_Password'];
$db['db_database'] = $_ENV['mysql_DB_Name'];
$db['db_port'] = $_ENV['mysql_DB_Port'];

foreach($db as $key => $value){
    
    define(strtoupper($key), $value);
    
}

$connection = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE,DB_PORT);

?>