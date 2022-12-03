<?php 
/**
 * Cloud Portal Database Functions.
 * Version 1.0.
 *
 * @author    Chris Groves <chris@thegaff.co.uk>
 * @copyright 2020 Chris Groves
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

function getUser(){
  
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT firstName, lastName, emailAddress FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['fullName'] = $row['firstName'] . " " . $row['lastName'];
            $array['emailAddress'] = $row['emailAddress'];
            }
    return $array;
    
    } else { return false; }
  
}

function removeSpecialChar($value) {
    $result  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$value);
	return $result;
}

function addVM($VMName,$VMProject,$VMNetwork,$VMImage,$VMMemory,$VMCpu,$VMPassword,$machineID){
  
    $freeMemory = json_decode(db_getHostFreeMemory(), true);
    if(894000 - getTotalDiskSpaceInUse() < 60000 || $freeMemory["value"] < 12000) { return false; }

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("INSERT IGNORE INTO vm (name, project, network, image, memory, cores, password, machineid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiiss", $VMName,$VMProject,$VMNetwork,$VMImage,$VMMemory,$VMCpu,$VMPassword,$machineID);
    if($stmt->execute()) { $lastId = $stmt->insert_id; } else { return false; }
    $stmt->close();

    addPermission($_SESSION['id'],3,$lastId);
    addLogEntry($_SESSION['id'],3,$lastId,1);

    return $lastId;
    
}

function getCurrentVMNames(){

    $VMNames = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT name FROM vm");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $VMNames[] = $row['name'];
            }
    return json_encode($VMNames);
    
    } else { return false; }

}

function addProject($projectName){

    $randomString = generateRandomString();

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("INSERT IGNORE INTO projects (name, projectid) VALUES (?, ?)");
    $stmt->bind_param("ss", $projectName, $randomString);
    if($stmt->execute()) { $lastId = $stmt->insert_id; } else { return false; }
    $stmt->close();

    addPermission($_SESSION['id'],1,$lastId);
    addLogEntry($_SESSION['id'],1,$lastId,1);

    return $lastId;
    
}

function addNetwork($network,$routerPassword,$project,$networkid,$networkIP,$lanIP,$subnetMask,$remotePublicIP,$remoteNetworkIP,$remoteSubnetMask,$psk,$l2tp_pass){
  
    $freeMemory = json_decode(db_getHostFreeMemory(), true);
    if(894000 - getTotalDiskSpaceInUse() < 10000 || $freeMemory["value"] < 6000) { return false; }

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("INSERT IGNORE INTO network (name, routerPassword, project, networkid, networkip, lanip, subnet, ipsec_remotewanip, ipsec_remotelanip, ipsec_remotesubnet, ipsec_psk, l2tp_pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssssssss", $network,$routerPassword,$project,$networkid,$networkIP,$lanIP,$subnetMask,$remotePublicIP,$remoteNetworkIP,$remoteSubnetMask,$psk,$l2tp_pass);
    if($stmt->execute()) { $lastId = $stmt->insert_id; } else { return false; }
    $stmt->close();

    addPermission($_SESSION['id'],2,$lastId);
    addLogEntry($_SESSION['id'],2,$lastId,1);

    return $lastId;
    
}

function routerInitialSetup($networkNum,$networkID,$routerPassword,$wanIP,$name,$networkIP,$subnetMask,$remotePublicIP,$remoteNetworkIP,$remoteSubnetMask,$psk,$l2tp_pass){
  
    $nextAvailableWANIP = getNextAvailableWANIP($networkNum);
    $name = cleanName($name);

    $array = [];
    array_push($array, "configure");

    array_push($array, "set firewall all-ping enable");
    array_push($array, "set firewall broadcast-ping disable");
    array_push($array, "set firewall config-trap disable");
    array_push($array, "set firewall ipv6-receive-redirects disable");
    array_push($array, "set firewall ipv6-src-route disable");
    array_push($array, "set firewall ip-src-route disable");
    array_push($array, "set firewall log-martians enable");
    array_push($array, "set firewall name OUTSIDE-LOCAL default-action drop");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 32 action accept");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 32 source address " . $remoteNetworkIP . "/" . $remoteSubnetMask);
    array_push($array, "set firewall receive-redirects disable");
    array_push($array, "set firewall send-redirects enable");
    array_push($array, "set firewall source-validation disable");
    array_push($array, "set firewall syn-cookies enable");
    array_push($array, "set firewall twa-hazards-protection disable");

    array_push($array, "delete interfaces ethernet eth0 address dhcp");
    array_push($array, "set protocols static route 0.0.0.0/0 next-hop 5.2.21.158");
    array_push($array, "set interfaces ethernet eth0 address " . $nextAvailableWANIP . "/28");
    array_push($array, "set interfaces ethernet eth1 address 172.20." . $networkIP. ".1/" . $subnetMask);

    array_push($array, "set nat source rule 10 destination address " . $remoteNetworkIP . "/" . $remoteSubnetMask);
    array_push($array, "set nat source rule 10 exclude");
    array_push($array, "set nat source rule 10 outbound-interface eth0");
    array_push($array, "set nat source rule 10 source address 172.20." . $networkIP. ".0/" . $subnetMask);
    array_push($array, "set nat source rule 100 outbound-interface eth0");
    array_push($array, "set nat source rule 100 source address 172.20." . $networkIP. ".0/" . $subnetMask);
    array_push($array, "set nat source rule 100 translation address masquerade");

    array_push($array, "set service dhcp-server shared-network-name LAN subnet 172.20." . $networkIP. ".0/" . $subnetMask . " default-router 172.20." . $networkIP. ".1");
    array_push($array, "set service dhcp-server shared-network-name LAN subnet 172.20." . $networkIP. ".0/" . $subnetMask . " dns-server 1.1.1.1");
    array_push($array, "set service dhcp-server shared-network-name LAN subnet 172.20." . $networkIP. ".0/" . $subnetMask . " dns-server 9.9.9.9");
    array_push($array, "set service dhcp-server shared-network-name LAN subnet 172.20." . $networkIP. ".0/" . $subnetMask . " domain-name Cloud-Network");
    array_push($array, "set service dhcp-server shared-network-name LAN subnet 172.20." . $networkIP. ".0/" . $subnetMask . " lease 86400");
    array_push($array, "set service dhcp-server shared-network-name LAN subnet 172.20." . $networkIP. ".0/" . $subnetMask . " range 0 start 172.20." . $networkIP. ".10");
    array_push($array, "set service dhcp-server shared-network-name LAN subnet 172.20." . $networkIP. ".0/" . $subnetMask . " range 0 stop 172.20." . $networkIP. ".200");

    array_push($array, "set firewall group network-group APPROVED_NETWORK network '5.2.21.144/28'");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 20 action 'accept'");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 20 destination port '22'");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 20 protocol 'tcp'");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 20 source group network-group 'APPROVED_NETWORK'");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 20 state new 'enable'");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 20 state established 'enable'");
    array_push($array, "set firewall name OUTSIDE-LOCAL rule 20 state related 'enable'");
    array_push($array, "set interfaces ethernet eth0 firewall local name OUTSIDE-LOCAL");

    array_push($array, "edit vpn ipsec");
    
    array_push($array, "set esp-group Cloud compression disable");
    array_push($array, "set esp-group Cloud lifetime 3600");
    array_push($array, "set esp-group Cloud mode tunnel");
    array_push($array, "set esp-group Cloud pfs disable");
    array_push($array, "set esp-group Cloud proposal 1 encryption aes128");
    array_push($array, "set esp-group Cloud proposal 1 hash sha1");
    array_push($array, "set ike-group Cloud close-action none");
    array_push($array, "set ike-group Cloud ikev2-reauth no");
    array_push($array, "set ike-group Cloud key-exchange ikev1");
    array_push($array, "set ike-group Cloud lifetime 28800");
    array_push($array, "set ike-group Cloud proposal 1 dh-group 14");
    array_push($array, "set ike-group Cloud proposal 1 encryption aes256");
    array_push($array, "set ike-group Cloud proposal 1 hash sha256");
    array_push($array, "set ipsec-interfaces interface eth0");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " authentication mode pre-shared-secret");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " authentication pre-shared-secret " . $psk);
    array_push($array, "set site-to-site peer " . $remotePublicIP . " connection-type initiate");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " default-esp-group Cloud");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " ike-group Cloud");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " ikev2-reauth inherit");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " local-address any");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " tunnel 1 allow-nat-networks disable");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " tunnel 1 allow-public-networks disable");
    array_push($array, "set site-to-site peer " . $remotePublicIP . " tunnel 1 local prefix 172.20." . $networkIP. ".0/" . $subnetMask);
    array_push($array, "set site-to-site peer " . $remotePublicIP . " tunnel 1 remote prefix " . $remoteNetworkIP . "/" . $remoteSubnetMask);
    array_push($array, "exit");
  
    array_push($array, "edit vpn l2tp");
    array_push($array, "set remote-access outside-address " . $nextAvailableWANIP);
    array_push($array, "set remote-access authentication mode local");
    array_push($array, "set remote-access authentication local-users username cloud password '" . $l2tp_pass . "'");
    array_push($array, "set remote-access client-ip-pool start 172.20." . $networkIP . ".201");
    array_push($array, "set remote-access client-ip-pool stop 172.20." . $networkIP . ".253");
    array_push($array, "set remote-access description L2TP-" . $name);
    array_push($array, "set remote-access ipsec-settings authentication mode pre-shared-secret");
    array_push($array, "set remote-access ipsec-settings authentication pre-shared-secret '" . $psk . "'");
    array_push($array, "set remote-access ipsec-settings ike-lifetime 3600");
    array_push($array, "exit");

    array_push($array, "set interfaces ethernet eth1 ip enable-proxy-arp");
    array_push($array, "set system login user vyos authentication plaintext-password " . $routerPassword);

    array_push($array, "commit");

    array_push($array, "save");

    array_push($array, "exit");
    
    array_push($array, "reboot now");

    echo sshcmd($array,$wanIP,"vyos");

    sleep(60);

    updateNetworkStatus("Running",$networkID,$nextAvailableWANIP);
    
}

function ubuntuSetup($wanIP,$password){

    $array = [];
    array_push($array, "echo -e '" . $_ENV['ubuntu_Setup_Password'] . "'\n${password}\n${password}' | passwd");
    echo sshcmd($array,$wanIP,$_ENV['ubuntu_Setup_Password'],"administrator");

}

function getNextAvailableWANIP($networkNum){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); error_log("Failed to get available WAN IP 0"); return false; }
  
    $stmt = $connection->prepare("SELECT id, ip FROM wanips WHERE networkid IS NULL LIMIT 1");
    if($stmt->execute()) { } else { error_log("Failed to get available WAN IP 1"); return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            if(allocateWANIP($row['id'],$networkNum)) {
             return $row['ip']; } else { error_log("Failed to get available WAN IP 2"); return false; }
            }
    } else { error_log("Failed to get available WAN IP 3"); return false; }

}


function allocateWANIP($id,$networkNum){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); error_log("Failed to get available WAN IP 4"); return false; }
  
    $stmt = $connection->prepare("UPDATE wanips SET networkid = ? WHERE id = ?");
    $stmt->bind_param("ii", $networkNum, $id);
    if($stmt->execute()) { return true; } else { error_log("Failed to get available WAN IP 5"); return false; }
    $stmt->close();

}

function deallocateWANIP($networkNum){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE wanips SET networkid = NULL WHERE networkid = ?");
    $stmt->bind_param("i", $networkNum);
    $stmt->execute();
    $stmt->close();

}

function addNewFirewallRuleDB($data){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $data = json_decode($data, true);
  
    if($data['publicport'] === "22") { return false; }

    $firewallID = generateRandomString();
  
    $stmt = $connection->prepare("INSERT IGNORE INTO firewall (firewallid, name, direction, networkid, type, publicport, privateport, source, target) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiiiiss", $firewallID, $data['name'], $data['direction'], $data['networkid'], $data['protocol'], $data['publicport'], $data['privateport'], $data['sourceip'], $data['targetip']);
    if($stmt->execute()) { addNewFirewallRule($data,$stmt->insert_id); } else { return false; }
    $stmt->close();
    
}

function addNewFirewallRule($data,$id){
  
    $networkWanIP = getNetworkWanIPfromNum($data['networkid']);
    $routerPassword = getNetworkRouterPasswordfromNum($data['networkid']);

    $array = [];
    array_push($array, "configure");      
    array_push($array, "set nat destination rule " . $id . " description '" . $data['name'] . "'");
    array_push($array, "set nat destination rule " . $id . " destination port " . $data['publicport']);
    array_push($array, "set nat destination rule " . $id . " source address " . $data['sourceip']);
    array_push($array, "set nat destination rule " . $id . " inbound-interface eth0");
    array_push($array, "set nat destination rule " . $id . " protocol " . $data['protocol']);
    array_push($array, "set nat destination rule " . $id . " translation address " . $data['targetip']);
    array_push($array, "set nat destination rule " . $id . " translation port " . $data['privateport']);
    array_push($array, "set nat source rule " . $id . " destination address " . $data['sourceip']);
    array_push($array, "set nat source rule " . $id . " source address " . $data['targetip']);
    array_push($array, "set nat source rule " . $id . " outbound-interface eth0");
    array_push($array, "set nat source rule " . $id . " protocol " . $data['protocol']);
    array_push($array, "set nat source rule " . $id . " translation address " . $data['targetip']);

    array_push($array, "commit");
    array_push($array, "save");
    array_push($array, "exit");
    array_push($array, "exit");

    echo sshcmd($array,$networkWanIP,$routerPassword);
    
}

function removeFirewallRuleDB($networkId,$id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    $stmt = $connection->prepare("DELETE FROM firewall WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { removeFirewallRule($networkId,$id); } else { return false; }
    $stmt->close();
    
}

function removeFirewallRule($networkId,$id){
  
    $networkWanIP = getNetworkWanIPfromNum($networkId);
    $routerPassword = getNetworkRouterPasswordfromNum($networkId);

    $array = [];
    array_push($array, "configure");      
    array_push($array, "delete nat destination rule " . $id);
    array_push($array, "delete nat source rule " . $id);
    array_push($array, "commit");
    array_push($array, "save");
    array_push($array, "exit");
    array_push($array, "exit");

    echo sshcmd($array,$networkWanIP,$routerPassword);
    
}

function showInboundRules($networkId){
  
      $array = [];
      $tableRows = "";

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM firewall WHERE networkid = ?");
    $stmt->bind_param("i", $networkId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 

        $tableRows .= "<table style='width:100%' class='table-borderless'>
        <thead>
           <th>Name</th>
           <th>Type</th>
           <th>Public Port</th>
           <th>Private Port</th>
           <th>Source</th>
           <th>Target</th>
        </thead>
        <tbody id='inboundRulesTable'>";

        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['firewallid'] = $row['firewallid'];
            $array['name'] = $row['name'];
            $array['direction'] = $row['direction'];
            $array['type'] = $row['type'];
            $array['publicport'] = $row['publicport'];
            $array['privateport'] = $row['privateport'];
            $array['source'] = $row['source'];
            $array['target'] = $row['target'];
            $array['time'] = $row['time'];
          
          $tableRows .= "<tr><td>" . $array['name'] . "</td><td>Custom</td><td>" . $array['publicport'] . "</td><td>" . $array['privateport'] . "</td><td>" . $array['source'] . "</td><td>" . $array['target'] . "</td><td style='color:red;cursor:pointer' data-toggle='tooltip' data-placement='right' title='Remove Rule' onclick='showRemoveRuleModal(" . $networkId . "," . $array['id'] . ")'><i class='fas fa-trash-alt'></i></td></tr>";
          
            }

            $tableRows .= "</tbody>
            </table>";
    
    return $tableRows;
    
    } else { return "<p>No Inbound Rules</p>"; }
  
}

function showOutboundRules($networkId){
  
      $array = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM firewall WHERE networkid = ?");
    $stmt->bind_param("i", $networkId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['firewallid'] = $row['firewallid'];
            $array['name'] = $row['name'];
            $array['direction'] = $row['direction'];
            $array['type'] = $row['type'];
            $array['publicport'] = $row['publicport'];
            $array['privateport'] = $row['privateport'];
            $array['source'] = $row['source'];
            $array['target'] = $row['target'];
            $array['time'] = $row['time'];
            }
    
    return $array;
    
    } else { return "No Rules Added"; }
  
}

function addNewDisk($diskId,$diskName,$diskSize,$diskIdentifier){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $diskPath = "D:\Disks\\" . $diskId . ".vhdx";
  
    $stmt = $connection->prepare("INSERT IGNORE INTO disks (diskId, name, diskIdentifier, size, path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $diskId, $diskName,$diskIdentifier,$diskSize,$diskPath);
    if($stmt->execute()) { $lastId = $stmt->insert_id; } else { return false; }
    $stmt->close();

    addPermission($_SESSION['id'],4,$lastId);
    addLogEntry($_SESSION['id'],4,$lastId,1);

    return $lastId;
    
}

function removeDiskFromDb($diskId){

    $id = getDiskDbID($diskId);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("DELETE FROM disks WHERE diskId = ?");
    $stmt->bind_param("s", $diskId);
    if($stmt->execute()) { 
        
        removePermissionsForResource(4,$id);
        addLogEntry($_SESSION['id'],4,$lastId,2);

        return true; 
    } else { 
        return false; 
    }



    return $lastId;
    
}

function attachDisk($diskId,$VMName){

    global $connection;
  
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE disks SET vm = ?, attached = 1 WHERE diskId = ?");
    $stmt->bind_param("ss", $VMName,$diskId);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
    
}

function attachNetwork($VMNetwork,$VMName){
  
    global $connection;
  
    $VMNetworkID = getNetworkNumfromId($VMNetwork);
  
    if(checkPermission($_SESSION['id'],2,$VMNetworkID)) {
      
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE vm SET network = ? WHERE id = ?");
    $stmt->bind_param("ii", $VMNetworkID,$VMName);
    if($stmt->execute()) { db_AttachNetwork($VMNetwork,$VMName); return true; } else { return false; }
    $stmt->close();
      
    } else { return false; }
    
}

function detachDisk($diskId){
  
    global $connection;
  
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE disks SET vm = 0, attached = 0 WHERE diskId = ?");
    $stmt->bind_param("s", $diskId);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
}

function checkDiskIsDetached($diskId){

    global $connection;

    $stmt = $connection->prepare("SELECT diskId FROM disks WHERE diskId = ? AND attached = 0");
    $stmt->bind_param("s", $diskId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { return true; }

}

function readyImage($image){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    $stmt = $connection->prepare("UPDATE images SET ready = 1 WHERE path = ?");
    $stmt->bind_param("s", $image);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
    
}

function unreadyImage($image){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    $stmt = $connection->prepare("UPDATE images SET ready = 0 WHERE path = ?");
    $stmt->bind_param("s", $image);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
    
}

function checkIfImageIsReady($image){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    $stmt = $connection->prepare("SELECT id FROM images WHERE path = ? AND ready = 1");
    $stmt->bind_param("s", $image);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { return true; }
    
}

function renameDisk($diskId,$diskName){
  
    global $connection;
  
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE disks SET name = ? WHERE diskId = ?");
    $stmt->bind_param("ss", $diskName,$diskId);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
}

function getNetworkIDfromNum($networkNum){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT networkid FROM network WHERE id = ?");
    $stmt->bind_param("s", $networkNum);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['networkid'];
            }
    return $id;
    
    } else { return false; }

}

function getNetworkNumfromId($networkId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT id FROM network WHERE networkid = ?");
    $stmt->bind_param("s", $networkId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            }
    return $id;
    
    } else { return false; }

}

function getNetworkWanIPfromNum($networkNum){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT wanip FROM network WHERE id = ?");
    $stmt->bind_param("i", $networkNum);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $wanip = $row['wanip'];
            }
    return $wanip;
    
    } else { return false; }

}

function getNetworkRouterPasswordfromNum($networkNum){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT routerPassword FROM network WHERE id = ?");
    $stmt->bind_param("i", $networkNum);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $routerPassword = $row['routerPassword'];
            }
    return $routerPassword;
    
    } else { return false; }

}

function getVMIDfromName($VMName){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT vmid FROM vm WHERE name = ?");
    $stmt->bind_param("s", $VMName);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['vmid'];
            }
    return $id;
    
    } else { return false; }

}

function readMonitorForVM($VMName){
  
  $VMuuid = getVMuuid($VMName);
  if(file_exists("../../agent_info/" . $VMuuid . ".txt")) {
    $monitorOutput = file_get_contents("../../agent_info/" . $VMuuid . ".txt");
    echo htmlspecialchars($monitorOutput);
  } else {
    echo "No Agent Installed";
  }
  
  
}

function getAttachedStorageForVM($VMId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT name, size FROM disks WHERE vm = ?");
    $stmt->bind_param("i", $VMId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            echo "<p>" . $row['name'] . "<small> - " . $row['size'] . "MB</small></p>";
            }

    } else { echo "<p>No Attached Virtual Disks</p>"; }

}

function getAttachedNetworkForVM($VMId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT network FROM vm WHERE id = ?");
    $stmt->bind_param("i", $VMId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
          $networkName = getNetworkName($row['network']);
          echo '<p><a href="https://cloud.jspc.co.uk/network?id=' . $row['network'] . '">' . $networkName . '</a></p>';
            }

    } else { echo "<p>No Attached Network</p>"; }

}

function addCheckpoint($VMName, $name){

    $VMId = getVMDbIDfromMachineID($VMName);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("INSERT IGNORE INTO checkpoints (vm, name) VALUES (?, ?)");
    $stmt->bind_param("is", $VMId, $name);
    if($stmt->execute()) { return $stmt->insert_id; } else { return false; }
    $stmt->close();
    
}

function removeCheckpoint($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("DELETE FROM checkpoints WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { return true; } else { return false; }
    
}

function removeAllCheckpointsForVM($VMName){

    $VMId = getVMDbIDfromMachineID($VMName);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("DELETE FROM checkpoints WHERE vm = ?");
    $stmt->bind_param("i", $VMId);
    if($stmt->execute()) { return true; } else { return false; }
    
}

function getCheckpointsForVM($VMId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT id, name, time FROM checkpoints WHERE vm = ?");
    $stmt->bind_param("i", $VMId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            echo "<p><a id='removeCheckpointBtn' href='#' onclick='removeCheckpointModal(" . $row['id'] . ",\"" . $row['name'] . "\")' class='btn btn-danger btn-sm' role='button'><i class='fas fa-trash-alt'></i></a>&nbsp;<a id='addCheckpointBtn' href='#' onclick='restoreCheckpointModal(" . $row['id'] . ",\"" . $row['name'] . "\")' class='btn btn-success btn-sm' role='button'><i class='fas fa-play'></i></a>&nbsp;&nbsp;" . $row['name'] . "<small> - " . $row['time'] . "</small></p>";
            }

    } else { echo "<p>No Checkpoints Available</p>"; }

}

function getNumberOfCheckpointsForVM($VMId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT id FROM checkpoints WHERE vm = ?");
    $stmt->bind_param("i", $VMId);
    if($stmt->execute()) { } else { return 0; }
    $result = $stmt->get_result();
    return $result->num_rows;

}

function getVMIDfromNum($VMNum){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT vmid FROM vm WHERE id = ?");
    $stmt->bind_param("i", $VMNum);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['vmid'];
            }
    return $id;
    
    } else { return false; }

}

function getVMIDfromMachineID($machineID){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT `vmid` FROM `vm` WHERE `machineid` = ?");
    $stmt->bind_param("s", $machineID);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['vmid'];
            }
    return $id;
    
    } else { return false; }

}

function getVMDbIDfromMachineID($machineID){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT `id` FROM `vm` WHERE `machineid` = ?");
    $stmt->bind_param("s", $machineID);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            }
    return $id;
    
    } else { return false; }

}

function getDiskDbID($diskId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT `id` FROM `disks` WHERE `diskid` = ?");
    $stmt->bind_param("s", $diskId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            }
    return $id;
    
    } else { return false; }

}

function getProjectDbId($projectId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT `id` FROM `projects` WHERE `projectid` = ?");
    $stmt->bind_param("s", $projectId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $id = $row['id'];
            }
    return $id;
    
    } else { return false; }

}

function updateVMBuildStatus($id,$status){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE vm SET buildstatus = ? WHERE id = ?");
    $stmt->bind_param("ii", $status,$id);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
    
}

function errorBuild($machineId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE vm SET errorBuild = 1, state = 'Error', status = 'Error', buildstatus = 100 WHERE machineid = ?");
    $stmt->bind_param("s",$machineId);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
    
}

function updateNetworkBuildStatus($id,$status){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE network SET buildstatus = ? WHERE id = ?");
    $stmt->bind_param("ii", $status,$id);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
    
}

function updateNetworkStatus($state,$networkID,$wanIP){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("UPDATE network SET state = ?, wanip = ? WHERE networkid = ?");
    $stmt->bind_param("sss",$state,$wanIP,$networkID);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();

}

function getVMsForNetwork($networkNum){
  
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $allVM = [];
  
    $stmt = $connection->prepare("SELECT id, name, ipaddr FROM vm WHERE network = ?");
    $stmt->bind_param("i", $networkNum);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['name'] = $row['name'];
            $array['ipaddr'] = $row['ipaddr'];
          
            $allVM[] = $array;
            }
    return $allVM;
    
    } else { return false; }
  
  
}

function getVMsForProject($projectId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $allVM = [];
  
    $stmt = $connection->prepare("SELECT id, name, ipaddr FROM vm WHERE project = ?");
    $stmt->bind_param("i", $projectId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['name'] = $row['name'];
            $array['ipaddr'] = $row['ipaddr'];
          
            $allVM[] = $array;
            }
    return $allVM;
    
    } else { return false; }

}

function getNetworksForProject($projectId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $allNetworks = [];
  
    $stmt = $connection->prepare("SELECT id, name, wanip FROM network WHERE project = ?");
    $stmt->bind_param("i", $projectId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['name'] = $row['name'];
            $array['wanip'] = $row['wanip'];
          
            $allNetworks[] = $array;
            }
    return $allNetworks;
    
    } else { return false; }

}

function updateVMStatus($machineID){

    updateVMUUID($machineID);
    $VM = db_getVMInfo($machineID);
    $VM = json_decode($VM,true);
    if(isset($VM['State'])) { $VMIPAddress = json_decode(db_getVMIPAddress($machineID),true); if(isset($VMIPAddress['IPAddresses'][0])) { $VMIPAddress = $VMIPAddress['IPAddresses'][0]; } else { $VMIPAddress = "No Address"; } }

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("UPDATE vm SET vmid = ?, state = ?, status = ?, uptime = ?, ipaddr = ? WHERE machineid = ?");
    $stmt->bind_param("ssssss",$VM['VMId'],$VM['State'],$VM['Status'],$VM['Uptime']['TotalSeconds'],$VMIPAddress,$machineID);
    if($stmt->execute()) { return $VM; } else { return false; }
    $stmt->close();

}


function updateVMIPAddress($machineID){

    $VM = db_getVMInfo($machineID);
    $VM = json_decode($VM,true);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("UPDATE vm SET vmid = ?, state = ?, status = ?, uptime = ? WHERE machineid = ?");
    $stmt->bind_param("sssss",$VM['VMId'],$VM['State'],$VM['Status'],$VM['Uptime']['TotalSeconds'],$machineID);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();

}

function updateVMUUID($machineID){

    $uuid = getVMuuid($machineID);
    if($uuid === false) {
        $uuid = json_decode(db_getVMUUID($machineID))->BIOSGUID;
    }

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("UPDATE vm SET uuid = ? WHERE machineid = ?");
    $stmt->bind_param("ss",$uuid,$machineID);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();

}

function getVMuuid($machineID){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT uuid FROM vm WHERE machineid = ?");
    $stmt->bind_param("s", $machineID);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $uuid = $row['uuid'];
            }
    return $uuid;
    
    } else { return false; }

}


function getVMStatus($machineID){

    $VM = db_getVMInfo($machineID);
    return json_decode($VM,true);

}

function getAllVMStatus(){

    $allVM = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM vm");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {

            if(checkPermission($_SESSION['id'],3,$row['id'])) {
                $array['id'] = $row['id'];
                $array['vmid'] = $row['vmid'];
                $array['name'] = $row['name'];
                $array['project'] = getProjectName($row['project']);
                $array['network'] = $row['network'];
                $array['image'] = getImageName($row['image']);
                $array['password'] = $row['password'];
                $array['memory'] = $row['memory'];
                $array['cores'] = $row['cores'];
                $array['disksize'] = $row['disksize'];
                $array['diskused'] = $row['diskused'];
                $array['ipaddr'] = $row['ipaddr'];
                $array['state'] = $row['state'];
                $array['status'] = $row['status'];
                $array['uptime'] = $row['uptime'];
                $array['machineid'] = $row['machineid'];
                $array['notes'] = $row['notes'];
                $array['buildstatus'] = $row['buildstatus'];
                $array['errorBuild'] = $row['errorBuild'];
                $array['time'] = $row['time'];

                $allVM[] = $array;
            }
            }
    
    return $allVM;
    
    } else { return false; }

}

function getAllNetworkStatus(){

    $allNetworks = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM network");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            if(checkPermission($_SESSION['id'],2,$row['id'])) {
                $array['id'] = $row['id'];
                $array['networkid'] = $row['networkid'];
                $array['name'] = $row['name'];
                $array['project'] = getProjectName($row['project']);
                $array['networkip'] = $row['networkip'];
                $array['subnet'] = $row['subnet'];
                $array['lanip'] = $row['lanip'];
                $array['wanip'] = $row['wanip'];
                $array['buildstatus'] = $row['buildstatus'];
                $array['vms'] = getVMsForNetwork($row['id']);

                $allNetworks[] = $array;
            }
            }
    
    return $allNetworks;
    
    } else { return false; }

}


function getAllVirtualDiskStatus(){

    $allVirtualDisks = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM disks");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            if(checkPermission($_SESSION['id'],4,$row['id'])) {
                $array['id'] = $row['id'];
                $array['diskId'] = $row['diskId'];
                $array['diskIdentifier'] = $row['diskIdentifier'];
                $array['name'] = $row['name'];
                $array['size'] = $row['size'];
                $array['attached'] = $row['attached'];
                $array['path'] = $row['path'];
                $array['vmname'] = getVMName($row['vm']);
                $array['vm'] = $row['vm'];

                $allVirtualDisks[] = $array;
            }
            }
    
    return $allVirtualDisks;
    
    } else { return false; }

}

function getAllProjectStatus(){

    $allProjects = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM projects");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            if(checkPermission($_SESSION['id'],1,$row['id'])) {
                $array['id'] = $row['id'];
                $array['projectid'] = $row['projectid'];
                $array['name'] = $row['name'];
                $array['vms'] = getVMsForProject($row['id']);
                $array['networks'] = getNetworksForProject($row['id']);

                $allProjects[] = $array;
            }
            }
    
    return $allProjects;
    
    } else { return false; }

}

function getVMStatusDB($id){

    $array = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM vm WHERE id = ? OR machineid = ?");
    $stmt->bind_param("is", $id,$id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['vmid'] = $row['vmid'];
            $array['name'] = $row['name'];
            $array['project'] = getProjectName($row['project']);
            $array['network'] = $row['network'];
            $array['image'] = getImageName($row['image']);
            $array['password'] = $row['password'];
            $array['memory'] = $row['memory'];
            $array['cores'] = $row['cores'];
            $array['disksize'] = $row['disksize'];
            $array['diskused'] = $row['diskused'];
            $array['ipaddr'] = $row['ipaddr'];
            $array['state'] = $row['state'];
            $array['status'] = $row['status'];
            $array['uptime'] = $row['uptime'];
            $array['machineid'] = $row['machineid'];
            $array['notes'] = $row['notes'];
            $array['buildstatus'] = $row['buildstatus'];
            $array['errorBuild'] = $row['errorBuild'];
            $array['time'] = $row['time'];
            $array['uuid'] = $row['uuid'];
        }

            $array['deploymentstatus'] = getBuildLog($array['machineid']);
    
    return $array;
    
    } else { return false; }

}

function getNetworkStatusDB($id){

    $array = [];

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $stmt = $connection->prepare("SELECT * FROM network WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['networkid'] = $row['networkid'];
            $array['name'] = $row['name'];
            $array['project'] = getProjectName($row['project']);
            $array['networkip'] = $row['networkip'];
            $array['subnet'] = $row['subnet'];
            $array['lanip'] = $row['lanip'];
            $array['wanip'] = $row['wanip'];
            $array['ipsec_psk'] = $row['ipsec_psk'];
            $array['ipsec_remotewanip'] = $row['ipsec_remotewanip'];
            $array['ipsec_remotelanip'] = $row['ipsec_remotelanip'];
            $array['ipsec_remotesubnet'] = $row['ipsec_remotesubnet'];
            $array['l2tp_pass'] = $row['l2tp_pass'];
            $array['buildstatus'] = $row['buildstatus'];
            $array['resetNetworkStats'] = $row['resetNetworkStats'];
            }
    
    return $array;
    
    } else { return false; }

}

function getProjectName($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT name FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $name = $row['name'];
            }
    return $name;
    
    } else { return "N/A"; }

}

function getProjectNamesForSelect(){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "<option value='null'>Choose...</option>";
  
    $stmt = $connection->prepare("SELECT id, name FROM projects");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            if(checkPermission($_SESSION['id'],1,$row['id'])) {
            $response .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            }
    return $response;
    
    } else { $response .= "<option selected>No Projects Available</option>";
             return $response; }

}


function getNetworkNamesForSelect(){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "<option value='null'>Choose...</option>";
  
    $stmt = $connection->prepare("SELECT id, name FROM network WHERE state = 'Running'");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            if(checkPermission($_SESSION['id'],2,$row['id'])) {
              $response .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            }
    return $response;
    
    } else { $response .= "<option selected>No Virtual Networks Available</option>";
             return $response; }

}


function getVMNamesForSelect(){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "<option>Choose...</option>";
  
    $stmt = $connection->prepare("SELECT machineid, name FROM vm");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            if(checkPermission($_SESSION['id'],3,$row['id'])) {
              $response .= "<option value='" . $row['machineid'] . "'>" . $row['name'] . "</option>";
            }
            }
    return $response;
    
    } else { $response .= "<option selected>No Virtual Machines Available</option>";
             return $response; }

}


function getDiskNamesAvailableForSelect(){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $response = "";


    $stmt = $connection->prepare("SELECT id, diskId, name FROM disks WHERE attached = 0");
    if($stmt->execute()) { } else { return "Failed to connect to Database"; }
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $response .= "<select id='storageDiskSelected' class='form-control'><option value='null'>Choose...</option>";
        while($row = $result->fetch_assoc()) {
          if(checkPermission($_SESSION['id'],4,$row['id'])) {
            $response .= "<option value='" . $row['diskId'] . "'>" . $row['name'] . "</option>";
          }
            }
        $response .= "</select>";
    return $response;
    
    } else { $response .= "<select disabled id='storageDiskSelected' class='form-control'><option selected value='null'>No Disks Available</option></select>";
             return $response; }

}

function getNetworksAvailableForSelect(){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $response = "";


    $stmt = $connection->prepare("SELECT id, networkid, name FROM network");
    if($stmt->execute()) { } else { return "Failed to connect to Database"; }
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $response .= "<select id='networkSelected' class='form-control'><option value='null'>Choose...</option>";
        while($row = $result->fetch_assoc()) {
          if(checkPermission($_SESSION['id'],2,$row['id'])) {
            $response .= "<option value='" . $row['networkid'] . "'>" . $row['name'] . "</option>";
          }
            }
        $response .= "</select>";
    return $response;
    
    } else { $response .= "<select disabled id='networkSelected' class='form-control'><option selected value='null'>No Networks Available</option></select>";
             return $response; }

}

function getTotalDiskSpaceInUse(){
    $vmDiskspaceUsed = getNumberOfVms() * 60000;
    $networkDiskspaceUsed = getNumberOfNetworks() * 10000;

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT SUM(size) FROM disks");
    if($stmt->execute()) { } else { return $response; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $response = $row['SUM(size)'];
            }
    return $response + $vmDiskspaceUsed + $networkDiskspaceUsed;
    }

}

function getNumberOfVms(){
    
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "...";
  
    $stmt = $connection->prepare("SELECT machineid FROM vm");
    if($stmt->execute()) { } else { return $response; }
    $result = $stmt->get_result();
    $response = $result->num_rows;
    return $response; 

}

function getNumberOfNetworks(){
    
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "...";
  
    $stmt = $connection->prepare("SELECT id FROM network");
    if($stmt->execute()) { } else { return $response; }
    $result = $stmt->get_result();
    $response = $result->num_rows;
    return $response; 

}

function getVMIdsForSelect(){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "<option>Choose...</option>";
  
    $stmt = $connection->prepare("SELECT id, name FROM vm");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $response .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
    return $response;
    
    } else { $response .= "<option selected>No Virtual Machines Available</option>";
             return $response; }

}


function getImageNamesForSelect(){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "<option value='null'>Choose...</option>";
  
    $stmt = $connection->prepare("SELECT id, name FROM images");
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
                // if(checkPermission($_SESSION['id'],5,$row['id'])) {
                    $response .= "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                // }
            }
    // TEMPORARY
    // $response = "<option selected value='1'>Windows Server 2019 Standard</option>";
    return $response;
    
    }

}


function getVMName($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT name FROM vm WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $name = $row['name'];
            }
    return $name;
    
    } else { return "N/A"; }

}


function getMachineIDForVM($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT machineid FROM vm WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $name = $row['machineid'];
            }
    return $name;
    
    } else { return "N/A"; }

}

function getNetworkName($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT name FROM network WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $name = $row['name'];
            }
    return $name;
    
    } else { return "N/A"; }

}

function getDiskName($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT name FROM disks WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $name = $row['name'];
            }
    return $name;
    
    } else { return "N/A"; }

}

function getVMMachineId($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT machineid FROM vm WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $machineId = $row['machineid'];
            }
    return $machineId;
    
    } else { return false; }

}

function getImageName($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT name FROM images WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $name = $row['name'];
            }
    return $name;
    
    } else { return "N/A"; }

}


function renameVM($newName,$machineID){
  
    $newName = removeSpecialChar($newName);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE vm SET name = ? WHERE machineid = ?");
    $stmt->bind_param("ss",$newName,$machineID);
    if($stmt->execute()) { return true; } else { return false; }
    
}

function renameNetwork($newName,$networkID){
  
    $newName = removeSpecialChar($newName);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE network SET name = ? WHERE networkid = ?");
    $stmt->bind_param("ss",$newName,$networkID);
    if($stmt->execute()) { return true; } else { return false; }
    
}

function removeVMfromDB($VMName){

    detachAllDisksForVM($VMName);
    removeAllCheckpointsForVM($VMName);

    $id = getVMDbIDfromMachineID($VMName);

    removePermissionsForResource(3,$id);
    addLogEntry($_SESSION['id'],3,$id,2);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("DELETE FROM vm WHERE machineid = ?");
    $stmt->bind_param("s", $VMName);
    if($stmt->execute()) { return true; } else { return false; }

}

function removeNetworkfromDB($networkId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $id = getNetworkNumfromId($networkId);
  
    deallocateWANIP($id);

    removePermissionsForResource(2,$id);
    addLogEntry($_SESSION['id'],2,$id,2);


    $stmt = $connection->prepare("DELETE FROM network WHERE networkid = ?");
    $stmt->bind_param("s", $networkId);
    $stmt->execute();
    $stmt->close();


}

function detachAllDisksForVM($VMName){

    $VMId = getVMDbIDfromMachineID($VMName);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE `disks` SET `attached`= 0, `vm`= 0 WHERE vm = ?");
    $stmt->bind_param("i",$VMId);
    if($stmt->execute()) { return true; } else { return false; }
}

function resetBandwidthUsage($networkId){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE network SET resetNetworkStats = NOW() WHERE networkid = ?");
    $stmt->bind_param("s", $networkId);
    if($stmt->execute()) { return true; } else { return false; }

}

function removeProjectfromDB($projectId){

    $id = getProjectDbId($projectId);

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("DELETE FROM projects WHERE projectid = ?");
    $stmt->bind_param("s", $projectId);
    if($stmt->execute()) { 
        removePermissionsForResource(1,$id); 
        addLogEntry($_SESSION['id'],1,$id,2);
        return true; 
    } else { 
        return false; 
    }

}

function renameProjectDB($projectId,$newProjectName){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("UPDATE projects SET name = ? WHERE projectid = ?");
    $stmt->bind_param("ss", $newProjectName,$projectId);
    if($stmt->execute()) { return true; } else { return false; }

}

function selectDisk($id){

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $array = [];
  
    $stmt = $connection->prepare("SELECT * FROM disks WHERE diskIdentifier = ?");
    $stmt->bind_param("s", $id);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $array['id'] = $row['id'];
            $array['diskIdentifier'] = $row['diskIdentifier'];
            $array['name'] = $row['name'];
            $array['size'] = $row['size'];
            $array['used'] = $row['used'];
            $array['path'] = $row['path'];
            $array['vm'] = $row['vm'];
            }
    return json_encode($array);
    
    } else { return false; }

}

function secondsToDaysHms($d){
  if($d < 1) { return "0 Seconds"; }
  $d = floatval($d);
  $days = floor($d / 86400);
  $h = floor($d % 86400 / 3600);
  $m = floor($d % 3600 / 60);
  $s = floor($d % 3600 % 60);

  $daysDisplay = $days > 0 ? $days . ($days == 1 ? " Day, " : " Days, ") : "";
  $hDisplay = $h > 0 ? $h . ($h == 1 ? " Hour, " : " Hours, ") : "";
  $mDisplay = $m > 0 ? $m . ($m == 1 ? " Minute, " : " Minutes, ") : "";
  $sDisplay = $s > 0 ? $s . ($s == 1 ? " Second" : " Seconds") : "";
  return $daysDisplay . $hDisplay . $mDisplay . $sDisplay; 
}

function checkPermission($userId,$resourceType,$resource) {

/* Resource Types:
  
      Project: 1
      Virtual Network: 2
      Virtual Machine: 3
      Disk: 4
      Image: 5 

*/
  
  if($userId === 9) { return true; }

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    $stmt = $connection->prepare("SELECT id FROM permissions WHERE user = ? AND resource_type = ? AND resource = ?");
    $stmt->bind_param("iii", $userId,$resourceType,$resource);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows > 0) { 
        return true;
    } else { return false; }
}

