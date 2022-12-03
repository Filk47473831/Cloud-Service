<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Images</h1>
  </div>

  <div class="row">

    <div class="col-xl-6 col-lg-8 col-md-12">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-file-image"></i>&nbsp;&nbsp;Add Custom Image</h6>
        </div>
        <div class="progress" style="border-radius:.0rem !important;">
          <div id="uploadImageProgress" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
        <div id="add-network" class="card-body">
          <div id="imageUploadForm">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputImageName">Name</label>
                <input disabled type="text" class="form-control" id="inputImageName">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="imageUploadFile">Custom Image (.vhdx)</label>
                <div class="custom-file">
                  <input disabled type="file" class="custom-file-input" id="customFile">
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
                </div>
            </div>
            
            <button disabled class="btn btn-success" onclick="uploadImage()">Upload</button>


          </div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>

<?php require_once("footer.php"); ?>