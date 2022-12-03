<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Projects</h1>
  </div>

  <div class="row">

    <div class="col-md-12 col-lg-8 col-xl-6">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-project-diagram"></i>&nbsp;&nbsp;Add Project</h6>
        </div>
        <div id="add-Project" class="card-body">
        <div class="row">
          <div class="col-xl-8">
          <div id="addNewProjectForm">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputProjectName">Name</label>
                <input autofocus type="text" class="form-control" id="inputProjectName">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputProjectNotes">Notes</label>
                <textarea class="form-control" id="inputProjectNotes" rows="3"></textarea>
              </div>
            </div>
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="terms" required="">
                <label class="form-check-label" for="terms">
                  Agree to terms and conditions
                </label>
              </div>
            </div>
            <button id="addNewProjectBtn" class="btn btn-success" onclick="addNewProject()">Add Project</button><br><span id="invalid-feedback" class="text-small text-danger"></span>


          </div>
      
      </div>
       <div class="col-xl-4 d-none d-xl-block">
        <div>
          <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_product_tour_foyt.svg" alt="">
          </div>
      </div>
    </div>
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
          <label for="newVMName">New Name</label>
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
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="destroyVM(); $('#destroyVMModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<script src="js/addProject.js"></script>

<?php require_once("footer.php"); ?>