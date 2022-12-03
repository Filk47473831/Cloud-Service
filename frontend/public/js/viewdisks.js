function getVirtualDisks() {

  document.getElementById("current-disks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {

      var response = "";
      if(this.responseText !== "false" && this.responseText !== "[]") {
      var arrayLength = JSON.parse(this.responseText).length;

      if(typeof arrayLength !== 'undefined') {

      for (var i = 0; i < arrayLength; i++) {

        response += `<nav class="navbar navbar-expand navbar-light bg-light mb-4">
            <a style="text-decoration: none;" class="navbar" href="#collapseCard-` + i + `" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample"><i class="fas fa-fw fa-hdd"></i>&nbsp;&nbsp;`;

        response += JSON.parse(this.responseText)[i].name;

        response += `</a>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">`;
      
      if(JSON.parse(this.responseText)[i].attached == 1) {
       response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#detachDiskModal" onclick="document.getElementById('existingDiskID').value = '` + encodeURI(JSON.parse(this.responseText)[i].diskId) + `'">Detach</a>`;
      } else {
       response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#attachDiskModal" onclick="document.getElementById('existingDiskID').value = '` + encodeURI(JSON.parse(this.responseText)[i].diskId) + `'">Attach</a>`; 
      }
       response += `<a class="dropdown-item text-muted" href="#">Edit</a>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameDiskModal" onclick="document.getElementById('newDiskName').value = '` + JSON.parse(this.responseText)[i].name + `';document.getElementById('existingDiskID').value = '` + JSON.parse(this.responseText)[i].diskId + `'">Rename</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyDiskModal" onclick="document.getElementById('existingDiskID').value = '` + encodeURI(JSON.parse(this.responseText)[i].diskId) + `'">Destroy</a>
                  </div>
              </li>
            </ul>
          </nav>
          <div class="collapse" id="collapseCard-` + i + `">
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-3">Disk ID</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].diskId + `</dd>
                <dt class="col-sm-3">Disk Identifier</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].diskIdentifier + `</dd>
                <dt class="col-sm-3">Disk Size</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].size + `MB</dd>`;
        if(JSON.parse(this.responseText)[i].attached == 1) {
           response += `<dt class="col-sm-3">Attached</dt>
                <dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/vm?id=`+ JSON.parse(this.responseText)[i].vm + `'>` + JSON.parse(this.responseText)[i].vmname + `</a></dd>`;
        }
           response += `</dl>
            </div>
          </div>`;
      }

    } else {

      response += `<nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <a style="text-decoration: none;" class="navbar" href="#collapseCard-1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample"><i class="fas fa-fw fa-hdd"></i>&nbsp;&nbsp;`;

      response += JSON.parse(this.responseText).name;

      response += `</a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">`;
      
      if(JSON.parse(this.responseText).attached == 1) {
       response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#detachDiskModal" onclick="document.getElementById('existingDiskID').value = '` + encodeURI(JSON.parse(this.responseText).diskId) + `'">Detach</a>`;
      } else {
       response += `<a class="dropdown-item" href="#" data-toggle="modal" data-target="#attachDiskModal" onclick="document.getElementById('existingDiskID').value = '` + encodeURI(JSON.parse(this.responseText).diskId) + `'">Attach</a>`; 
      }
       response += `<a class="dropdown-item text-muted" href="#">Edit</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameDiskModal" onclick="document.getElementById('newDiskName').value = '` + JSON.parse(this.responseText).name + `';document.getElementById('existingDiskID').value = '` + JSON.parse(this.responseText).diskId + `'">Rename</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#destroyDiskModal" onclick="document.getElementById('existingDiskID').value = '` + encodeURI(JSON.parse(this.responseText).diskId) + `'">Destroy</a>
                </div>
            </li>
          </ul>
        </nav>
        <div class="collapse" id="collapseCard-1">
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-3">Disk ID</dt>
              <dd class="col-sm-9">` + JSON.parse(this.responseText).diskId + `</dd>
              <dt class="col-sm-3">Disk Identifier</dt>
              <dd class="col-sm-9">` + JSON.parse(this.responseText).diskIdentifier + `</dd>
              <dt class="col-sm-3">Disk Size</dt>
              <dd class="col-sm-9">` + JSON.parse(this.responseText).size + `MB</dd>
              <dt class="col-sm-3">Attached</dt>
              <dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/vm?id=`+ JSON.parse(this.responseText).vm + `'>` + JSON.parse(this.responseText).vmname + `</a></dd>
            </dl>
          </div>
        </div>`;


    }
      } else { response = "No Virtual Disks"; }

      document.getElementById("current-disks").innerHTML = response;
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getVirtualDisks");

}

function attachDisk() {

  document.getElementById("current-disks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var diskId = document.getElementById("existingDiskID").value;
  var VMName = document.getElementById("VMName").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getVirtualDisks();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("attachDisk=" + diskId + "&VMName=" + VMName);

}


function detachDisk() {

  document.getElementById("current-disks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var diskId = document.getElementById("existingDiskID").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getVirtualDisks();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("detachDisk=" + diskId);

}

function renameDisk() {

  document.getElementById("current-disks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var diskId = document.getElementById("existingDiskID").value;
  var newName = document.getElementById("newDiskName").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getVirtualDisks();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("renameDisk=" + newName + "&diskId=" + diskId);

}

function destroyDisk() {

  document.getElementById("current-disks").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var diskId = document.getElementById("existingDiskID").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getVirtualDisks();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("destroyDisk=" + diskId);

}

getVirtualDisks();