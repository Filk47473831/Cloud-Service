<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Virtual Machines</h1>
  </div>

  <div class="row">

    <div class="col-xl-8 col-lg-10 col-md-12">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-hdd"></i>&nbsp;&nbsp;Terminal</h6>
        </div>

        <div id="terminal" class="card-body">
          <div id="terminalForm">
            <div class="form-row">
              <div class="form-group col-md-12">
                <input disabled type="text" class="form-control" id="inputTerminalInput">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <textarea disabled class="form-control" id="inputTerminalOutput" rows="35">
                
                <?php
                  
//                   $array = [];
//                   array_push($array, "configure");      
//                   array_push($array, "set nat destination rule 110 description 'MS RDP'");
//                   array_push($array, "set nat destination rule 110 destination address 81.107.205.54");
//                   array_push($array, "set nat destination rule 110 destination port 3389");
//                   array_push($array, "set nat destination rule 110 inbound-interface eth0");
//                   array_push($array, "set nat destination rule 110 protocol tcp");
//                   array_push($array, "set nat destination rule 110 translation address 172.20.100.11");
//                   array_push($array, "set nat destination rule 110 translation port 3389");
//                   array_push($array, "commit");
//                   array_push($array, "save");
//                   array_push($array, "exit");
//                   array_push($array, "exit");
                  
//                   echo "Commands Sent";
                  
//                   echo sshcmd($array,"192.168.0.16");
                  
                    ?>
                
                </textarea>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>


<!-- <script src="terminal.js"></script> -->
<?php require_once("footer.php"); ?>