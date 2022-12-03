<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Virtual Machines</h1>
  </div>

  <div class="row">

  <?php if($_GET){ {

    $VmID = $_GET['id'];
    if(getVMStatusDB($VmID)) { $VMStatus = getVMStatusDB($VmID); } else { header("Location: /"); }
    if(!checkPermission($_SESSION['id'],3,$_GET['id'])) { header("Location: /"); }
    
    } } else { header("Location: /"); } ?>

    <input hidden id="percentComplete" value="<?php echo htmlspecialchars($VMStatus['buildstatus']); ?>">
    <input hidden id="VmID" value="<?php echo htmlspecialchars($VMStatus['id']); ?>">

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">

      <div id="cardVMMain" class="card position-relative <?php if($VMStatus['errorBuild'] == 1) { echo "border-bottom-danger"; } else { echo "border-bottom-success"; } ?>">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-hdd"></i>&nbsp;&nbsp;<?php echo htmlspecialchars($VMStatus['name']); ?><span class="float-right"><i id="updatebtn" onclick="getVMStatus()" style="cursor:pointer;" class="fas fa-sync"></i></span></h6>
        </div>
        <div class="progress" style="border-radius:.0rem !important;">
          <div id="deployProgress" class="<?php if($VMStatus['buildstatus'] == 100 && $VMStatus['errorBuild'] !== 1) { echo "progress-bar bg-success"; } else { 
            
            if($VMStatus['errorBuild'] == 1) { 
              echo "progress-bar bg-danger";
            } else {
              echo "progress-bar progress-bar-striped progress-bar-animated bg-success"; } }; ?>" role="progressbar" aria-valuenow="<?php echo htmlspecialchars($VMStatus['buildstatus']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo htmlspecialchars($VMStatus['buildstatus']); ?>%"></div>
        </div>
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs scroll-nav-tabs">
              <li class="nav-item">
                <a id="infoTabBtn" onclick="infoTab()" class="nav-link active" href="#">Info</a>
              </li>
              <li id="networkTabBtnListItem" class="nav-item">
                <a id="networkTabBtn" onclick="networkTab()" class="nav-link disabled" href="#">Network</a>
              </li>
              <!-- <li class="nav-item">
                <a id="statusTabBtn" onclick="statusTab()" class="nav-link" href="#">Status</a>
              </li> -->
              <li id="storageTabBtnListItem" class="nav-item">
                <a id="storageTabBtn" onclick="storageTab()" class="nav-link disabled" href="#">Disks</a>
              </li>
              <li hidden class="nav-item">
                <a id="checkpointsTabBtn" onclick="checkpointsTab()" class="nav-link disabled" href="#">Checkpoints</a>
              </li>
              <li class="nav-item">
                <a id="monitoringTabBtn" onclick="monitoringTab()" class="nav-link disabled" href="#">Monitoring</a>
              </li>
<!--          <li id="thumbnailTabBtnListItem" class="nav-item">
                <a id="thumbnailTabBtn" onclick="" class="nav-link disabled" href="#">Preview</a>
              </li> -->
            </ul>
          </div>
        <div id="view-vm" class="card-body">
        <div class="row">
            <div id="infoTab" class="col-12">
              <p id="deploymentInfoText" class="<?php if($VMStatus['errorBuild'] == 1) { echo "text-danger"; } else { echo "text-success"; } ?>">Deployment Info</p>
              <dl class="row">
                <dt class="col-sm-4">State</dt>
                <dd id="currentState" class="col-sm-8"><?php echo htmlspecialchars($VMStatus['state']); ?></dd>
                <dt class="col-sm-4">Status</dt>
                <dd id="deployStatus" class="col-sm-8"><?php if($VMStatus['errorBuild'] == 1) { echo "Error During Build"; } else { echo htmlspecialchars($VMStatus['status']); } ?></dd>
                <!-- <dt class="col-sm-4">Name</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($VMStatus['name']); ?></dd> -->
                <dt class="col-sm-4">Machine ID</dt>
                <dd id="machineId" class="col-sm-8"><?php echo htmlspecialchars($VMStatus['machineid']); ?></dd>
                <dt class="col-sm-4">Project</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($VMStatus['project']); ?></dd>
                <dt class="col-sm-4">Image</dt>
                <dd id="imageName" class="col-sm-8"><?php echo htmlspecialchars($VMStatus['image']); ?></dd>
                <dt class="col-sm-4">Memory</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($VMStatus['memory']); ?>MB</dd>
                <dt class="col-sm-4">CPU Cores</dt>
                <dd class="col-sm-8"><?php echo htmlspecialchars($VMStatus['cores']); ?> vCPUs</dd>
                <dt class="col-sm-4">IP Address</dt>
                <dd id="ipaddr" class="col-sm-8"><?php echo htmlspecialchars($VMStatus['ipaddr']); ?></dd>
                <dt class="col-sm-4">Username</dt>
                <dd class="col-sm-8">administrator</dd>
                <dt class="col-sm-4">Password</dt>
                <dd id="password" class="col-sm-8"><?php echo htmlspecialchars($VMStatus['password']); ?></dd>
                <dt class="col-sm-4">Uptime</dt>
                <dd id="uptime" class="col-sm-8"><?php echo secondsToDaysHms(htmlspecialchars($VMStatus['uptime'])); ?></dd>
                <dt class="col-sm-4">Created</dt>
                <dd id="created" class="col-sm-8"><?php echo htmlspecialchars($VMStatus['time']); ?></dd>
              </dl>
              <a id="connectBtn" href="#" class="btn btn-primary disabled" role="button">Connect File</a>
             </div>
            <div id="storageTab" style="display:none" class="col-12">
              <p id="deploymentStorageText" class="<?php if($VMStatus['errorBuild'] == 1) { echo "text-danger"; } else { echo "text-success"; } ?>">Attached Virtual Disks</p>
                <div class="row">
                  <div class="form-group col-md-12">
                    <?php getAttachedStorageForVM($VmID); ?>
                    <a id="attachStorageBtn" href="#" data-toggle="modal" data-backdrop="static" data-target="#attachStorageModal"  class="btn btn-primary" role="button">Attach Virtual Disk</a>
                  </div>
                </div>
             </div>
             <div id="networkTab" style="display:none" class="col-12">
              <p id="deploymentNetworkText" class="<?php if($VMStatus['errorBuild'] == 1) { echo "text-danger"; } else { echo "text-success"; } ?>">Attached Virtual Network</p>
                <div class="row">
                  <div class="form-group col-md-12">
                    <?php getAttachedNetworkForVM($VmID); ?>
                    <a id="attachNetworkBtn" href="#" data-toggle="modal" data-backdrop="static" data-target="#attachNetworkModal"  class="btn btn-primary" role="button">Attach Network</a>
                  </div>
                </div>
             </div>
             <div id="checkpointsTab" style="display:none" class="col-12">
                <p class="text-success">Checkpoints</p>
                    <div class="row">
                      <div class="form-group col-md-12">
                      <?php getCheckpointsForVM($VmID); ?>
                      <?php if(getNumberOfCheckpointsForVM($VmID) < 3) { ?><a id="addCheckpointBtn" href="#" data-toggle="modal" data-backdrop="static" data-target="#addCheckpointModal"  class="btn btn-primary" role="button">Add Checkpoint</a><?php } ?>
                      </div>
                    </div>
                <p class="text-success">Automatic Backup</p>
                    <div class="row">
                      <div class="form-group col-md-12">
                      <?php if(strtotime(date("Y-m-d h:i:s")) - strtotime($VMStatus['time']) > 93000) { ?><a id="restoreFromDailyBtn" href="#" data-toggle="modal" data-backdrop="static" data-target="#restoreFromDailyModal"  class="btn btn-success" role="button">Restore</a><?php } ?>
                      </div>
                    </div>
                </div>
                <!-- <div id="statusTab" style="display:none" class="col-12">
                <p class="<?php if($VMStatus['errorBuild'] == 1) { echo "text-danger"; } else { echo "text-success"; } ?>">Deployment Status</p>
                    <div class="row">
                      <div class="form-group col-md-12">
                      <textarea class="form-control terminalOutput" id="deploymentTerminalOutput" rows="40"><?php echo htmlspecialchars($VMStatus['deploymentstatus']); ?></textarea>
                      </div>
                    </div>
                </div> -->
    <!--              <div id="thumbnailTab" style="display:none" class="col-12">
                <p class="<?php if($VMStatus['errorBuild'] == 1) { echo "text-danger"; } else { echo "text-success"; } ?>">Deployment Preview</p>
                    <div class="row">
                      <div class="form-group col-md-12">
                      <img id="vmThumbnailImg" style="width:100%" src="img/vm/<?php echo htmlspecialchars($VMStatus['machineid']); ?>.bmp">
                      </div>
                    </div>
                </div> -->
               <div id="monitoringTab" style="display:none" class="col-12">
                <p class="<?php if($VMStatus['errorBuild'] == 1) { echo "text-danger"; } else { echo "text-success"; } ?>">Monitoring</p>
                    <div class="row">
                      <div class="form-group col-md-12">
                        <p id="monitoringAgentText">No Agent Installed</p>
                        <dl class="row">
                          <dt class="col-sm-4">CPU Load</dt>
                          <dd id="currentLoad" class="col-sm-8">N/A</dd>
                          <dt class="col-sm-4">RAM Utilisation</dt>
                          <dd id="ramUtilisation" class="col-sm-8">N/A</dd>
                          <dt class="col-sm-4">Disk Utilisation</dt>
                          <dd id="diskUtilisation" class="col-sm-8">N/A</dd>
                          <dt class="col-sm-4">Running Processes</dt>
                          <dd id="runningProcesses" class="col-sm-8">N/A</dd>
                        </dl>
                      </div>
                    </div>
                </div>
            </div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>

<div class="modal fade" id="attachStorageModal" tabindex="-1" role="dialog" aria-labelledby="attachStorageModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="attachStorageModalLabel">Attach Virtual Disk</h5>
      </div>
      <div class="modal-body">
        <div class="form-group col-md-12">
          <div class="form-row">
          <label>Virtual Disk</label>
          <?php echo getDiskNamesAvailableForSelect(); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="attachStorageModalCancelBtn" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="attachStorageModalBtn" type="button" class="btn btn-success"
          onclick="attachDisk()">Attach</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="attachNetworkModal" tabindex="-1" role="dialog" aria-labelledby="attachStorageModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="attachNetworkModalLabel">Attach Virtual Network</h5>
      </div>
      <div class="modal-body">
        <div class="form-group col-md-12">
          <div class="form-row">
          <label>Virtual Network</label>
          <?php echo getNetworksAvailableForSelect(); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="attachNetworkModalCancelBtn" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="attachNetworkModalBtn" type="button" class="btn btn-success"
          onclick="attachNetwork()">Attach</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addCheckpointModal" tabindex="-1" role="dialog" aria-labelledby="addCheckpointModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCheckpointModalLabel">Add Checkpoint</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Add a new Checkpoint for this VM?</p>
          <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputCheckpointName">Name</label>
                <input type="text" class="form-control" id="inputCheckpointName">
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="addCheckpointModalCancelBtn" type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button id="addCheckpointModalBtn" type="button" class="btn btn-success" onclick="addCheckpoint()">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="restoreCheckpointModal" tabindex="-1" role="dialog" aria-labelledby="restoreCheckpointModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="restoreCheckpointModalLabel">Restore Checkpoint</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Restore this Checkpoint?</p>
          <p class="text-danger">Warning: VM current state will be lost. This action is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button id="restoreCheckpointModalCancelBtn" type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button id="restoreCheckpointModalBtn" type="button" class="btn btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="restoreFromDailyModal" tabindex="-1" role="dialog" aria-labelledby="restoreFromDailyModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="restoreFromDailyModalLabel">Restore</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Restore from last automatic backup? (<?php echo date("l",strtotime("-1 days")); ?> at 1am)</p>
          <p class="text-danger">Warning: VM current state will be lost. This action is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button id="restoreFromDailyModalCancelBtn" type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button onclick="restoreCheckpoint('null','<?php echo date("l",strtotime("-1 days")); ?>')" id="restoreFromDailyModalBtn" type="button" class="btn btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="removeCheckpointModal" tabindex="-1" role="dialog" aria-labelledby="removeCheckpointModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="removeCheckpointModalLabel">Remove Checkpoint</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Remove this Checkpoint?</p>
          <p class="text-danger">Warning: Checkpoint will be permanently destroyed. This action is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button id="removeCheckpointModalCancelBtn" type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button id="removeCheckpointModalBtn" type="button" class="btn btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>


<script src="js/viewvm.js"></script>

<?php require_once("footer.php"); ?>
