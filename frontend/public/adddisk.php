<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Virtual Disks</h1>
  </div>

  <div class="row">

    <div class="col-md-12 col-lg-8 col-xl-6">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-hdd"></i>&nbsp;&nbsp;Add Virtual Disk (HDD)</h6>
        </div>
        <div id="add-disk" class="card-body">
        <div class="row">
          <div class="col-xl-8">
          <div id="addNewDiskForm">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputDiskName">Name</label>
                <input autofocus type="text" class="form-control" id="inputDiskName">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputDiskSize">Size</label>
                <p><input type="number" class="numberInputLarge" id="inputDiskSize" value="10000" min="1000" max="30000"> MB</p>
              </div>
            </div>
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="terms" required>
                <label class="form-check-label" for="terms">
                  Agree to terms and conditions
                </label>
              </div>
            </div>
            <button <?php if(894000 - getTotalDiskSpaceInUse() < 40000) { echo "disabled"; } ?> id="addNewDiskBtn" class="btn btn-success" onclick="addNewDisk()">Add Virtual Disk</button><br><span id="invalid-feedback" class="text-small text-danger"><?php if(894000 - getTotalDiskSpaceInUse() < 40000) { echo "Insufficient resources available"; } ?></span>


          </div>
</div>
<div class="col-xl-4 d-none d-xl-block">
<div>
                  <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_data_processing_yrrv.svg" alt="">
            </div>
</div>
</div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>


<script src="js/adddisk.js"></script>

<?php require_once("footer.php"); ?>