function addPermission($userId,$resourceType,$resource) {
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    if($userId != 9) {
    $stmt = $connection->prepare("INSERT IGNORE INTO permissions (user, resource_type, resource) VALUES ('9', ?, ?)");
    $stmt->bind_param("ii", $resourceType,$resource);
    if($stmt->execute()) { } else { return false; }
    $stmt->close();
    }
  
    $stmt = $connection->prepare("INSERT IGNORE INTO permissions (user, resource_type, resource) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId,$resourceType,$resource);
    if($stmt->execute()) { return $stmt->insert_id; } else { return false; }
    $stmt->close();
}

function removePermissionsForResource($resourceType,$resource) {
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    $stmt = $connection->prepare("DELETE FROM permissions WHERE resource_type = ? AND resource = ?");
    $stmt->bind_param("ii",$resourceType,$resource);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
}

function removePermissionsForUser($userId) {
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    $stmt = $connection->prepare("DELETE FROM permissions WHERE user = ?");
    $stmt->bind_param("i",$userId);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
}

function removePermissionsOfUserForResource($userId,$resourceType,$resource) {
    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
    
    $stmt = $connection->prepare("DELETE FROM permissions WHERE user = ? AND resource_type = ? AND resource = ?");
    $stmt->bind_param("iii",$userId,$resourceType,$resource);
    if($stmt->execute()) { return true; } else { return false; }
    $stmt->close();
}

// Adds a new Log entry

function addLogEntry($userId,$resourceType,$resource,$action) {

    /* Resource Types:
  
      Project: 1
      Virtual Network: 2
      Virtual Machine: 3
      Disk: 4
      Image: 5 

       Actions:

      Add: 1
      Remove: 2
      Start: 3
      Stop: 4
      Login: 5
      Logout: 6
     
*/

    global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("INSERT IGNORE INTO log (user, resource_type, resource, action) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $userId,$resourceType,$resource,$action);
    if($stmt->execute()) { return $stmt->insert_id; } else { return false; }
    $stmt->close();
}

// Returns Log Entries for a user formatted for a table

function getLogEntriesForTable($userId) {
  
      global $connection;
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }

    $response = "<tr><td colspan='5'>No Log Entries</td></tr>";
    $query = "SELECT * FROM log WHERE user = ? ORDER BY time DESC LIMIT 50";
  
    if($userId == 9) { 
      
     $query = "SELECT * FROM log ORDER BY time DESC LIMIT 500";
     $stmt = $connection->prepare($query);
                     
    } else {
      
     $stmt = $connection->prepare($query);
     $stmt->bind_param("i", $userId);
      
    }

    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        $response = "";
        while($row = $result->fetch_assoc()) {
            
            $name = getUserNameFromId($row['user']);
            $resourceType = getResourceTypeFromId($row['resource_type']);
            $resource = getResourceNameFromId($row['resource_type'],$row['resource']);
            $action = getActionNameFromId($row['action']);
          
            $response .= "<tr>
                      <td>{$name}</td>
                      <td>{$resourceType}</td>
                      <td>{$resource}</td>
                      <td>{$action}</td>
                      <td>{$row['time']}</td>
                    </tr>";
            }
    }
  
  return $response;
  
}

