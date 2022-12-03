<?php require_once("header.php"); ?>
<?php ratelimitclear(); ?>

<div class="container-fluid">

<div class="d-sm-flex align-items-center justify-content-between mb-8">
    <div class="h3 col-12 mb-3 text-gray-800">Virtual Machines
<!--       <div class="h3 col-12 mb-3 text-gray-800">Virtual Machines&nbsp;&nbsp;&nbsp;&nbsp;<a id="oneClickDeployBtn" href="#" class="btn btn-primary" role="button"><i class="fas fa-tv"></i></a> -->
<!--       &nbsp;<a href="#" class="btn btn-secondary" role="button"><i class="fas fa-cog"></i></a> -->
  </div>
  </div>

<div class="row">
<div class="col-sm-12 col-md-12 col-lg-6 col-xl-5">

  <div class="row">

    <div class="col-12">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 id="currentVMHeaderText" class="m-0 font-weight-bold text-primary"><i class="fas fa-tv"></i>&nbsp;&nbsp;Current VMs</h6>
        </div>
        <div id="running-vms" class="card-body">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>

    </div>

  </div>


  <div class="row" style="padding-top:25px;"> </div>

</div>

<div class="col-sm-12 col-md-12 col-lg-6 col-xl-5 d-lg-block">

    <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Latest Updates</h6>
    </div>
    <div class="card-body">
      <div class="text-center">
        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_posting_photo.svg" alt="">
      </div>
      <p>Cloud Technology is improving rapidly. Add some quality to your network, <a rel="nofollow" href="addproject">add a new Project now</a> - an increasing number of options are available!</p>
      <a target="_blank" rel="nofollow" href="https://www.jspc.co.uk/">Brought to you by JSPC Computer Services &rarr;</a>
    </div>
  </div>

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">1-Click Deployment</h6>
    </div>
    <div class="card-body">
      <div class="text-center">
        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_fast_loading_0lbh.svg" alt="">
      </div>
      <p>Speed is key, <a rel="nofollow" href="addproject">add a new Project now</a> - and have full access in minutes!</p>
      <a target="_blank" rel="nofollow" href="https://www.jspc.co.uk/">Brought to you by JSPC Computer Services &rarr;</a>
    </div>
  </div>



</div>
</div>

</div>

</div>


<input hidden id="existingVMName">


<div class="modal fade" id="renameVMModal" tabindex="-1" role="dialog" aria-labelledby="renameVMModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renameVMModalLabel">Rename VM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="newVMName">Name</label>
          <input id="newVMName" type="text" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="saveNewVMNameBtn" type="button" class="btn btn-success"
          onclick="renameVM(); $('#renameVMModal').modal('hide')">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="turnOnVMModal" tabindex="-1" role="dialog" aria-labelledby="turnOnVMModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="turnOnVMModalLabel">VM Power</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Turn on this VM?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="turnOnVM(); $('#turnOnVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="turnOffVMModal" tabindex="-1" role="dialog" aria-labelledby="turnOffVMModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="turnOffVMModalLabel">VM Power</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Turn off this VM?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="turnOffVM(); $('#turnOffVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="restartVMModal" tabindex="-1" role="dialog" aria-labelledby="restartVMModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="restartVMModalLabel">VM Power</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Restart this VM?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="restartVM(); $('#restartVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="shutdownVMModal" tabindex="-1" role="dialog" aria-labelledby="shutdownVMModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shutdownVMModalLabel">VM Power</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Shutdown this VM?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="shutdownVM(); $('#shutdownVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="saveVMModal" tabindex="-1" role="dialog" aria-labelledby="saveVMModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="saveVMModalLabel">VM Power</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Save this VM?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success" onclick="saveVM(); $('#saveVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteSavedStateVMModal" tabindex="-1" role="dialog"
  aria-labelledby="deleteSavedStateVMModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteSavedStateVMModalLabel">VM Power</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Delete the saved state for this VM?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="deleteSavedStateVM(); $('#deleteSavedStateVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="destroyVMModal" tabindex="-1" role="dialog" aria-labelledby="destroyVMModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroyVMModalLabel">Destroy VM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Destroy VM?</p>
          <p class="text-danger">Warning: Destruction is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-danger"
          onclick="destroyVM(); $('#destroyVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<script src="js/viewvms.js"></script>

<?php require_once("footer.php"); ?>