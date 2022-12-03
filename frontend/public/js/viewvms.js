getVMs();
getFailOverStatus();

function getVMs() {

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {

      if(this.responseText !== "false" && this.responseText !== "[]") {

      var arrayLength = JSON.parse(this.responseText).length;
      var response = "";

      if(typeof arrayLength !== 'undefined') {

        for(var i = 0; i < arrayLength; i++) {
          
          response += `<div><nav class="navbar navbar-expand navbar-light bg-light mb-4">`;

          if(JSON.parse(this.responseText)[i].errorBuild == 1) { 
            response += `<i class="fas fa-tv fa-2x text-danger"></i><a style="text-decoration: none;" class="navbar text-danger" href="#collapseCard-` + i + `" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`; } else { 
          if(JSON.parse(this.responseText)[i].state === "Running") {
            response += `<i class="fas fa-tv fa-2x text-success"></i><a style="text-decoration: none;" class="navbar" href="#collapseCard-` + i + `" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`; } else {
          if(JSON.parse(this.responseText)[i].state === "Off") {
            response += `<i class="fas fa-tv fa-2x text-danger"></i><a style="text-decoration: none;" class="navbar text-danger" href="#collapseCard-` + i + `" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`;
              } else {
            response += `<i class="fas fa-tv fa-2x text-primary"></i><a style="text-decoration: none;" class="navbar" href="#collapseCard-` + i + `" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`;  
              } } }

          response += JSON.parse(this.responseText)[i].name;

          response += `</a>`;
          
          if(JSON.parse(this.responseText)[i].buildstatus == 100 && JSON.parse(this.responseText)[i].errorBuild !== 1 ) {
            response += `<ul class="navbar-nav ml-auto">
            <div id="` + JSON.parse(this.responseText)[i].machineid + `-loading" style="display:none" class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <li id="` + JSON.parse(this.responseText)[i].machineid + `-dropdown" class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="vm?id=` + JSON.parse(this.responseText)[i].id + `">View</a>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid  + `';document.getElementById('newVMName').value = '` + JSON.parse(this.responseText)[i].name + `'">Rename</a>
                  <div class="dropdown-divider"></div>`;
          if(JSON.parse(this.responseText)[i].state === "Off" || JSON.parse(this.responseText)[i].state === "Saved") {
            response += `<a class="dropdown-item text-success" href="#" data-toggle="modal" data-target="#turnOnVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Turn On</a>
                    <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Destroy</a>`;
              if(JSON.parse(this.responseText)[i].state !== "Saved") {
                response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#saveVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Save</a>`;
              } else {
                response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteSavedStateVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Delete Saved State</a>`;
              }
          } else {
            response += `<a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#turnOffVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Turn Off</a>
                    <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#restartVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Restart</a>
                    <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#shutdownVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Shutdown</a>`;
            if(JSON.parse(this.responseText)[i].state !== "Saved") {
              response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#saveVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Save</a>`;
            } else {
              response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteSavedStateVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Delete Saved State</a>`;
            }
          }
          response += `</div>
              </li>
            </ul>`; } 
          
          if(JSON.parse(this.responseText)[i].errorBuild == 1 || JSON.parse(this.responseText)[i].buildstatus < 100){
              response += `<ul class="navbar-nav ml-auto">
            <div id="` + JSON.parse(this.responseText)[i].machineid + `-loading" style="display:none" class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          <li id="` + JSON.parse(this.responseText)[i].machineid + `-dropdown" class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="vm?id=` + JSON.parse(this.responseText)[i].id + `">View</a>
      <div class="dropdown-divider"></div>
    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText)[i].machineid + `'">Destroy</a>
            </li>
            </ul>`;
            }

          response += `</nav>
          <div class="collapse" id="collapseCard-` + i + `">
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-3">State</dt>`;
                if(JSON.parse(this.responseText)[i].state === "Off") {
                  response += `<dd class="col-sm-9 text-danger">` + JSON.parse(this.responseText)[i].state + `</dd>`;
                } else {
                  response += `<dd class="col-sm-9">` + JSON.parse(this.responseText)[i].state + `</dd>`;
                }
                response += `
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].status + `</dd>
                <dt class="col-sm-3">Project</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].project + `</dd>
                <dt class="col-sm-3">Image</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].image + `</dd>
                <dt class="col-sm-3">Memory</dt>
                <dd class="col-sm-9">` + (JSON.parse(this.responseText)[i].memory) + `MB</dd>
                <dt class="col-sm-3">CPU Cores</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].cores + ` vCPUs</dd>
                <dt class="col-sm-3">Uptime</dt>
                <dd class="col-sm-9">` +  secondsToDaysHms(JSON.parse(this.responseText)[i].uptime) + `</dd>
                <dt class="col-sm-3">Created</dt>
                <dd class="col-sm-9">` +  JSON.parse(this.responseText)[i].time + `</dd>
              </dl>
            </div>
          </div>
          <div class="row">
          <div class="col-lg-6 mb-4">`;

          if(JSON.parse(this.responseText)[i].errorBuild == 1) {
            response += `<div class="card bg-danger text-white shadow">
                <div class="card-body">
                Error
                  <div class="text-white-50 small">Issue Encountered</div>
                </div>
              </div>
            </div>`; } else {
          if(JSON.parse(this.responseText)[i].buildstatus !== 100) { 
            response += `<div class="card bg-warning text-white shadow">
                <div class="card-body">
                Deploying
                  <div class="text-white-50 small">` + JSON.parse(this.responseText)[i].buildstatus + `%</div>
                </div>
              </div>
            </div>`;
          } else {
            response += `<div class="card bg-`; if(JSON.parse(this.responseText)[i].state === "Running") { response += `success` } else { response += `danger`; } response += ` text-white shadow">
                <div class="card-body">
                ` + JSON.parse(this.responseText)[i].state + `
                  <div class="text-white-50 small">` + JSON.parse(this.responseText)[i].status + `</div>
                </div>
              </div>
            </div>`;
          } }

          response += `<div class="col-lg-6 mb-4">
          <div class="card bg-secondary text-white shadow">
            <div class="card-body">
            System Specs
              <div class="text-white-50 small">` + (JSON.parse(this.responseText)[i].memory) + `MB / ` + JSON.parse(this.responseText)[i].cores + ` vCPU</div>
            </div>
          </div>
        </div>
        </div>
        </div>`;
        }

      } else {

        response += `<nav class="navbar navbar-expand navbar-light bg-light mb-4">`;

          if(JSON.parse(this.responseText).errorBuild == 1) {
            response += `<i class="fas fa-tv fa-2x text-danger"></i><a style="text-decoration: none;" class="navbar text-danger" href="#collapseCard-1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`; } else { 
          if(JSON.parse(this.responseText).state === "Running") {
            response += `<i class="fas fa-tv fa-2x text-success"></i><a style="text-decoration: none;" class="navbar" href="#collapseCard-1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`; } else {
          if(JSON.parse(this.responseText).state === "Off") {
            response += `<i class="fas fa-tv fa-2x text-danger"></i><a style="text-decoration: none;" class="navbar text-danger" href="#collapseCard-1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`;
              } else {
            response += `<i class="fas fa-tv fa-2x text-primary"></i><a style="text-decoration: none;" class="navbar" href="#collapseCard-1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">&nbsp;&nbsp;`;  
              } } }

        response += JSON.parse(this.responseText).name;

        response += `</a>`;
        
        if(JSON.parse(this.responseText).buildstatus == 100) { 
          response += `<ul class="navbar-nav ml-auto">
          <div id="` + JSON.parse(this.responseText).machineid + `-loading" style="display:none" class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
          </div>
            <li id="` + JSON.parse(this.responseText).machineid + `-dropdown" class="nav-item dropdown">
              <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="vm?id=` + JSON.parse(this.responseText).id + `">View</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid  + `';document.getElementById('newVMName').value = '` + JSON.parse(this.responseText).name + `'">Rename</a>
                <div class="dropdown-divider"></div>`;
        if(JSON.parse(this.responseText).state === "Off" || JSON.parse(this.responseText).state === "Saved") {
          response += `<a class="dropdown-item text-success" href="#" data-toggle="modal" data-target="#turnOnVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Turn On</a>
                  <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Destroy</a>`;
            if (JSON.parse(this.responseText).state !== "Saved") {
              response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#saveVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Save</a>`;
            } else {
              response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteSavedStateVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Delete Saved State</a>`;
            }
        } else {
          response += `<a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#turnOffVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Turn Off</a>
                  <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#restartVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Restart</a>
                  <a class="dropdown-item text-primary" href="#" data-toggle="modal" data-target="#shutdownVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Shutdown</a>`;
          if (JSON.parse(this.responseText).state !== "Saved") {
            response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#saveVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Save</a>`;
          } else {
            response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteSavedStateVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Delete Saved State</a>`;
          }
        }
        response += `</div>
            </li>
          </ul>`; }
        
                  
          if(JSON.parse(this.responseText).errorBuild == 1 || JSON.parse(this.responseText).buildstatus < 100){
              response += `<ul class="navbar-nav ml-auto">
            <div id="` + JSON.parse(this.responseText).machineid + `-loading" style="display:none" class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div>
          <li id="` + JSON.parse(this.responseText).machineid + `-dropdown" class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="vm?id=` + JSON.parse(this.responseText).id + `">View</a>
      <div class="dropdown-divider"></div>
    <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyVMModal" onclick="document.getElementById('existingVMName').value = '` + JSON.parse(this.responseText).machineid + `'">Destroy</a>
            </li>
            </ul>`;
            }

        response += `</nav>
        <div class="collapse" id="collapseCard-1">
          <div class="card-body">
            <dl class="row">
            <dt class="col-sm-3">State</dt>`;
            if (JSON.parse(this.responseText).state === "Off") {
              response += `<dd class="col-sm-9 text-danger">` + JSON.parse(this.responseText).state + `</dd>`;
            } else {
              response += `<dd class="col-sm-9">` + JSON.parse(this.responseText).state + `</dd>`;
            }
            response += `
            <dt class="col-sm-3">Status</dt>
            <dd class="col-sm-9">` + JSON.parse(this.responseText).status + `</dd>
            <dt class="col-sm-3">Project</dt>
            <dd class="col-sm-9">` + JSON.parse(this.responseText).project + `</dd>
            <dt class="col-sm-3">Image</dt>
            <dd class="col-sm-9">` + JSON.parse(this.responseText).image + `</dd>
            <dt class="col-sm-3">Memory</dt>
            <dd class="col-sm-9">` + (JSON.parse(this.responseText).memory) + `MB</dd>
            <dt class="col-sm-3">CPU Cores</dt>
            <dd class="col-sm-9">` + JSON.parse(this.responseText).cores + ` vCPUs</dd>
            <dt class="col-sm-3">Uptime</dt>
            <dd class="col-sm-9">` +  secondsToDaysHms(JSON.parse(this.responseText).uptime) + `</dd>
            <dt class="col-sm-3">Created</dt>
            <dd class="col-sm-9">` +  JSON.parse(this.responseText).time + `</dd>
            </dl>
          </div>
        </div>
        <div class="row">
        <div class="col-lg-6 mb-4">`;

        if(JSON.parse(this.responseText).errorBuild == 1) {
          response += `<div class="card bg-danger text-white shadow">
        <div class="card-body">
        Error
        <div class="text-white-50 small">Issue Encountered</div>
        </div>
        </div>
        </div>`;
        } else {
          if(JSON.parse(this.responseText).buildstatus !== 100) {
            response += `<div class="card bg-warning text-white shadow">
        <div class="card-body">
        Deploying
        <div class="text-white-50 small">` + JSON.parse(this.responseText).buildstatus + `%</div>
        </div>
        </div>
        </div>`;
          } else {
            response += `<div class="card bg-`;
            if (JSON.parse(this.responseText).state === "Running") {
              response += `success`
            } else {
              response += `danger`;
            }
            response += ` text-white shadow">
        <div class="card-body">
        ` + JSON.parse(this.responseText).state + `
        <div class="text-white-50 small">` + JSON.parse(this.responseText).status + `</div>
        </div>
        </div>
        </div>`;
          }
        }

        response += `<div class="col-lg-6 mb-4">
        <div class="card bg-secondary text-white shadow">
          <div class="card-body">
          System Specs
            <div class="text-white-50 small">` + (JSON.parse(this.responseText).memory) + `MB / ` + JSON.parse(this.responseText).cores + ` vCPU</div>
          </div>
        </div>
      </div>
      </div>
      </div>`;
      
        arrayLength = 1;

      }

      document.getElementById("running-vms").innerHTML = response;
      } else {  
      document.getElementById("running-vms").innerHTML = "No Virtual Machines.<br><br><ol><li>Start by <a href='addproject'>adding a Project</a>.</li><li>After you have a Project, you can <a href='addnetwork'>add a new Virtual Network</a>.</li><li>When you have a Virtual Network, you can <a href='addvm'> add a Virtual Machine</a>.</li></ol>"; }
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getVMs");

}

