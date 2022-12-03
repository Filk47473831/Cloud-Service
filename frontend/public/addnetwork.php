<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Virtual Networks</h1>
  </div>

  <div class="row">

  <div class="col-md-12 col-lg-10 col-xl-6">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-network-wired"></i>&nbsp;&nbsp;Add Network</h6>
        </div>
        <div class="progress" style="border-radius:.0rem !important;">
          <div id="addNetworkProgress" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
        <div id="add-network" class="card-body">
        <div class="row">
          <div class="col-xl-8">
          <div id="addNewNetworkForm">
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputNetworkName">Name</label>
                <input autofocus type="text" class="form-control" id="inputNetworkName">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputNetworkProject">Project</label>
                <select id="inputNetworkProject" class="form-control">
                  <?php echo getProjectNamesForSelect(); ?>
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
                <label for="inputNetworkIP">Local Area Network</label>
                <p>172 . 20 . <input type="number" class="numberInput" id="inputNetworkIP" value="1" min="1" max="254"> . 0 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp; <input type="number" class="numberInput" id="inputSubnetMask" value="24" min="16" max="24"></p>
              </div>
            </div>

            <p class="text-success">LAN to LAN VPN</p>
            
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputWanIP">Remote Network Public IP</label>
                <p><input type="number" class="numberInput" id="inputWanIP1" value="82" min="1" max="254"> . <input type="number" class="numberInput" id="inputWanIP2" value="219" min="1" max="254"> . <input type="number" class="numberInput" id="inputWanIP3" value="1" min="1" max="254"> . <input type="number" class="numberInput" id="inputWanIP4" value="1" min="1" max="254"></p>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="inputNetworkIP">Remote Network Internal</label>
                <p><input type="number" class="numberInput" id="inputWanNetworkIP1" value="172" min="1" max="254"> . <input type="number" class="numberInput" id="inputWanNetworkIP2" value="25" min="1" max="254"> . <input type="number" class="numberInput" id="inputWanNetworkIP3" value="1" min="1" max="254"> . 0 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / &nbsp;&nbsp; <input type="number" class="numberInput" id="inputWanNetworkSubnetMask" value="24" min="8" max="32"></p>
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
            <button class="btn btn-success" onclick="addNewNetwork()">Deploy Network</button><br><span id="invalid-feedback" class="text-small text-danger"></span>


          </div>
    </div>
      <div class="col-xl-4 d-none d-xl-block">
        <div>
        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_connected_world_wuay.svg" alt="">
        </div>
      </div>
    </div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>

<script src="js/addnetwork.js"></script>

<?php require_once("footer.php"); ?>