  function addNewVM() {
    
    var progress = document.getElementById("addVMProgress");
    var percentComplete = 0;

    function progressComplete() {
      if (percentComplete < 95) {
        percentComplete = percentComplete + 0.1
      }
      progress.style = "Width: " + percentComplete + "%";
    }

    $("#addNewVMForm :input").prop('disabled', true);

    var progressBar = setInterval(progressComplete, 100)

    var name = document.getElementById("inputVMName").value;
    var Project = document.getElementById("inputVMProject").value;
    var memory = document.getElementById("inputVMMemory").value;
    var cpu = document.getElementById("inputVMCPU").value;

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
        clearInterval(progressBar);
        progress.style = "Width: 100%";
        document.getElementById("add-vm").innerHTML = `<div class="card-body">
        <dl class="row">
          <dt class="col-sm-3">Name</dt>
          <dd class="col-sm-9">` + name + `</dd>
          <dt class="col-sm-3">Project</dt>
          <dd class="col-sm-9">` + Project + `</dd>
          <dt class="col-sm-3">IP Address</dt>
          <dd class="col-sm-9">` + JSON.parse(this.responseText).IPAddresses; + `</dd>
        </dl>
      </div>`;
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("addNewVM=" + name + "&addNewVMToGroup=" + Project + "&addNewVMMemory=" + memory + "&addNewVMCPU=" + cpu);

  }