  function addNewDisk() {

    var name = document.getElementById("inputDiskName"),
    size = document.getElementById("inputDiskSize"),
    terms = document.getElementById("terms"),
    invalidFeedback = document.getElementById("invalid-feedback"),
    addNewDiskBtn = document.getElementById("addNewDiskBtn");

    if(terms.checked === false || size.value == "" || name.value == "") { 
      
    if(terms.checked === false) { invalidFeedback.innerText = "Please agree to the terms and conditions"; }
    if(size.value == "" || size.value == "0") { invalidFeedback.innerText = "Please enter a disk size"; }
    if(name.value == "") { invalidFeedback.innerText = "Please enter a disk name"; }

    } else { invalidFeedback.innerText = "" }

    if(invalidFeedback.innerText == "") {

    addNewDiskBtn.innerText = "New Disk Adding";
    $("#addNewDiskForm :input").prop('disabled', true);

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
        window.location.href = 'disks';
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("addNewDisk=" + name.value + "&diskSize=" + size.value);

    }
  }