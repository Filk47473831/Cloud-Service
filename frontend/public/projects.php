<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Projects</h1>
  </div>

  <div class="row">

    <div class="col-xl-6 col-lg-8 col-md-12">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-project-diagram"></i>&nbsp;&nbsp;Current Projects</h6>
        </div>
        <div id="current-projects" class="card-body">
          <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>

<input hidden id="existingProjectName">

<div class="modal fade" id="renameProjectModal" tabindex="-1" role="dialog" aria-labelledby="renameProjectModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renameProjectModalLabel">Rename Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="newProjectName">New Name</label>
          <input id="newProjectName" type="text" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button id="saveNewProjectNameBtn" type="button" class="btn btn-success"
          onclick="renameProject(); $('#renameProjectModal').modal('hide')">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="removeProjectModal" tabindex="-1" role="dialog" aria-labelledby="removeProjectModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="removeProjectModalLabel">Remove Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Remove this Project?</p>
          <p class="text-danger">Warning: Action is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-success"
          onclick="removeProject(); $('#removeProjectModal').modal('hide')">Yes</button>
      </div>
    </div>
  </div>
</div>

<script src="js/viewprojects.js"></script>

<?php require_once("footer.php"); ?>