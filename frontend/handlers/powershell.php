<?php 
/**
 * Cloud Portal Powershell Functions.
 * Version 1.0.
 *
 * @author    Chris Groves <chris@thegaff.co.uk>
 * @copyright 2020 Chris Groves
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

function db_getFailOverStatus(){
    $array = [];
    array_push($array, "Get-ItemProperty -Path 'HKLM:\SOFTWARE\Microsoft\Virtual Machine\Guest\Parameters' -Name 'PhysicalHostName'");
    return pscmd($array);
}

function db_getVMInfo($VMName){
    $array = [];
    array_push($array, "Get-VM -name '" . $VMName . "'");
    return pscmd($array);
}

function db_getVMUUID($VMName){
    $array = [];
    array_push($array, "Get-VM '" . $VMName . "' | % { (gwmi -Namespace root\\virtualization\\v2 -Class msvm_computersystem -Filter ('ElementName=\"{0}\"' -f \$_.Name)).GetRelated('msvm_virtualsystemsettingdata') | select BIOSGUID -last 1}");
    return pscmd($array);
}

function db_getVMIPAddress($VMName){
    $array = [];
    array_push($array, "Get-VM -Name '" . $VMName . "' | Select -ExpandProperty NetworkAdapters | Select IPAddresses");
    return pscmd($array);
}

function db_renameVM($oldName, $newName){
    $array = [];
    $newName = removeSpecialChar($newName);
    array_push($array, "Rename-VM '" . $oldName . "' '" . $newName . "'");
    pscmd($array);
}

function db_turnOnVM($VMName){
    $array = [];
    array_push($array, "Start-VM -Name '" . $VMName . "'");
    pscmd($array);
    $id = getVMDbIDfromMachineID($VMName);
    addLogEntry($_SESSION['id'],3,$id,3);
}

function db_turnOffVM($VMName){
    $array = [];
    array_push($array, "Stop-VM -Name '" . $VMName . "' -TurnOff");
    pscmd($array);
    $id = getVMDbIDfromMachineID($VMName);
    addLogEntry($_SESSION['id'],3,$id,4);
}

function db_shutdownVM($VMName){
    $array = [];
    array_push($array, "Stop-VM -Name '" . $VMName . "' -Force");
    pscmd($array);
    $id = getVMDbIDfromMachineID($VMName);
    addLogEntry($_SESSION['id'],3,$id,4);
}

function db_restartVM($VMName){
    $array = [];
    array_push($array, "Restart-VM -Name '" . $VMName . "' -Force");
    pscmd($array);
    $id = getVMDbIDfromMachineID($VMName);
    addLogEntry($_SESSION['id'],3,$id,4);
    addLogEntry($_SESSION['id'],3,$id,3);
}

function db_saveVMState($VMName){
    $array = [];
    array_push($array, "Save-VM -Name '" . $VMName . "'");
    pscmd($array);
}

function db_deleteVMState($VMName){
    $array = [];
    array_push($array, "Remove-VMSavedState -VMName '" . $VMName . "'");
    pscmd($array);
}

function db_checkpointVM($VMName,$name){
    $array = [];
    array_push($array, "Checkpoint-VM -Name '" . $VMName . "' -SnapshotName '" . $name . "'");
    pscmd($array);
    addCheckpoint($VMName, $name);
}

function db_removeCheckpointVM($id,$name,$VMName){
    $array = [];
    array_push($array, "Get-VM '" . $VMName . "' | Remove-VMSnapshot -Name '" . $name . "'");
    pscmd($array);
    removeCheckpoint($id);
}

function db_restoreCheckpointVM($id,$name,$VMName){
    $array = [];
    array_push($array, "Restore-VMSnapshot -Name '" . $name . "' -VMName '" . $VMName . "' -Confirm:\$false; Get-VM '" . $VMName . "' | Remove-VMSnapshot -Name '" . $name . "'; Start-VM -Name '" . $VMName . "'");
    pscmd($array);
    if($id != "null") { removeCheckpoint($id); }
}

function db_updateVMImg($VMName){
    $output = shell_exec(escapeshellcmd("powershell.exe -file C:\Scripts\VMImage\bmp.ps1 -VMName " . $VMName));
}

function db_destroyVM($VMName){
    $array = [];
    array_push($array, "Remove-VM -Name '" . $VMName . "' -Force");
    array_push($array, "Remove-Item 'D:\Hyper-V\\" . $VMName . "' -Recurse -Force");
    pscmd($array);
    removeVMfromDB($VMName);
}

function db_destroyNetwork($networkId){
    $array = [];
    array_push($array, "Stop-VM -Name '" . $networkId . "' -Force | Out-Null");
    array_push($array, "Remove-VM -Name '" . $networkId . "' -Force | Out-Null");
    array_push($array, "Remove-Item 'D:\Hyper-V\\" . $networkId . "' -Recurse -Force | Out-Null");
    array_push($array, "Remove-VMSwitch '" . $networkId . "' -Force | Out-Null");
    pscmd($array);
    removeNetworkfromDB($networkId);
}

function db_destroyDisk($diskId){
    db_detachDisk($diskId);
    $array = [];
    array_push($array, "Remove-Item 'D:\Disks\\" . $diskId . ".vhdx' -Recurse -Force");
    pscmd($array);
    removeDiskFromDb($diskId);
}

function db_addNewProject($projectName){
  if(addProject($projectName)) {
    $array = [];
    array_push($array, "New-VMGroup -Name '" . $projectName . "' -GroupType VMCollectionType | Out-Null");
    array_push($array, "Add-VMGroupMember -VMgroup (Get-VMGroup 'Projects') -VMGroupMember (Get-VMGroup '" . $projectName . "') | Out-Null");
    pscmd($array);
  }
}

function db_addNewDisk($diskName,$diskSize){
    $diskId = generateRandomString();
    $array = [];
    array_push($array, "New-VHD -SizeBytes " . $diskSize . "MB -Path 'D:\Disks\\" . $diskId . ".vhdx' |  Out-Null; Get-VHD -Path 'D:\Disks\\" . $diskId . ".vhdx' | Select-Object -ExpandProperty DiskIdentifier");
    $diskIdentifier = pscmd($array);
    $diskIdentifier = json_decode($diskIdentifier,true);
    $diskIdentifier = $diskIdentifier['value'];
    addNewDisk($diskId,$diskName,$diskSize,$diskIdentifier);
}

function db_attachDisk($diskId,$VMName){
    if(checkDiskIsDetached($diskId)){
    $machineId = getVMMachineId($VMName);    
    $array = [];
    array_push($array, "Add-VMHardDiskDrive -VMName '" . $machineId . "' -Path 'D:\disks\\" . $diskId . ".vhdx' | Out-Null");
    pscmd($array);
    attachDisk($diskId,$VMName);
    }
}

function db_detachDisk($diskId){
    $array = [];
    array_push($array, "\$VHD = Get-VMHardDiskDrive -VMName * | where Path -eq 'D:\Disks\\" . $diskId . ".vhdx'; Remove-VMHardDiskDrive -VMName \$VHD.VMName -ControllerType SCSI -ControllerNumber \$VHD.Controllernumber -ControllerLocation \$VHD.ControllerLocation | Out-Null");
    pscmd($array);
    detachDisk($diskId);
}

function db_addNewVM($VMName,$VMProject,$VMNetwork,$VMImage,$VMMemory,$VMCpu,$VMPassword){

   ob_end_clean();
   header("Connection: close");
   ignore_user_abort();
   ob_start();

   $machineID = generateRandomString();
   if($VMNum = addVM($VMName,$VMProject,$VMNetwork,$VMImage,$VMMemory,$VMCpu,$VMPassword,$machineID)) {

   echo $VMNum;

   $size = ob_get_length();
   header("Content-Length: $size");
   ob_end_flush();
   flush();
   session_write_close();

   updateVMBuildStatus($VMNum,5);
    
   $name = $machineID;
   $image = $VMImage;
   $memory = intval($VMMemory);
   $cpu = intval($VMCpu);
   $VMNetwork = getNetworkIDfromNum($VMNetwork);

   updateVMBuildStatus($VMNum,10);

   switch ($image) {
       case 1:
       $deployScript = db_createDeployScript($machineID,$image,$VMName,$VMProject,$VMPassword);
       $image = "Base_2019_Std";
       break;
       case 2:
       $deployScript = db_createDeployScript($machineID,$image,$VMName,$VMProject,$VMPassword);
       $image = "Base_2012_Std";
       break;
       case 3:
       $deployScript = db_createDeployScript($machineID,$image,$VMName,$VMProject,$VMPassword);
       $image = "Base_Win10_Edu";
       break;
       case 4:
       $image = "Ubuntu";
       break;
       default:
       $deployScript = db_createDeployScript($machineID,$image,$VMName,$VMProject,$VMPassword);
       $image = "Base_2019_Std";
   }

    $array = [];
     
    updateVMBuildStatus($VMNum,5);

    while(!checkIfImageIsReady($image)) sleep(5);

    echo commandPrep("Creating Virtual Hard Disk Directory...","New-Item -ItemType directory -Path 'D:\Hyper-V\\" . $name . "\Virtual Hard Disks' | Out-Null",$machineID);

    unreadyImage($image);

    echo commandPrep("Copying Template Virtual Hard Disk...","Move-Item -Path 'D:\Images\\" . $image . ".vhdx' -Destination 'D:\Hyper-V\\" . $name . "\Virtual Hard Disks\\" . $name . ".vhdx'",$machineID);

    if($image != "Ubuntu") {
        echo commandPrep("Mounting Template Virtual Hard Disk...","New-Item -ItemType directory -Path 'D:\Mount\\" . $name . "'",$machineID);
        echo commandPrep("Copying Deployment Files...","Mount-WindowsImage -ImagePath 'D:\Hyper-V\\" . $name . "\Virtual Hard Disks\\" . $name . ".vhdx' -Path 'D:\Mount\\" . $name . "\' -Index 1; Copy-Item -Path 'C:\Deploy\\" . $name . ".xml' -Destination 'D:\Mount\\" . $name . "\Windows\Panther\deploy.xml' -Recurse; Dismount-WindowsImage -Path 'D:\Mount\\" . $name . "\' -Save",$machineID);
        echo commandPrep("Unmounting Template Virtual Hard Disk...","Remove-Item 'D:\Mount\\" . $name . "' -Recurse -Force",$machineID);
        echo commandPrep("Removing Temporary Deployment Files...","Remove-Item 'C:\Deploy\\" . $name . ".xml' -Force",$machineID);
    }

    updateVMBuildStatus($VMNum,5);

    echo commandPrep("Creating New VM...","New-VM -Name '" . $name . "' -MemoryStartupBytes " . $memory . "MB -Generation 2 -Path 'D:\Hyper-V\' | Out-Null",$machineID);
    echo commandPrep("Setting CPU Cores...","Set-VMProcessor '" . $name . "' -Count " . $cpu . " -Reserve 10 -Maximum 75 -RelativeWeight 200 | Out-Null",$machineID);
    echo commandPrep("Setting Memory...","Set-VMMemory '" . $name . "' -DynamicMemoryEnabled 1 -MinimumBytes " . $memory . "MB -StartupBytes " . $memory . "MB -MaximumBytes " . $memory . "MB -Priority 80 -Buffer 25 | Out-Null",$machineID);
    echo commandPrep("Attaching Virtual Hard Disk...","Add-VMHardDiskDrive -VMName '" . $name . "' -Path 'D:\Hyper-V\\" . $name . "\Virtual Hard Disks\\" . $name . ".vhdx' | Out-Null",$machineID);

    if($image == "Ubuntu") {
    echo commandPrep("Setting UEFI Firmware...","Set-VMFirmware '" . $name . "' -SecureBootTemplate 'MicrosoftUEFICertificateAuthority'",$machineID);
    }

    echo commandPrep("Attaching Network Adapter...","Set-VMNetworkAdapter -VMName '" . $name . "' -DhcpGuard On | Out-Null",$machineID);

    updateVMBuildStatus($VMNum,10);

    if($image == "Ubuntu") {
    echo commandPrep("Connecting To Network...","Get-VMSwitch 'External' | Connect-VMNetworkAdapter -VMName '" . $name . "' | Out-Null",$machineID);
    } else {
    echo commandPrep("Connecting To Network...","Get-VMSwitch '" . $VMNetwork . "' | Connect-VMNetworkAdapter -VMName '" . $name . "' | Out-Null",$machineID);    
    }
    echo commandPrep("Setting VM Features...","Set-VMFirmware '" . $name . "' -BootOrder (Get-VMHardDiskDrive '" . $name . "') | Out-Null",$machineID);
    echo commandPrep("","Set-VMProcessor -VMName '" . $name . "' -ExposeVirtualizationExtensions 1 | Out-Null",$machineID);
    echo commandPrep("","Enable-VMResourceMetering -VMName '" . $name . "' | Out-Null",$machineID);   
    echo commandPrep("","Get-VM -Name '" . $name . "' | Get-VMIntegrationService | Enable-VMIntegrationService | Out-Null",$machineID);
    echo commandPrep("","Get-VM -Name '" . $name . "' | Enable-VMResourceMetering | Out-Null",$machineID);
    echo commandPrep("Sysprepping VM...","Start-VM -Name '" . $name . "'",$machineID);
    echo commandPrep("Restoring Image for Next VM...","Copy-Item -Path 'D:\Working\\" . $image . ".vhdx' -Destination 'D:\Images\\" . $image . ".vhdx'",$machineID);

    readyImage($image);
     
    updateVMBuildStatus($VMNum,20);

    if($image != "Ubuntu") {
        sleep(115); 
        updateVMBuildStatus($VMNum,35);
        sleep(30);     
        updateVMBuildStatus($VMNum,45);
        sleep(25);
        updateVMBuildStatus($VMNum,50);
        sleep(45);
    } else {
        sleep(40);
        do {
            updateVMStatus($machineID);
            $VMStatus = getVMStatusDB($machineID);
            sleep(15);
           } while (substr($VMStatus['ipaddr'], 0, 4) !== "192.");
        ubuntuSetup($VMStatus['ipaddr'],$VMPassword);
        updateVMBuildStatus($VMNum,50);
        echo commandPrep("Connecting To Network...","Get-VMSwitch '" . $VMNetwork . "' | Connect-VMNetworkAdapter -VMName '" . $name . "' | Out-Null",$machineID);    
    }
                  
     do {
       updateVMStatus($machineID);
       $VMStatus = getVMStatusDB($machineID);
       sleep(15);
      } while (substr($VMStatus['ipaddr'], 0, 4) !== "172.");
         
     if(substr($VMStatus['ipaddr'], 0, 4) == "172.") {  
          updateVMUUID($machineID); 
          updateVMBuildStatus($VMNum,100);
          $output = "VM Deployed...";
          buildLog($output,$machineID);
          $VMStatus = updateVMStatus($machineID);
      }

    }
}


function db_addNewNetwork($project,$name,$networkIP,$subnetMask,$remotePublicIP,$remoteNetworkIP,$remoteSubnetMask){

    ob_end_clean();
    header("Connection: close");
    ignore_user_abort();
    ob_start();

    $image = "Router_Default";

    $networkID = generateRandomString();
    $routerPassword = generateRandomString(78);
    $l2tp_pass = generateRandomString(16);
    $psk = generateRandomString(56);
    $networkIPDB = "172.20." . $networkIP . ".0";
    $lanIP = "172.20." . $networkIP . ".1";
    if($networkNum = addNetwork($name,$routerPassword,$project,$networkID,$networkIPDB,$lanIP,$subnetMask,$remotePublicIP,$remoteNetworkIP,$remoteSubnetMask,$psk,$l2tp_pass)) {

    echo $networkNum;
    
    $size = ob_get_length();
    header("Content-Length: $size");
    ob_end_flush();
    flush();
    session_write_close();
   
    updateNetworkBuildStatus($networkNum,5);
 
    $array = [];
 
     array_push($array, "New-Item -ItemType directory -Path 'D:\Hyper-V\\" . $networkID . "\Virtual Hard Disks' | Out-Null");
     array_push($array, "Copy-Item -Path 'D:\Images\\" . $image . ".vhdx' -Destination 'D:\Hyper-V\\" . $networkID . "\Virtual Hard Disks\\" . $networkID . ".vhdx' | Out-Null");
     array_push($array, "New-VM -Name '" . $networkID . "' -MemoryStartupBytes 1024MB -Generation 1 -Path 'D:\Hyper-V\' | Out-Null");
     array_push($array, "Set-VMProcessor '" . $networkID . "' -Count 1 -Reserve 10 -Maximum 75 -RelativeWeight 200 | Out-Null");
     array_push($array, "Set-VMMemory '" . $networkID . "' -DynamicMemoryEnabled 1 -MinimumBytes 64MB -StartupBytes 1024MB -MaximumBytes 1024MB -Priority 80 -Buffer 25 | Out-Null");
     array_push($array, "Add-VMHardDiskDrive -VMName '" . $networkID . "' -Path 'D:\Hyper-V\\" . $networkID . "\Virtual Hard Disks\\" . $networkID . ".vhdx' | Out-Null");
     array_push($array, "Get-VMSwitch 'External' | Connect-VMNetworkAdapter -VMName '" . $networkID . "' | Out-Null");
     array_push($array, "New-VMSwitch -name '" . $networkID . "' -SwitchType Private | Out-Null");
     array_push($array, "Add-VMNetworkAdapter -VMName '" . $networkID . "' -SwitchName '" . $networkID . "' | Out-Null");
     array_push($array, "Get-VMNetworkAdapter -VMName '" . $networkID . "' | Where-Object {\$_.SwitchName -eq 'External'} | Set-VMNetworkAdapter -MacAddressSpoofing On | Out-Null");
     array_push($array, "Get-VM -Name '" . $networkID . "' | Get-VMIntegrationService | Enable-VMIntegrationService | Out-Null");
     array_push($array, "Get-VM -Name '" . $networkID . "' | Enable-VMResourceMetering | Out-Null");
     array_push($array, "Start-VM -Name '" . $networkID . "' | Out-Null");

     updateNetworkBuildStatus($networkNum,20);
     pscmd($array);
     $array = [];

     sleep(60);
     array_push($array, "Get-VM -Name '" . $networkID . "' | Select -ExpandProperty NetworkAdapters | Select IPAddresses");
 
     $ipAddresses = pscmd($array);
     $ipAddresses = json_decode($ipAddresses,true);

     $wanIP = $ipAddresses[0]['IPAddresses'][0];
      
     updateNetworkBuildStatus($networkNum,60);

     routerInitialSetup($networkNum,$networkID,$routerPassword,$wanIP,$name,$networkIP,$subnetMask,$remotePublicIP,$remoteNetworkIP,$remoteSubnetMask,$psk,$l2tp_pass);

     updateNetworkBuildStatus($networkNum,100);

    }
}

function db_updateProjects(){
    $array = [];
    array_push($array, "Get-VMGroup Projects | Select-Object -ExpandProperty VMGroupMembers");
    return pscmd($array);
}

function db_getNetworkNames(){
    $array = [];
    array_push($array, "Get-VMGroup Networks | Select-Object -ExpandProperty VMGroupMembers | Select-Object -ExpandProperty Name");
    return pscmd($array);
}

function db_getHostTotalDiskSpace(){
    $array = [];
    array_push($array, 'Get-WmiObject Win32_LogicalDisk | Foreach-Object {$_.Size}');
    return pscmd($array);
}

function db_getHostFreeDiskSpace(){
    $array = [];
    array_push($array, 'Get-WmiObject Win32_LogicalDisk | Foreach-Object {$_.FreeSpace}');
    return pscmd($array);
}

function db_getHostTotalMemory(){
    $array = [];
    array_push($array, "[math]::Round((Get-WmiObject -Class Win32_ComputerSystem).TotalPhysicalMemory/1MB)");
    return pscmd($array);
}

function db_getHostFreeMemory(){
    $array = [];
    array_push($array, "Get-Counter '\Memory\Available MBytes' | Select-Object -ExpandProperty CounterSamples | Select-Object -ExpandProperty CookedValue");
    return pscmd($array);
}

function db_addNewVMGroupToProjects($VMGroupName){
    $array = [];
    array_push($array, "New-VMGroup -Name '" . $VMGroupName . "' -GroupType VMCollectionType");
    array_push($array, "Add-VMGroupMember -Name 'Projects' -VMGroupMember '" . $VMGroupName . "'");
    pscmd($array);
}

function db_AttachNetwork($VMNetwork,$machineID){
  $machineID = getMachineIDForVM($machineID);
  echo commandPrep("Connecting To Network...","Get-VMSwitch '" . $VMNetwork . "' | Connect-VMNetworkAdapter -VMName '" . $machineID . "' | Out-Null",$machineID);
}

// function sendTerminalCommand($command){
//     $command = $command;
//     $array = [];
//     array_push($array, $command);
//     return pscmd($array);
// }

function db_getBandwidthUsage($networkId){
    $array = [];
    array_push($array, 'Get-VM -Name "' . $networkId . '" | Measure-VM | ForEach-Object { (($_.NetworkMeteredTrafficReport | where { $_.Direction -eq "Inbound" }).TotalTraffic | Measure-Object -Sum).Sum }');
    array_push($array, 'Get-VM -Name "' . $networkId . '" | Measure-VM | ForEach-Object { (($_.NetworkMeteredTrafficReport | where { $_.Direction -eq "Outbound" }).TotalTraffic | Measure-Object -Sum).Sum }');
    return pscmd($array);
}

function db_resetBandwidthUsage($networkId){
    $array = [];
    array_push($array, 'Get-VM -Name "' . $networkId . '" | Reset-VMResourceMetering -ErrorAction SilentlyContinue');
    return pscmd($array);
}

function db_createDeployScript($machineID,$image,$name,$project,$password){
  
    $project = getProjectName($project);
    $name = cleanName($name);
    
    $array = [];
    
    switch ($image) {

    case 1:
    array_push($array, "Set-Content -Path 'C:\Deploy\\${machineID}.xml' -Value '<?xml version=\"1.0\" encoding=\"utf-8\"?>
    <unattend xmlns=\"urn:schemas-microsoft-com:unattend\">
    <settings pass=\"windowsPE\">
    <component name=\"Microsoft-Windows-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <UserData>
    <AcceptEula>true</AcceptEula>
    <FullName>Administrator</FullName>
    <Organization>${project}</Organization>
    <ProductKey>
    <Key>TNK62-RXVTB-4P47B-2D623-4GF74</Key>
    </ProductKey>
    </UserData>
    <EnableFirewall>false</EnableFirewall>
    </component>
    <component name=\"Microsoft-Windows-International-Core-WinPE\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SetupUILanguage>
    <UILanguage>en-US</UILanguage>
    </SetupUILanguage>
    <InputLocale>0c09:00000409</InputLocale>
    <SystemLocale>en-US</SystemLocale>
    <UILanguage>en-US</UILanguage>
    <UILanguageFallback>en-US</UILanguageFallback>
    <UserLocale>en-GB</UserLocale>
    </component>
    </settings>
    <settings pass=\"offlineServicing\">
    <component name=\"Microsoft-Windows-LUA-Settings\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <EnableLUA>false</EnableLUA>
    </component>
    </settings>
    <settings pass=\"generalize\">
    <component name=\"Microsoft-Windows-Security-SPP\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SkipRearm>1</SkipRearm>
    </component>
    </settings>
    <settings pass=\"specialize\">
    <component name=\"Microsoft-Windows-International-Core\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <InputLocale>0809:00000809</InputLocale>
    <SystemLocale>en-GB</SystemLocale>
    <UILanguage>en-GB</UILanguage>
    <UILanguageFallback>en-GB</UILanguageFallback>
    <UserLocale>en-GB</UserLocale>
    </component>
    <component name=\"Microsoft-Windows-Security-SPP-UX\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SkipAutoActivation>true</SkipAutoActivation>
    </component>
    <component name=\"Microsoft-Windows-SQMApi\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <CEIPEnabled>0</CEIPEnabled>
    </component>
    <component name=\"Microsoft-Windows-Shell-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <ComputerName>${name}</ComputerName>
    <ProductKey>TNK62-RXVTB-4P47B-2D623-4GF74</ProductKey>
    </component>
    </settings>
    <settings pass=\"oobeSystem\">
    <component name=\"Microsoft-Windows-Shell-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <AutoLogon>
    <Password>
    <Value>${password}</Value>
    <PlainText>true</PlainText>
    </Password>
    <Enabled>true</Enabled>
    <Username>Administrator</Username>
    </AutoLogon>
    <OOBE>
    <HideEULAPage>true</HideEULAPage>
    <HideLocalAccountScreen>true</HideLocalAccountScreen>
    <HideOEMRegistrationScreen>true</HideOEMRegistrationScreen>
    <HideOnlineAccountScreens>true</HideOnlineAccountScreens>
    <HideWirelessSetupInOOBE>true</HideWirelessSetupInOOBE>
    <NetworkLocation>Work</NetworkLocation>
    <ProtectYourPC>1</ProtectYourPC>
    <SkipMachineOOBE>true</SkipMachineOOBE>
    <SkipUserOOBE>true</SkipUserOOBE>
    </OOBE>
    <RegisteredOrganization>${project}</RegisteredOrganization>
    <RegisteredOwner>${project}</RegisteredOwner>
    <DisableAutoDaylightTimeSet>false</DisableAutoDaylightTimeSet>
    <TimeZone>GMT Standard Time</TimeZone>
    <UserAccounts>
    <AdministratorPassword>
    <Value>${password}</Value>
    <PlainText>true</PlainText>
    </AdministratorPassword>
    <LocalAccounts>
    <LocalAccount wcm:action=\"add\">
    <Description>Administrator</Description>
    <DisplayName>Administrator</DisplayName>
    <Group>Administrators</Group>
    <Name>Administrator</Name>
    </LocalAccount>
    </LocalAccounts>
    </UserAccounts>
    </component>
    </settings>
    </unattend>'");
    break;

    case 2:
    array_push($array, "Set-Content -Path 'C:\Deploy\\${machineID}.xml' -Value '<?xml version=\"1.0\" encoding=\"utf-8\"?>
    <unattend xmlns=\"urn:schemas-microsoft-com:unattend\">
    <settings pass=\"windowsPE\">
    <component name=\"Microsoft-Windows-International-Core-WinPE\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SetupUILanguage>
    <UILanguage>en-US</UILanguage>
    </SetupUILanguage>
    <InputLocale>0809:00000809</InputLocale>
    <SystemLocale>en-US</SystemLocale>
    <UILanguage>en-US</UILanguage>
    <UILanguageFallback>en-US</UILanguageFallback>
    <UserLocale>en-GB</UserLocale>
    </component>
    <component name=\"Microsoft-Windows-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <UserData>
    <AcceptEula>true</AcceptEula>
    <FullName>Administrator</FullName>
    <Organization>${project}</Organization>
    </UserData>
    <EnableFirewall>false</EnableFirewall>
    </component>
    </settings>
    <settings pass=\"offlineServicing\">
    <component name=\"Microsoft-Windows-LUA-Settings\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <EnableLUA>false</EnableLUA>
    </component>
    </settings>
    <settings pass=\"generalize\">
    <component name=\"Microsoft-Windows-Security-SPP\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SkipRearm>1</SkipRearm>
    </component>
    </settings>
    <settings pass=\"specialize\">
    <component name=\"Microsoft-Windows-International-Core\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <InputLocale>0809:00000809</InputLocale>
    <SystemLocale>en-GB</SystemLocale>
    <UILanguage>en-GB</UILanguage>
    <UILanguageFallback>en-GB</UILanguageFallback>
    <UserLocale>en-GB</UserLocale>
    </component>
    <component name=\"Microsoft-Windows-Security-SPP-UX\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SkipAutoActivation>true</SkipAutoActivation>
    </component>
    <component name=\"Microsoft-Windows-SQMApi\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <CEIPEnabled>0</CEIPEnabled>
    </component>
    <component name=\"Microsoft-Windows-Shell-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <ComputerName>${name}</ComputerName>
    <ProductKey>W3GGN-FT8W3-Y4M27-J84CP-Q3VJ9</ProductKey>
    </component>
    </settings>
    <settings pass=\"oobeSystem\">
    <component name=\"Microsoft-Windows-Shell-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <AutoLogon>
    <Password>
    <Value>${password}</Value>
    <PlainText>true</PlainText>
    </Password>
    <Enabled>true</Enabled>
    <Username>Administrator</Username>
    </AutoLogon>
    <OOBE>
    <HideEULAPage>true</HideEULAPage>
    <HideOEMRegistrationScreen>true</HideOEMRegistrationScreen>
    <HideOnlineAccountScreens>true</HideOnlineAccountScreens>
    <HideWirelessSetupInOOBE>true</HideWirelessSetupInOOBE>
    <NetworkLocation>Work</NetworkLocation>
    <ProtectYourPC>1</ProtectYourPC>
    <SkipUserOOBE>true</SkipUserOOBE>
    <SkipMachineOOBE>true</SkipMachineOOBE>
    </OOBE>
    <UserAccounts>
    <LocalAccounts>
    <LocalAccount wcm:action=\"add\">
    <Password>
    <Value>${password}</Value>
    <PlainText>true</PlainText>
    </Password>
    <Description></Description>
    <DisplayName>Administrator</DisplayName>
    <Group>Administrators</Group>
    <Name>Administrator</Name>
    </LocalAccount>
    </LocalAccounts>
    </UserAccounts>
    <RegisteredOrganization>${project}</RegisteredOrganization>
    <RegisteredOwner>${project}</RegisteredOwner>
    <DisableAutoDaylightTimeSet>false</DisableAutoDaylightTimeSet>
    <TimeZone>GMT Standard Time</TimeZone>
    <VisualEffects>
    <SystemDefaultBackgroundColor>14</SystemDefaultBackgroundColor>
    </VisualEffects>
    </component>
    <component name=\"Microsoft-Windows-ehome-reg-inf\" processorArchitecture=\"x86\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"NonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <RestartEnabled>true</RestartEnabled>
    </component>
    <component name=\"Microsoft-Windows-ehome-reg-inf\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"NonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <RestartEnabled>true</RestartEnabled>
    </component>
    </settings>
    </unattend>'");
    break;

    case 3:
    array_push($array, "Set-Content -Path 'C:\Deploy\\${machineID}.xml' -Value '<?xml version=\"1.0\" encoding=\"utf-8\"?>
    <unattend xmlns=\"urn:schemas-microsoft-com:unattend\">
    <settings pass=\"windowsPE\">
    <component name=\"Microsoft-Windows-International-Core-WinPE\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SetupUILanguage>
    <UILanguage>en-US</UILanguage>
    </SetupUILanguage>
    <InputLocale>0809:00000809</InputLocale>
    <SystemLocale>en-US</SystemLocale>
    <UILanguage>en-US</UILanguage>
    <UILanguageFallback>en-US</UILanguageFallback>
    <UserLocale>en-GB</UserLocale>
    </component>
    <component name=\"Microsoft-Windows-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <UserData>
    <ProductKey>
    <!-- Do not uncomment the Key element if you are using trial ISOs -->
    <!-- You must uncomment the Key element (and optionally insert your own key) if you are using retail or volume license ISOs -->
    <Key></Key>
    <WillShowUI>Never</WillShowUI>
    </ProductKey>
    <AcceptEula>true</AcceptEula>
    <FullName>Administrator</FullName>
    <Organization>${project}</Organization>
    </UserData>
    </component>
    </settings>
    <settings pass=\"offlineServicing\">
    <component name=\"Microsoft-Windows-LUA-Settings\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <EnableLUA>false</EnableLUA>
    </component>
    </settings>
    <settings pass=\"generalize\">
    <component name=\"Microsoft-Windows-Security-SPP\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SkipRearm>1</SkipRearm>
    </component>
    </settings>
    <settings pass=\"specialize\">
    <component name=\"Microsoft-Windows-International-Core\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <InputLocale>0809:00000809</InputLocale>
    <SystemLocale>en-GB</SystemLocale>
    <UILanguage>en-GB</UILanguage>
    <UILanguageFallback>en-GB</UILanguageFallback>
    <UserLocale>en-GB</UserLocale>
    </component>
    <component name=\"Microsoft-Windows-Security-SPP-UX\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <SkipAutoActivation>true</SkipAutoActivation>
    </component>
    <component name=\"Microsoft-Windows-SQMApi\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <CEIPEnabled>0</CEIPEnabled>
    </component>
    <component name=\"Microsoft-Windows-Shell-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <ComputerName>${name}</ComputerName>
    <ProductKey>W269N-WFGWX-YVC9B-4J6C9-T83GX</ProductKey>
    </component>
    </settings>
    <settings pass=\"oobeSystem\">
    <component name=\"Microsoft-Windows-Shell-Setup\" processorArchitecture=\"amd64\" publicKeyToken=\"31bf3856ad364e35\" language=\"neutral\" versionScope=\"nonSxS\" xmlns:wcm=\"http://schemas.microsoft.com/WMIConfig/2002/State\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
    <AutoLogon>
    <Password>
    <Value>${password}</Value>
    <PlainText>true</PlainText>
    </Password>
    <Enabled>true</Enabled>
    <Username>Administrator</Username>
    </AutoLogon>
    <OOBE>
    <HideEULAPage>true</HideEULAPage>
    <HideOEMRegistrationScreen>true</HideOEMRegistrationScreen>
    <HideOnlineAccountScreens>true</HideOnlineAccountScreens>
    <HideWirelessSetupInOOBE>true</HideWirelessSetupInOOBE>
    <NetworkLocation>Work</NetworkLocation>
    <SkipUserOOBE>true</SkipUserOOBE>
    <SkipMachineOOBE>true</SkipMachineOOBE>
    <ProtectYourPC>3</ProtectYourPC>
    </OOBE>
    <UserAccounts>
    <LocalAccounts>
    <LocalAccount wcm:action=\"add\">
    <Password>
    <Value>${password}</Value>
    <PlainText>true</PlainText>
    </Password>
    <Description></Description>
    <DisplayName>Administrator</DisplayName>
    <Group>Administrators</Group>
    <Name>Administrator</Name>
    </LocalAccount>
    </LocalAccounts>
    </UserAccounts>
    <RegisteredOrganization>${project}</RegisteredOrganization>
    <RegisteredOwner>${project}</RegisteredOwner>
    <DisableAutoDaylightTimeSet>false</DisableAutoDaylightTimeSet>
    <FirstLogonCommands>
    <SynchronousCommand wcm:action=\"add\">
    <Description>Control Panel View</Description>
    <Order>1</Order>
    <CommandLine>reg add \"HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Explorer\ControlPanel\" /v StartupPage /t REG_DWORD /d 1 /f</CommandLine>
    <RequiresUserInput>true</RequiresUserInput>
    </SynchronousCommand>
    <SynchronousCommand wcm:action=\"add\">
    <Order>2</Order>
    <Description>Control Panel Icon Size</Description>
    <RequiresUserInput>false</RequiresUserInput>
    <CommandLine>reg add \"HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Explorer\ControlPanel\" /v AllItemsIconView /t REG_DWORD /d 1 /f</CommandLine>
    </SynchronousCommand>
    <SynchronousCommand wcm:action=\"add\">
    <Order>3</Order>
    <RequiresUserInput>false</RequiresUserInput>
    <CommandLine>cmd /C wmic useraccount where name=\"Administrator\" set PasswordExpires=false</CommandLine>
    <Description>Password Never Expires</Description>
    </SynchronousCommand>
    </FirstLogonCommands>
    <TimeZone>GMT Standard Time</TimeZone>
    </component>
    </settings>
    </unattend>'");
    break;

}
  
    pscmd($array);
  }


  function buildLog($output,$file){

    $current = "";
    $file = 'C:/Logs/' . $file . '.txt';
    if(file_exists($file)){ $current = file_get_contents($file); }
    if($output !== "") {$current .= $output . "\n"; }
    file_put_contents($file, $current);

    $error = '"writeErrorStream":  true';
    if(strpos($output, $error) !== false) {
        return true;
    } else { return false; }

  }

  function getBuildLog($file){

    $file = 'C:/Logs/' . $file . '.txt';
    if(file_exists($file)){ 
        return file_get_contents($file);
    }

  }

  function commandPrep($output,$command,$machineID){
    buildLog($output,$machineID);
    $output = "";
    $array = [];
    array_push($array, $command);
    $output = pscmd($array);
    if(buildLog($output,$machineID)) { errorBuild($machineID); };
    $output = "";
    return $output;
  }

function cleanName($name){
  
    $name = preg_replace("/[^a-zA-Z0-9\s]/", "", $name);
    $name = str_replace(" ", "-", $name);
    $name = substr($name,0,14);
  
  return $name;
  
}