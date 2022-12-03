function getHostFreeDiskSpace() {

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
  
        var utilisationSSD = JSON.parse(this.responseText)[1].value;
        
        utilisationSSD = utilisationSSD / 1024 / 1024;

        utilisationSSD = ((utilisationSSD / 1005226) * 100);
        utilisationSSD = parseInt(utilisationSSD);
        utilisationSSD = 100 - utilisationSSD;
  
        document.getElementById("ssdFreeSpaceBar").style = "Width: " + utilisationSSD + "%";
        document.getElementById("ssdFreeSpace").innerText = utilisationSSD + "%";

      }
    }
    xmlhttp.open("POST", "../../control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("getHostFreeDiskSpace");
  
  }

  function getHostFreeMemory() {
  
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
  
        var utilisation = JSON.parse(this.responseText).value;
        utilisation = 100 - ((utilisation / 65536) * 100);
        utilisation = parseInt(utilisation);
  
        document.getElementById("hostFreeMemoryBar").style = "Width: " + utilisation + "%";
        document.getElementById("hostFreeMemory").innerText = utilisation + "%";
      }
    }
    xmlhttp.open("POST", "../../control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("getHostFreeMemory");
  
  }

  function getNumberOfVms() {
  
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
  
        var numberOfVms = this.responseText;
        document.getElementById("total-vms").innerText = numberOfVms;
      }
    }
    xmlhttp.open("POST", "../../control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("getNumberOfVms");
  
  }

  getHostFreeDiskSpace();
  getHostFreeMemory();
  getNumberOfVms();