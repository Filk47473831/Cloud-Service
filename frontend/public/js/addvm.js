  function addNewVM() {

    var name = document.getElementById("inputVMName").value,
        project = document.getElementById("inputVMProject").value,
        network = document.getElementById("inputVMNetwork").value,
        image = document.getElementById("inputVMImage").value,
        memory = document.getElementById("inputVMMemory").value,
        cpu = document.getElementById("inputVMCPU").value,
        password = passwordGenerate(),
        invalidFeedback = document.getElementById("invalid-feedback"),
        terms = document.getElementById("terms");

    if(terms.checked === false || name == "" || project === "null" || network === "null" || image === "null" || memory === "null" || cpu === "null") { 
  
      if(terms.checked === false) { invalidFeedback.innerText = "Please agree to the terms and conditions"; }
      if(isNaN(cpu)) { invalidFeedback.innerText = "Please choose a CPU level for this VM"; }
      if(isNaN(memory)) { invalidFeedback.innerText = "Please choose a memory level for this VM"; }
      if(isNaN(image)) { invalidFeedback.innerText = "Please choose an image for this VM"; }
      if(isNaN(network)) { invalidFeedback.innerText = "Please choose a network for this VM"; }
      if(isNaN(project)) { invalidFeedback.innerText = "Please choose a project for this VM"; }
      if(currentVMNames.includes(name)) { invalidFeedback.innerText = "Please enter a different VM name"; }
      if(name === "") { invalidFeedback.innerText = "Please enter a VM name"; }
      
      } else { invalidFeedback.innerText = "" }
  
      if(invalidFeedback.innerText == "") { 

        $("#addNewVMForm :input").prop('disabled', true);

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
        if(this.responseText == "") { invalidFeedback.innerText = "Insufficient Resources Available"; $("#addNewVMForm :input").prop('disabled', false); } else { window.location.href = "vm?id=" + this.responseText; }
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("addNewVM=" + name + "&VMProject=" + project + "&VMNetwork=" + network + "&VMImage=" + image + "&VMMemory=" + memory + "&VMCPU=" + cpu + "&VMPassword=" + password);

    }

  }

function passwordGenerate(){
    var passwordChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    var randPassword = Array(24).fill(passwordChars).map(function(x) { return x[Math.floor(Math.random() * x.length)] }).join('');
    return randPassword;
}