function renameVM() {

  var name = document.getElementById("existingVMName").value;
  var newName = document.getElementById("newVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("renameVM=" + name + "&newVMName=" + newName);

}

function destroyVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("destroyVM=" + name);

}

function turnOnVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("turnOnVM=" + name);

}


function turnOffVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("turnOffVM=" + name);

}


function restartVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("restartVM=" + name);

}


function shutdownVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("shutdownVM=" + name);

}


function turnOffVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("turnOffVM=" + name);

}

function saveVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("saveVM=" + name);

}

function deleteSavedStateVM() {

  var name = document.getElementById("existingVMName").value;
  
  document.getElementById(name + "-dropdown").style = "display:none";
  document.getElementById(name + "-loading").style = "";

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      getVMs();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("deleteSaveVM=" + name);

}


function secondsToDaysHms(d) {
  if(d < 1) { return "0 Seconds"; }
  d = Number(d);
  var days = Math.floor(d / 86400);
  var h = Math.floor(d % 86400 / 3600);
  var m = Math.floor(d % 3600 / 60);
  var s = Math.floor(d % 3600 % 60);

  var daysDisplay = days > 0 ? days + (days == 1 ? " Day, " : " Days, ") : "";
  var hDisplay = h > 0 ? h + (h == 1 ? " Hour, " : " Hours, ") : "";
  var mDisplay = m > 0 ? m + (m == 1 ? " Minute, " : " Minutes, ") : "";
  var sDisplay = s > 0 ? s + (s == 1 ? " Second" : " Seconds") : "";
  return daysDisplay + hDisplay + mDisplay + sDisplay; 
}

function getFailOverStatus() {

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      if(JSON.parse(this.responseText).PhysicalHostName != "JSPC-CLOUD") { document.getElementById("currentVMHeaderText").innerHTML = `<i class="fas fa-tv"></i>&nbsp;&nbsp;Current VMs - <span class="text-danger">Redundancy Mode</span>` }
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getFailOverStatus");

}