// Returns Username from ID

function getUserNameFromId($userId){
  
    $name = "N/A";
  
    global $connection;
  
    if(mysqli_connect_errno()) { error_log("Failed to connect to Database: " . mysqli_connect_error()); return false; }
  
    $stmt = $connection->prepare("SELECT firstName, lastName FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    if($stmt->execute()) { } else { return false; }
    $result = $stmt->get_result();
    if($result->num_rows !== 0) { 
        while($row = $result->fetch_assoc()) {
            $name = $row['firstName'] . " " . $row['lastName'];
            }
    } 
  
  return $name;
}

// Returns Resource Type name from its ID

function getResourceTypeFromId($id){
  
    switch($id) {
      case 1:
        return "Project";
      case 2:
        return "Virtual Network";
      case 3:
        return "Virtual Machine";
      case 4:
        return "Disk";
      case 5:
        return "Image";
      default:
        return "N/A";
    }
  
}

// Returns name of Resource from its ID

function getResourceNameFromId($resourceType,$resource){
  
    $name = "N/A";
  
    global $connection;
  
      switch($resourceType) {
      case 1:
        return getProjectName($resource);
      case 2:
        return getNetworkName($resource);
      case 3:
        return getVMName($resource);
      case 4:
        return getDiskName($resource);
      case 5:
        return getImageName($resource);
      default:
        return "N/A";
    }

}

// Returns name of log action from its ID

function getActionNameFromId($id){
  
    switch($id) {
      case 1:
        return "Add";
      case 2:
        return "Remove";
      case 3:
        return "Start";
      case 4:
        return "Stop";
      case 5:
        return "Login";
      case 6:
        return "Logout";
      default:
        return "N/A";
    }
  
}

// Sanitize string

function escape($string) {
       
    if(!is_array($string)) {
	return str_replace(";", "", nl2br(htmlspecialchars($string), ENT_QUOTES));
    } else { return $string; }
      
}
