function getProjects() {

  var j
  var vms
  var networks
  var response

  document.getElementById("current-projects").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      if(this.responseText !== "false" && this.responseText !== "[]") {
        
      var arrayLength = JSON.parse(this.responseText).length;
      response = "";

      if (typeof arrayLength !== 'undefined') {

      for (var i = 0; i < arrayLength; i++) {

        if(JSON.parse(this.responseText)[i].vms !== undefined) {
          vms = "";
            for (j = 0; j < JSON.parse(this.responseText)[i].vms.length; j++) {
              vms += `<dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/vm?id=`+ JSON.parse(this.responseText)[i].vms[j].id + `'>` + JSON.parse(this.responseText)[i].vms[j].name + `</a></dd><dt class="divide-fix col-sm-3"></dt>`;
            }
          }
        
        if(JSON.parse(this.responseText)[i].networks == false) { networks = `<dd class="col-sm-9">No Virtual Networks. <a href="addnetwork">Add a new network here</a>.</dd>`; }

             if(JSON.parse(this.responseText)[i].networks !== false) {
        networks = "";
          for (j = 0; j < JSON.parse(this.responseText)[i].networks.length; j++) {
            networks += `<dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/network?id=`+ JSON.parse(this.responseText)[i].networks[j].id + `'>` + JSON.parse(this.responseText)[i].networks[j].name + `</a></dd><dt class="divide-fix col-sm-3"></dt>`;
          }
           networks += `<dd class="divide-fix col-sm-9"></dd>`
        }

          if(JSON.parse(this.responseText)[i].vms == false) { vms = `<dd class="col-sm-9">No Virtual Machines. <a href="addvm">Add a new VM here</a>.</dd>`; }

        response += `<nav class="navbar navbar-expand navbar-light bg-light mb-4">
            <a style="text-decoration: none;" class="navbar" href="#collapseCard-` + i + `" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample"><i class="fas fa-project-diagram"></i>&nbsp;&nbsp;`;

        response += JSON.parse(this.responseText)[i].name;

        response += `</a>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item text-muted" href="#">Edit</a>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameProjectModal" onclick="document.getElementById('existingProjectName').value = '` + JSON.parse(this.responseText)[i].projectid + `'; document.getElementById('newProjectName').value = '` + JSON.parse(this.responseText)[i].name + `'">Rename</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#removeProjectModal" onclick="document.getElementById('existingProjectName').value = '` + JSON.parse(this.responseText)[i].projectid + `'">Remove</a>
                  </div>
              </li>
            </ul>
          </nav>
          <div class="collapse" id="collapseCard-` + i + `">
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">` + JSON.parse(this.responseText)[i].projectid + `</dd><dt class="divide-fix col-sm-3"></dt><dd class="divide-fix col-sm-9"></dd>
                <dt class="col-sm-3">Virtual Network's</dt>` + networks + `
                <dt class="col-sm-3">VM's</dt>` + vms + `
              </dl>
            </div>
          </div>`;
      }

    } else {

      if(JSON.parse(this.responseText).vms !== undefined) {
        vms = "";
          for (j = 0; j < JSON.parse(this.responseText).vms.length; j++) {
            vms += `<dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/vm?id=`+ JSON.parse(this.responseText).vms[j].id + `'>` + JSON.parse(this.responseText).vms[j].name + `</a></dd><dt class="divide-fix col-sm-3"></dt>`;
          }
        }

      if(JSON.parse(this.responseText).networks == false) { networks = `<dd class="col-sm-9">No Virtual Machines. <a href="addvm">Add a new VM here</a>.</dd>`; }

         if(JSON.parse(this.responseText).networks !== false) {
      networks = "";
        for (j = 0; j < JSON.parse(this.responseText).networks.length; j++) {
          networks += `<dd class="col-sm-9"><a href='https://cloud.jspc.co.uk/network?id=`+ JSON.parse(this.responseText).networks[j].id + `'>` + JSON.parse(this.responseText).networks[j].name + `</a></dd><dt class="divide-fix col-sm-3"></dt>`;
        }
         networks += `<dd class="divide-fix col-sm-9"></dd>`
      }

        if(JSON.parse(this.responseText).networks == false) { networks = `<dd class="col-sm-9">No Virtual Networks. <a href="addnetwork">Add a new network here</a>.</dd>`; }

      response += `<nav class="navbar navbar-expand navbar-light bg-light mb-4">
      <a style="text-decoration: none;" class="navbar" href="#collapseCard-1" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample"><i class="fas fa-project-diagram"></i>&nbsp;&nbsp;`;

  response += JSON.parse(this.responseText).name;

  response += `</a>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="navbarDropdown">
            <a class="dropdown-item text-muted" href="#">Edit</a>
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#renameProjectModal" onclick="document.getElementById('existingProjectName').value = '` + JSON.parse(this.responseText).projectid + `'; document.getElementById('newProjectName').value = '` + JSON.parse(this.responseText).name + `'">Rename</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#removeProjectModal" onclick="document.getElementById('existingProjectName').value = '` + JSON.parse(this.responseText).projectid + `'">Remove</a>
            </div>
        </li>
      </ul>
    </nav>
    <div class="collapse" id="collapseCard-1">
      <div class="card-body">
        <dl class="row">
          <dd class="col-sm-9">` + JSON.parse(this.responseText).projectid + `</dd><dt class="divide-fix col-sm-3"></dt><dd class="divide-fix col-sm-9"></dd>
          <dt class="col-sm-3">Virtual Network's</dt>` + networks + `
          <dt class="col-sm-3">VM's</dt>` + vms + `
        </dl>
      </div>
    </div>`;


    }
  } else { response = "No Projects. <a href='addproject'>Add one now</a>."; }
      document.getElementById("current-projects").innerHTML = response;
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getProjects");

}

getProjects();


function renameProject() {

  document.getElementById("current-projects").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var name = document.getElementById("existingProjectName").value;
  var newName = document.getElementById("newProjectName").value;
 
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getProjects();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("renameProject=" + name + "&newProjectName=" + newName);

}

function removeProject() {

  document.getElementById("current-projects").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`;

  var name = document.getElementById("existingProjectName").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {
      getProjects();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("removeProject=" + name);

}