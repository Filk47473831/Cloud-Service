<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Monitoring</h1>
  </div>

  <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Cloud Host Uptime</h6>
                  <div class="dropdown no-arrow">
                    <!-- <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a> -->
                    <!-- <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div> -->
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="myAreaChart" width="386" height="160" class="chartjs-render-monitor" style="display: block; width: 386px; height: 160px;"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">VM Operating Systems</h6>
                  <div class="dropdown no-arrow">
                    <!-- <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a> -->
                    <!-- <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div> -->
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                    <canvas id="myPieChart" width="386" height="208" class="chartjs-render-monitor" style="display: block; width: 386px; height: 208px;"></canvas>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Windows
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Linux
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Other
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>



<div class="row">

  <div class="col-xl-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Storage Utilisation</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div id="ssdFreeSpace" class="h5 mb-0 mr-3 font-weight-bold text-gray-800">...</div>
                </div>
                <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div id="ssdFreeSpaceBar" class="progress-bar bg-info" role="progressbar" style="width: 0%"
                      aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Host Memory Utilisation</div>
              <div class="row no-gutters align-items-center">
              <div class="col-auto">
                <div id="hostFreeMemory" class="h5 mb-0 mr-3 font-weight-bold text-gray-800">...</div>
              </div>
              <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div id="hostFreeMemoryBar" class="progress-bar bg-info" role="progressbar" style="width: 0%"
                      aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
                </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


  <div class="col-xl-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total VM's</div>
              <div id="total-vms" class="h5 mb-0 font-weight-bold text-gray-800">...</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-desktop fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Est. Energy Usage (Annual)</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">???? kWh</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-charging-station fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>

</div>


<input hidden id="existingVMName">

<script src="js/monitoring.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

<?php require_once("footer.php"); ?>