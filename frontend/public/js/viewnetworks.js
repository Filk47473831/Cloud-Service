function getNetworks() {
  
  var j;
  var vms;

  document.getElementById("current-networks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

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

      if (typeof arrayLength !== 'undefined') {

      for (var i = 0; i < arrayLength; i++) {

        if(JSON.parse(this.responseText)[i].vms !== undefined) {
          
        vms = "";
        for (j = 0; j < JSON.parse(this.responseText)[i].vms.length; j++) {

          vms += `<dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/vm?id=`+ JSON.parse(this.responseText)[i].vms[j].id + `'>` + JSON.parse(this.responseText)[i].vms[j].name + `</a></dd><dt class="col-sm-3"></dt>`;
        }
        }
        
        if(JSON.parse(this.responseText)[i].vms == false) { vms = `<dd class="col-sm-9">No Virtual Machines. <a href="addvm">Add a new VM here</a>.</dd>`; }
        
        response += `<nav class="navbar navbar-expand navbar-light bg-light mb-4">
            <a style="text-decoration: none;" class="navbar" href="#collapseCard-` + i + `" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample"><i class="fas fa-network-wired"></i>&nbsp;&nbsp;`;

        response += JSON.parse(this.responseText)[i].name;

        response += `</a>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="network?id=` + JSON.parse(this.responseText)[i].id + `">View</a>
                  <a class="dropdown-item text-muted" href="#">Edit</a>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameNetworkModal" onclick="document.getElementById('existingNetworkId').value = '` + JSON.parse(this.responseText)[i].networkid  + `'; document.getElementById('newNetworkName').value = '` + JSON.parse(this.responseText)[i].name + `'">Rename</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyNetworkModal" onclick="document.getElementById('existingNetworkId').value = '` + JSON.parse(this.responseText)[i].networkid + `'">Destroy</a>
                  </div>
              </li>
            </ul>
          </nav>
          <div class="collapse" id="collapseCard-` + i + `">
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-3">Project</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].project + `</dd>
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].networkid + `</dd>
                <dt class="col-sm-3">LAN IP</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].lanip + `</dd>
                <dt class="col-sm-3">WAN IP</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].wanip + `</dd>
                <dt class="col-sm-3">VM's</dt>` + vms +
              `</dl>
            </div>
          </div>`;
      }

    } else {
      
        if(JSON.parse(this.responseText).vms !== undefined) {

        vms = "";
        for (j = 0; j < JSON.parse(this.responseText).vms.length; j++) {
          vms += `<dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/vm?id=`+ JSON.parse(this.responseText).vms[j].id + `'>` + JSON.parse(this.responseText).vms[j].name + `</a></dd><dt class="col-sm-3"></dt>`;
        }
        }

        if(JSON.parse(this.responseText).vms == false) { vms = `<dd class="col-sm-9">No Virtual Machines. <a href="addvm">Add a new VM here</a>.</dd>`; }
      
      response += `<nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <a style="text-decoration: none;" class="navbar" href="#collapseCard-1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample"><i class="fas fa-network-wired"></i>&nbsp;&nbsp;`;

      response += JSON.parse(this.responseText).name;

      response += `</a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="network?id=` + JSON.parse(this.responseText).id + `">View</a>
                <a class="dropdown-item text-muted" href="#">Edit</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameNetworkModal" onclick="document.getElementById('existingNetworkId').value = '` + JSON.parse(this.responseText).networkid  + `'; document.getElementById('newNetworkName').value = '` + JSON.parse(this.responseText).name + `'">Rename</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyNetworkModal" onclick="document.getElementById('existingNetworkId').value = '` + JSON.parse(this.responseText).networkid + `'">Destroy</a>
                </div>
            </li>
          </ul>
        </nav>
        <div class="collapse" id="collapseCard-1">
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-3">Project</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText).project + `</dd>
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText).networkid + `</dd>
                <dt class="col-sm-3">LAN IP</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText).lanip + `</dd>
                <dt class="col-sm-3">WAN IP</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText).wanip + `</dd>
                <dt class="col-sm-3">VM's</dt>` + vms +
              `</dl>
            </div>
          </div>`;


    }

      document.getElementById("current-networks").innerHTML = response;
    } else {  
      document.getElementById("current-networks").innerHTML = "No Virtual Networks. Start by <a href='addproject'>adding a Project</a>. Then you can <a href='addnetwork'>add and associate a new Virtual Network</a>."; }
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getNetworks");

}

function destroyNetwork() {

  document.getElementById("current-networks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var name = document.getElementById("existingNetworkId").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getNetworks();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("destroyNetwork=" + name);

}

function renameNetwork() {

  document.getElementById("current-networks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var networkid = document.getElementById("existingNetworkId").value;
  var newName = document.getElementById("newNetworkName").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getNetworks();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("renameNetwork=" + networkid + "&newNetworkName=" + newName);

}

getNetworks();