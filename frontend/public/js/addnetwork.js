  function addNewNetwork() {

    var name = document.getElementById("inputNetworkName").value,
        project = document.getElementById("inputNetworkProject").value,
        networkIP = document.getElementById("inputNetworkIP").value,
        subnetMask = document.getElementById("inputSubnetMask").value,
        remotePublicIP = document.getElementById("inputWanIP1").value + '.' + document.getElementById("inputWanIP2").value + '.' + document.getElementById("inputWanIP3").value + '.' + document.getElementById("inputWanIP4").value,
        remoteIP = document.getElementById("inputWanNetworkIP1").value + '.' + document.getElementById("inputWanNetworkIP2").value + '.' + document.getElementById("inputWanNetworkIP3").value + '.0',
        remoteSubnetMask = document.getElementById("inputWanNetworkSubnetMask").value,
        invalidFeedback = document.getElementById("invalid-feedback"),
        terms = document.getElementById("terms");
    
    if(terms.checked === false || remoteSubnetMask == "" || document.getElementById("inputWanNetworkIP1").value == "" || document.getElementById("inputWanNetworkIP2").value == "" || document.getElementById("inputWanNetworkIP3").value == "" || document.getElementById("inputWanIP1").value == "" || document.getElementById("inputWanIP2").value == "" || document.getElementById("inputWanIP3").value == "" || document.getElementById("inputWanIP4").value == "" || subnetMask == "" || networkIP == "" || project === "null" || name == "")  { 
      
      if(terms.checked === false) { invalidFeedback.innerText = "Please agree to the terms and conditions"; }
      if(remoteSubnetMask == "") { invalidFeedback.innerText = "Please specify a subnet mask for the remote network"; }
      if(document.getElementById("inputWanNetworkIP1").value == "" || document.getElementById("inputWanNetworkIP2").value == "" || document.getElementById("inputWanNetworkIP3").value == "") { invalidFeedback.innerText = "Please specify a remote network internal address"; }
      if(document.getElementById("inputWanIP1").value == "" || document.getElementById("inputWanIP2").value == "" || document.getElementById("inputWanIP3").value == "" || document.getElementById("inputWanIP4").value == "") { invalidFeedback.innerText = "Please specify a remote network public IP"; }
      if(subnetMask == "") { invalidFeedback.innerText = "Please specify a subnet mask"; }
      if(networkIP == "") { invalidFeedback.innerText = "Please specify a Local Area Network address"; }
      if(isNaN(project)) { invalidFeedback.innerText = "Please select a project for this network"; }
      if(name == "") { invalidFeedback.innerText = "Please enter a network name"; }
  
      } else { invalidFeedback.innerText = "" }
  
      if(invalidFeedback.innerText == "") {

    $("#addNewNetworkForm :input").prop('disabled', true);

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
        if(this.responseText == "") { invalidFeedback.innerText = "Insufficient Resources Available"; $("#addNewNetworkForm :input").prop('disabled', false); } else { window.location.href = "network?id=" + this.responseText; }
        }
      }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("addNewNetwork=" + name + "&project=" + project + "&networkIP=" + networkIP + "&subnetMask=" + subnetMask + "&remotePublicIP=" + remotePublicIP + "&remoteNetworkIP=" + remoteIP + "&remoteSubnetMask=" + remoteSubnetMask);

    }
  }

  function passwordGenerate(){
    var passwordChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    var randPassword = Array(56).fill(passwordChars).map(function(x) { return x[Math.floor(Math.random() * x.length)] }).join('');
    return randPassword;
    }
    