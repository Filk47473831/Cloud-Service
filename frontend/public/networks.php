<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Virtual Networks</h1>
  </div>

  <div class="row">

    <div class="col-xl-6 col-lg-6 col-md-8">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-network-wired"></i>&nbsp;&nbsp;Current Networks</h6>
        </div>
        <div id="current-networks" class="card-body">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>

    </div>

 

</div>

</div>

</div>


<input hidden id="existingNetworkId">

<div class="modal fade" id="destroyNetworkModal" tabindex="-1" role="dialog" aria-labelledby="destroyNetworkModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroyNetworkModalLabel">Destroy Network</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Destroy Network?</p>
          <p class="text-danger">Warning: Destruction is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-danger"
          onclick="destroyNetwork(); $('#destroyNetworkModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="renameNetworkModal" tabindex="-1" role="dialog" aria-labelledby="renameNetworkModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renameNetworkModalLabel">Rename Network</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="newNetworkName">Name</label>
          <input id="newNetworkName" type="text" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="saveNewNetworkNameBtn" type="button" class="btn btn-success"
          onclick="renameNetwork(); $('#renameNetworkModal').modal('hide')">Save</button>
      </div>
    </div>
  </div>
</div>


<script src="js/viewnetworks.js"></script>

<?php require_once("footer.php"); ?>