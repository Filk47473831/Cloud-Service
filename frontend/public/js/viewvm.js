var currentPercentComplete = document.getElementById("percentComplete").value;
var percentComplete = document.getElementById("percentComplete").value;

if (percentComplete < 100) {
  var getVMStatusDB = setInterval(getVMStatus, 10000);
}

function progressComplete() {
  if (percentComplete < (parseFloat(currentPercentComplete) + parseFloat(20))) {
    percentComplete = parseFloat(percentComplete) + parseFloat(0.048);

    if (percentComplete > 3 && percentComplete < 60) {
      document.getElementById("deployStatus").innerHTML = `Copying Image...`;
    }

    if (percentComplete > 60 && percentComplete < 65) {
      document.getElementById("deployStatus").innerHTML = `Connecting Network...`;
    }

    if (percentComplete > 65 && percentComplete < 80) {
      document.getElementById("deployStatus").innerHTML = `Preparing OS...`;
    }

    if (percentComplete > 80 && percentComplete < 85) {
      document.getElementById("deployStatus").innerHTML = `Making Tea...`;
    }

    if (percentComplete > 85 && percentComplete < 100) {
      document.getElementById("deployStatus").innerHTML = `Finishing Up...`;
    }

  }
  document.getElementById("deployProgress").style = "Width: " + percentComplete + "%";
}

var progressBar = setInterval(progressComplete, 100);

