<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
/**
 * Cloud Portal Control Functions.
 * Version 1.0.
 *
 * @author    Chris Groves <chris@thegaff.co.uk>
 * @copyright 2019 Chris Groves
 */

require_once("../../handlers/main.php");
authenticated();

 if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['HTTP_ORIGIN'] == 'https://cloud.jspc.co.uk') {

    if(isset($_POST['getFailOverStatus'])) {
        echo db_getFailOverStatus();
    }

    if(isset($_POST['sendTerminalCommand'])) {
        db_sendTerminalCommand(escape($_POST['sendTerminalCommand']));
    }

    if(isset($_POST['getVMs'])) {
        echo json_encode(getAllVMStatus());
    }

    if(isset($_POST['renameVM'])) {
        renameVM($_POST['newVMName'],$_POST['renameVM']);
        updateVMStatus(escape($_POST['renameVM']));
    }

    if(isset($_POST['renameNetwork'])) {
        renameNetwork(escape($_POST['newNetworkName']), escape($_POST['renameNetwork']));
    }

    if(isset($_POST['turnOnVM'])) {
        db_turnOnVM(escape($_POST['turnOnVM']));
        updateVMStatus(escape($_POST['turnOnVM']));
    }

    if(isset($_POST['turnOffVM'])) {
        db_turnOffVM(escape($_POST['turnOffVM']));
        updateVMStatus(escape($_POST['turnOffVM']));
    }

    if(isset($_POST['shutdownVM'])) {
        db_shutdownVM(escape($_POST['shutdownVM']));
        updateVMStatus(escape($_POST['shutdownVM']));
    }

    if(isset($_POST['restartVM'])) {
        db_restartVM(escape($_POST['restartVM']));
        updateVMStatus(escape($_POST['restartVM']));
    }

    if(isset($_POST['restartNetwork'])) {
        db_restartVM(escape($_POST['restartNetwork']));
    }

    if(isset($_POST['saveVM'])) {
        db_saveVMState(escape($_POST['saveVM']));
        updateVMStatus(escape($_POST['saveVM']));
    }

    if(isset($_POST['deleteSaveVM'])) {
        db_deleteVMState(escape($_POST['deleteSaveVM']));
        updateVMStatus(escape($_POST['deleteSaveVM']));
    }

    if(isset($_POST['destroyVM'])) {
        db_destroyVM(escape($_POST['destroyVM']));
    }

    if(isset($_POST['destroyNetwork'])) {
        db_destroyNetwork(escape($_POST['destroyNetwork']));
    }

    if(isset($_POST['destroyDisk'])) {
        db_destroyDisk(escape($_POST['destroyDisk']));
    }

    if(isset($_POST['attachDisk'])) {
        db_attachDisk(escape($_POST['attachDisk'],$_POST['VMName']));
    }
   
    if(isset($_POST['attachNetwork'])) {
        attachNetwork(escape($_POST['attachNetwork']),escape($_POST['VMName']));
    }

    if(isset($_POST['addCheckpoint'])) {
        db_checkpointVM(escape($_POST['VMName']),escape($_POST['addCheckpoint']));
    }
    
    if(isset($_POST['removeCheckpoint'])) {
        db_removeCheckpointVM(escape($_POST['removeCheckpoint']),escape($_POST['checkpointName']),escape($_POST['VMName']));
    }

    if(isset($_POST['restoreCheckpoint'])) {
        db_restoreCheckpointVM(escape($_POST['restoreCheckpoint']),escape($_POST['checkpointName']),escape($_POST['VMName']));
    }

    if(isset($_POST['detachDisk'])) {
        db_detachDisk(escape($_POST['detachDisk']));
    }

    if(isset($_POST['addNewVM'])) {
        db_addNewVM(escape($_POST['addNewVM']),escape($_POST['VMProject']),escape($_POST['VMNetwork']),escape($_POST['VMImage']),escape($_POST['VMMemory']),escape($_POST['VMCPU']),escape($_POST['VMPassword']));
    }

    if(isset($_POST['addNewNetwork'])) {
        db_addNewNetwork(escape($_POST['project']),escape($_POST['addNewNetwork']),escape($_POST['networkIP']),escape($_POST['subnetMask']),escape($_POST['remotePublicIP']),escape($_POST['remoteNetworkIP']),escape($_POST['remoteSubnetMask']));
    }

    if(isset($_POST['addNewFirewallRule'])) {
        addNewFirewallRuleDB(escape($_POST['addNewFirewallRule']));
    }

    if(isset($_POST['removeFirewallRule'])) {
        removeFirewallRuleDB(escape($_POST['networkId']),escape($_POST['removeFirewallRule']));
    }

    if(isset($_POST['pushNetworkConfig'])) {
        db_pushNetworkConfig(escape($_POST['pushNetworkConfig']),escape($_POST['name']),escape($_POST['networkIP']),escape($_POST['subnetMask']),escape($_POST['remotePublicIP']),escape($_POST['remoteNetworkIP']),escape($_POST['remoteSubnetMask']),escape($_POST['psk']));
    }

    if(isset($_POST['addNewProject'])) {
        addProject(escape($_POST['addNewProject']));
    }

    if(isset($_POST['addNewDisk'])) {
        db_addNewDisk(escape($_POST['addNewDisk']),escape($_POST['diskSize']));
    }

    if(isset($_POST['renameDisk'])) {
        renameDisk(escape($_POST['diskId']),escape($_POST['renameDisk']));
    }

    if(isset($_POST['renameProject'])) {
        renameProjectDB(escape($_POST['renameProject']),escape($_POST['newProjectName']));
    }

    if(isset($_POST['removeProject'])) {
        removeProjectfromDB(escape($_POST['removeProject']));
    }

    if(isset($_POST['addNewVMGroup'])) {
        db_addNewVMGroupToProjects(escape($_POST['addNewVMGroup']));
    }

    if(isset($_POST['getProjects'])) {
        echo json_encode(getAllProjectStatus());
    }

    if(isset($_POST['getNetworks'])) {
        echo json_encode(getAllNetworkStatus());
    }

    if(isset($_POST['getVMsForNetwork'])) {
        echo json_encode(getVMsForNetwork(escape($_POST['getVMsForNetwork'])));
    }

    if(isset($_POST['getNumberOfVms'])) {
        echo getNumberOfVms();
    }
    
    if(isset($_POST['getVirtualDisks'])) {
        echo json_encode(getAllVirtualDiskStatus());
    }

    if(isset($_POST['getHostFreeDiskSpace'])) {
        echo db_getHostFreeDiskSpace();
    }

    if(isset($_POST['getHostFreeMemory'])) {
        echo db_getHostFreeMemory();
    }

    if(isset($_POST['getVMStatus'],$_POST['getVMStatusDB'])) {
        updateVMStatus(escape($_POST['getVMStatusDB']));
        echo json_encode(getVMStatusDB(escape($_POST['getVMStatus'])));
    }

    if(isset($_POST['getVMUUID'])) {
        echo db_getVMUUID(escape($_POST['getVMUUID']));
    }

    if(isset($_POST['updateVMImg'])) {
        db_updateVMImg(escape($_POST['updateVMImg']));
    }

    if(isset($_POST['updatePassword'])) {
        echo updatePassword(escape($_POST['currentPassword']),escape($_POST['newPassword']),escape($_POST['confirmPassword']));
    }

    if(isset($_POST['getNetworkStatusDB'])) {
        echo json_encode(getNetworkStatusDB(escape($_POST['getNetworkStatusDB'])));
    }

    if(isset($_POST['getBandwidthUsage'])) {
        echo db_getBandwidthUsage(escape($_POST['getBandwidthUsage']));
    }

    if(isset($_POST['resetBandwidthUsage'])) {
        resetBandwidthUsage(escape($_POST['resetBandwidthUsage']));
        db_resetBandwidthUsage(escape($_POST['resetBandwidthUsage']));
    }
   
   if(isset($_POST['readMonitorForVM'])) {
        echo readMonitorForVM(escape($_POST['readMonitorForVM']));
   }
    
} else {

    header("Location: /login");

}
