<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Virtual Machines</h1>
  </div>

  <div class="row">

    <div class="col-md-12 col-lg-10 col-xl-6">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tv"></i>&nbsp;&nbsp;Add VM</h6>
        </div>
        <div class="progress" style="border-radius:.0rem !important;">
          <div id="addVMProgress" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
        <div id="add-vm" class="card-body">
          <div class="row">
          <div class="col-xl-8">
            <div id="addNewVMForm">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputVMName">Name</label>
                  <input autofocus type="text" class="form-control" id="inputVMName">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputVMProject">Project</label>
                  <select id="inputVMProject" class="form-control">
                    <?php echo getProjectNamesForSelect(); ?>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputVMNetwork">Network</label>
                  <select id="inputVMNetwork" class="form-control">
                    <?php echo getNetworkNamesForSelect(); ?>
                  </select>
                </div>
              </div>
              <!-- <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputVMNotes">Notes</label>
                  <textarea class="form-control" id="inputVMNotes" rows="3"></textarea>
                </div>
              </div> -->
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="inputVMImage">Image</label>
                  <select id="inputVMImage" class="form-control">
                      <?php echo getImageNamesForSelect(); ?>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputVMMemory">Memory</label>
                  <select id="inputVMMemory" class="form-control">
                    <option value="null" selected>Choose...</option>
                    <option value="2048">2048MB</option>
                    <option value="4096">4096MB</option>
                    <option value="8192">8192MB</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="inputVMCPU">CPU</label>
                  <select id="inputVMCPU" class="form-control">
                    <option value="null" selected>Choose...</option>
                    <option value="2">2 Core</option>
                    <option value="4">4 Core</option>
                    <option value="8">8 Core</option>
                  </select>
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
              <button class="btn btn-success" onclick="addNewVM()">Deploy VM</button><p id="deployStatus" class="inlineElement"></p><br><span id="invalid-feedback" class="text-small text-danger"></span>


            </div>
          </div>
          <div class="col-xl-4 d-none d-xl-block">
            <div>
                  <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_server_q2pb.svg" alt="">
            </div>
          </div>  
          </div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>

<script>var currentVMNames = <?php echo getCurrentVMNames(); ?></script>
<script src="js/addvm.js"></script>

<?php require_once("footer.php"); ?>