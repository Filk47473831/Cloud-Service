<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Virtual Disks</h1>
  </div>

  <div class="row">

    <div class="col-xl-6 col-lg-6 col-md-8">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-hdd"></i>&nbsp;&nbsp;Current Disks</h6>
        </div>
        <div id="current-disks" class="card-body">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>

    </div>

 

</div>

</div>

</div>


<input hidden id="existingDiskID">


<div class="modal fade" id="attachDiskModal" tabindex="-1" role="dialog" aria-labelledby="attachDiskModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="attachDiskModalLabel">Attach Disk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Attach this Disk to:</p>
          <label for="VMName">Virtual Machine</label>
          <select id="VMName" class="form-control">
            <?php echo getVMIdsForSelect(); ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="attachDiskNameBtn" type="button" class="btn btn-success"
          onclick="attachDisk(); $('#attachDiskModal').modal('hide')">Attach</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="detachDiskModal" tabindex="-1" role="dialog" aria-labelledby="detachDiskModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detachDiskModalLabel">Detach Disk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Detach this Disk?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="detachDiskNameBtn" type="button" class="btn btn-success"
          onclick="detachDisk(); $('#detachDiskModal').modal('hide')">Detach</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="renameDiskModal" tabindex="-1" role="dialog" aria-labelledby="renameDiskModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renameDiskModalLabel">Rename Disk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="newDiskName">Name</label>
          <input id="newDiskName" type="text" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="saveNewDiskNameBtn" type="button" class="btn btn-success"
          onclick="renameDisk(); $('#renameDiskModal').modal('hide')">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="destroyDiskModal" tabindex="-1" role="dialog" aria-labelledby="destroyDiskModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroyDiskModalLabel">Destroy Disk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Destroy this Disk?</p>
          <p class="text-danger">Warning: Action is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="destroyDisk(); $('#destroyDiskModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>
<script src="js/viewdisks.js"></script>

<?php require_once("footer.php"); ?>