function secondsToDaysHms(d) {
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

function getVMStatus() {

  document.getElementById("updatebtn").setAttribute("class", "fas fa-sync active-btn");

  var id = document.getElementById("VmID").value;
  var machineid = document.getElementById("machineId").innerText;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function() {
    if (this.status == 200) {
      document.getElementById("updatebtn").setAttribute("class", "fas fa-sync");

      var response = JSON.parse(this.responseText);
      
      if (percentComplete < response.buildstatus) {
        percentComplete = parseFloat(percentComplete) + parseFloat(10);
      }
      currentPercentComplete = response.buildstatus;

      if (response.ipaddr == "") {
        response.ipaddr = "No Address";
      }
      if (response.ipaddr.startsWith("192.")) {
        response.ipaddr = "No Address";
      }
      if (response.state == "") {
        response.state = "Off";
      }

      document.getElementById("password").innerText = response.password;
      document.getElementById("currentState").innerText = response.state;
      document.getElementById("ipaddr").innerText = response.ipaddr;
      document.getElementById("uptime").innerText = secondsToDaysHms(response.uptime);
      // document.getElementById("deploymentTerminalOutput").innerText = response.deploymentstatus;

      if (response.errorBuild == 1) {
        buildFail();
        document.getElementById("currentState").innerText = response.state;
      }

      if (response.buildstatus == 100 && response.errorBuild !== 1) {
        // if (document.getElementById("imageName").innerText !== "Ubuntu 20.04.1 LTS") {
        //  updateThumbnail(response.machineid);
        // }
        clearInterval(getVMStatusDB);
        clearInterval(progressBar);
        document.getElementById("deployProgress").style = "Width: 100%";
        document.getElementById("deployProgress").setAttribute("class", "progress-bar bg-success");
//         if (document.getElementById("imageName").innerText !== "Ubuntu 20.04.1 LTS") {
//           document.getElementById("thumbnailTabBtn").setAttribute("onclick", "thumbnailTab()");
//           document.getElementById("thumbnailTabBtn").classList.remove("disabled");
//         }
        document.getElementById("checkpointsTabBtn").setAttribute("onclick", "checkpointsTab()");
        document.getElementById("monitoringTabBtn").classList.remove("disabled");
        document.getElementById("checkpointsTabBtn").classList.remove("disabled");
        document.getElementById("storageTabBtn").classList.remove("disabled");
        document.getElementById("networkTabBtn").classList.remove("disabled");
        document.getElementById("currentState").innerText = response.state;

        document.getElementById("deployStatus").innerHTML = `Operating Normally`;

        if(response.uuid != null) {
          readMonitorForVM(response.uuid); 
        } else { 
          document.getElementById("monitoringAgentText").innerHTML = `No Agent Installed` 
        }

        if (document.getElementById("imageName").innerText !== "Ubuntu 20.04.1 LTS") {
          download(response.name + ".RDP", `screen mode id:i:1
        use multimon:i:1
        desktopwidth:i:2048
        desktopheight:i:1536
        session bpp:i:16
        winposstr:s:0,1,779,223,2845,1806
        compression:i:1
        keyboardhook:i:2
        audiocapturemode:i:0
        videoplaybackmode:i:1
        connection type:i:6
        networkautodetect:i:1
        bandwidthautodetect:i:1
        displayconnectionbar:i:1
        enableworkspacereconnect:i:0
        disable wallpaper:i:0
        allow font smoothing:i:0
        allow desktop composition:i:0
        disable full window drag:i:1
        disable menu anims:i:1
        disable themes:i:0
        disable cursor setting:i:0
        bitmapcachepersistenable:i:1
        audiomode:i:0
        redirectprinters:i:1
        redirectcomports:i:1
        redirectsmartcards:i:0
        redirectclipboard:i:1
        redirectposdevices:i:0
        autoreconnection enabled:i:1
        authentication level:i:0
        prompt for credentials:i:0
        negotiate security layer:i:1
        remoteapplicationmode:i:0
        alternate shell:s:
        shell working directory:s:
        gatewayhostname:s:
        gatewayusagemethod:i:2
        gatewaycredentialssource:i:4
        gatewayprofileusagemethod:i:0
        promptcredentialonce:i:1
        gatewaybrokeringtype:i:0
        use redirection server name:i:0
        rdgiskdcproxy:i:0
        kdcproxyname:s:
        drivestoredirect:s:*
        username:s:Administrator
        full address:s:` + response.ipaddr);
        }
      }

    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getVMStatus=" + id + "&getVMStatusDB=" + machineid);

}

getVMStatus();

function download(filename, text) {
  if (document.getElementById("connectBtn")) {
    document.getElementById("connectBtn").remove()
  }
  var element = document.createElement('a');
  element.setAttribute('id', 'connectBtn');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.setAttribute('class', 'btn btn-success');
  element.setAttribute('role', 'button');
  text = document.createTextNode("Connect File");
  element.appendChild(text);
  document.getElementById("infoTab").appendChild(element);
}

// document.getElementById("deploymentTerminalOutput").addEventListener("change", function(){
//   scrollOutputWindowToBottom();
// });

function scrollOutputWindowToBottom() {
  var element = document.getElementById("deploymentTerminalOutput");
  element.scrollTop = element.scrollHeight;
}

function buildFail() {
  clearInterval(getVMStatusDB);
  clearInterval(progressBar);

  document.getElementById("deploymentInfoText").setAttribute("class", "text-danger");
  document.getElementById("deploymentStatusText").setAttribute("class", "text-danger");
  document.getElementById("cardVMMain").setAttribute("class", "card position-relative border-bottom-danger");

  document.getElementById("deployProgress").style = "Width: 100%";
  document.getElementById("deployProgress").setAttribute("class", "progress-bar bg-danger");
  document.getElementById("deployStatus").innerHTML = `Error During Build`;
}


function secondsToDaysHms(d) {
  if (d < 1) {
    return "0 Seconds";
  }
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

function storageTab() {
  // document.getElementById("statusTab").style = "display:none";
  document.getElementById("monitoringTab").style = "display:none";
  document.getElementById("infoTab").style = "display:none";
  document.getElementById("storageTab").style = "";
  document.getElementById("networkTab").style = "display:none";
//   document.getElementById("thumbnailTab").style = "display:none";
  document.getElementById("checkpointsTab").style = "display:none";
  document.getElementById("infoTabBtn").setAttribute("class", "nav-link");
  document.getElementById("monitoringTabBtn").setAttribute("class", "nav-link");
  document.getElementById("storageTabBtn").setAttribute("class", "nav-link active");
  document.getElementById("checkpointsTabBtn").setAttribute("class", "nav-link");
  document.getElementById("networkTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("thumbnailTabBtn").setAttribute("class", "nav-link");
}

function statusTab() {
  // document.getElementById("statusTab").style = "";
  document.getElementById("monitoringTab").style = "display:none";
  document.getElementById("infoTab").style = "display:none";
  document.getElementById("networkTab").style = "display:none";
  // document.getElementById("thumbnailTab").style = "display:none";
  document.getElementById("storageTab").style = "display:none";
  document.getElementById("checkpointsTab").style = "display:none";
  document.getElementById("statusTabBtn").setAttribute("class", "nav-link active");
  document.getElementById("infoTabBtn").setAttribute("class", "nav-link");
  document.getElementById("monitoringTabBtn").setAttribute("class", "nav-link");
  document.getElementById("storageTabBtn").setAttribute("class", "nav-link");
  document.getElementById("checkpointsTabBtn").setAttribute("class", "nav-link");
  document.getElementById("networkTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("thumbnailTabBtn").setAttribute("class", "nav-link");
}

function infoTab() {
  // document.getElementById("statusTab").style = "display:none";
  document.getElementById("monitoringTab").style = "display:none";
  document.getElementById("infoTab").style = "";
  document.getElementById("networkTab").style = "display:none";
  // document.getElementById("thumbnailTab").style = "display:none";
  document.getElementById("checkpointsTab").style = "display:none";
  document.getElementById("storageTab").style = "display:none";
  document.getElementById("infoTabBtn").setAttribute("class", "nav-link active");
  document.getElementById("monitoringTabBtn").setAttribute("class", "nav-link");
  document.getElementById("storageTabBtn").setAttribute("class", "nav-link");
  document.getElementById("checkpointsTabBtn").setAttribute("class", "nav-link");
  document.getElementById("networkTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("thumbnailTabBtn").setAttribute("class", "nav-link");
}

function checkpointsTab() {
//   // document.getElementById("statusTab").style = "display:none";
//   document.getElementById("monitoringTab").style = "display:none";
//   document.getElementById("infoTab").style = "display:none";
//   document.getElementById("checkpointsTab").style = "";
//   document.getElementById("networkTab").style = "display:none";
//   // document.getElementById("thumbnailTab").style = "display:none";
//   document.getElementById("storageTab").style = "display:none";
//   document.getElementById("infoTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("monitoringTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("checkpointsTabBtn").setAttribute("class", "nav-link active");
//   document.getElementById("storageTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("networkTabBtn").setAttribute("class", "nav-link");
// //   document.getElementById("thumbnailTabBtn").setAttribute("class", "nav-link");
}

function networkTab() {
  // document.getElementById("statusTab").style = "display:none";
  document.getElementById("monitoringTab").style = "display:none";
  document.getElementById("infoTab").style = "display:none";
  document.getElementById("checkpointsTab").style = "display:none";
  document.getElementById("networkTab").style = "";
  // document.getElementById("thumbnailTab").style = "display:none";
  document.getElementById("storageTab").style = "display:none";
  document.getElementById("infoTabBtn").setAttribute("class", "nav-link");
  document.getElementById("monitoringTabBtn").setAttribute("class", "nav-link");
  document.getElementById("checkpointsTabBtn").setAttribute("class", "nav-link");
  document.getElementById("networkTabBtn").setAttribute("class", "nav-link active");
  document.getElementById("storageTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("thumbnailTabBtn").setAttribute("class", "nav-link");
}

function monitoringTab() {
  // document.getElementById("statusTab").style = "display:none";
  document.getElementById("monitoringTab").style = "";
  document.getElementById("infoTab").style = "display:none";
  document.getElementById("checkpointsTab").style = "display:none";
  document.getElementById("networkTab").style = "display:none";
  // document.getElementById("thumbnailTab").style = "";
  document.getElementById("storageTab").style = "display:none";
//   document.getElementById("statusTabBtn").setAttribute("class", "nav-link");
  document.getElementById("infoTabBtn").setAttribute("class", "nav-link");
  document.getElementById("monitoringTabBtn").setAttribute("class", "nav-link active");
  document.getElementById("storageTabBtn").setAttribute("class", "nav-link");
  document.getElementById("checkpointsTabBtn").setAttribute("class", "nav-link");
  document.getElementById("networkTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("thumbnailTabBtn").setAttribute("class", "nav-link active");
}

function thumbnailTab() {
  // document.getElementById("statusTab").style = "display:none";
  document.getElementById("monitoringTab").style = "display:none";
  document.getElementById("infoTab").style = "display:none";
  document.getElementById("checkpointsTab").style = "display:none";
  document.getElementById("networkTab").style = "display:none";
  // document.getElementById("thumbnailTab").style = "";
  document.getElementById("storageTab").style = "display:none";
//   document.getElementById("statusTabBtn").setAttribute("class", "nav-link");
  document.getElementById("infoTabBtn").setAttribute("class", "nav-link");
  document.getElementById("monitoringTabBtn").setAttribute("class", "nav-link");
  document.getElementById("storageTabBtn").setAttribute("class", "nav-link");
  document.getElementById("checkpointsTabBtn").setAttribute("class", "nav-link");
  document.getElementById("networkTabBtn").setAttribute("class", "nav-link");
//   document.getElementById("thumbnailTabBtn").setAttribute("class", "nav-link active");
}

function updateThumbnail(machineId) {

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function() {
    if (this.status == 200) {
      reloadThumbnail(machineId);
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("updateVMImg=" + machineId);

}

function reloadThumbnail(machineId) {

  var img = document.getElementById("vmThumbnailImg");
  if (checkURL("img/vm/" + machineId + ".bmp")) {
    img.src = "img/vm/" + machineId + ".bmp?" + Math.random();
  }
}

function checkURL(url) {

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function() {
    if (this.status === 404) {
      return true;
    }
  }
  xmlhttp.open("GET", url, true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send();

}

function attachDisk() {

  var diskId = document.getElementById("storageDiskSelected").value;
  var VMName = document.getElementById("VmID").value;

  if (diskId != "null") {

    document.getElementById("storageDiskSelected").setAttribute("disabled", "disabled")
    document.getElementById("attachStorageModalCancelBtn").setAttribute("disabled", "disabled")
    document.getElementById("attachStorageModalBtn").setAttribute("disabled", "disabled")
    document.getElementById("attachStorageModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function() {
      if (this.status == 200) {
        location.reload();
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("attachDisk=" + diskId + "&VMName=" + VMName);

  }

}

function attachNetwork() {

  var networkId = document.getElementById("networkSelected").value;
  var VMName = document.getElementById("VmID").value;
  
  if (networkId != "null") {

    document.getElementById("networkSelected").setAttribute("disabled", "disabled")
    document.getElementById("attachNetworkModalCancelBtn").setAttribute("disabled", "disabled")
    document.getElementById("attachNetworkModalBtn").setAttribute("disabled", "disabled")
    document.getElementById("attachNetworkModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function() {
      if (this.status == 200) {
        location.reload();
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("attachNetwork=" + networkId + "&VMName=" + VMName);

  }

}

function addCheckpoint() {

  var checkpointName = document.getElementById("inputCheckpointName").value;
  var VMName = document.getElementById("machineId").innerText;

  if (checkpointName != "") {

    document.getElementById("inputCheckpointName").setAttribute("disabled", "disabled")
    document.getElementById("addCheckpointModalCancelBtn").setAttribute("disabled", "disabled")
    document.getElementById("addCheckpointModalBtn").setAttribute("disabled", "disabled")
    document.getElementById("addCheckpointModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function() {
      if (this.status == 200) {
        location.reload();
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("addCheckpoint=" + checkpointName + "&VMName=" + VMName);

  }

}

function removeCheckpoint(id, checkpointName) {

  var VMName = document.getElementById("machineId").innerText;

  document.getElementById("removeCheckpointModalCancelBtn").setAttribute("disabled", "disabled")
  document.getElementById("removeCheckpointModalBtn").setAttribute("disabled", "disabled")
  document.getElementById("removeCheckpointModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function() {
    if (this.status == 200) {
      location.reload();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("removeCheckpoint=" + id + "&checkpointName=" + checkpointName + "&VMName=" + VMName);

}

function restoreCheckpoint(id, checkpointName) {

  var VMName = document.getElementById("machineId").innerText;

  document.getElementById("restoreCheckpointModalCancelBtn").setAttribute("disabled", "disabled")
  document.getElementById("restoreCheckpointModalBtn").setAttribute("disabled", "disabled")
  document.getElementById("restoreCheckpointModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`
  
  document.getElementById("restoreFromDailyModalCancelBtn").setAttribute("disabled", "disabled")
  document.getElementById("restoreFromDailyModalBtn").setAttribute("disabled", "disabled")
  document.getElementById("restoreFromDailyModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function() {
    if (this.status == 200) {
      location.reload();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("restoreCheckpoint=" + id + "&checkpointName=" + checkpointName + "&VMName=" + VMName);

}

function restoreCheckpointModal(id, checkpointName) {
  document.getElementById("restoreCheckpointModalBtn").setAttribute("onclick", "restoreCheckpoint(" + id + ",\"" + checkpointName + "\")")
  $('#restoreCheckpointModal').modal({
    backdrop: 'static',
    keyboard: false
  })
}

function removeCheckpointModal(id, checkpointName) {
  document.getElementById("removeCheckpointModalBtn").setAttribute("onclick", "removeCheckpoint(" + id + ",\"" + checkpointName + "\")")
  $('#removeCheckpointModal').modal({
    backdrop: 'static',
    keyboard: false
  })
}


function readMonitorForVM(uuid) {

  var VMName = document.getElementById("machineId").innerText;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function() {
    if (this.status == 200) {

      if(this.responseText == "No Agent Installed") {
        document.getElementById("monitoringAgentText").innerHTML = "No Agent Installed"
      } else {
        var response = JSON.parse(this.responseText)
        document.getElementById("monitoringAgentText").innerHTML = `Agent Installed - ` + uuid;
        document.getElementById("currentLoad").innerHTML = (Math.round(response.currentload * 100) / 100).toFixed(2) + "%"
      }
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("readMonitorForVM=" + VMName);

}
