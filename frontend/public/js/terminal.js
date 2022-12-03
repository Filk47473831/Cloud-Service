document.getElementById("inputTerminalInput").focus();

function sendTerminalCommand() {

    $("#terminalForm :input").prop('disabled', true);

    var command = document.getElementById("inputTerminalInput").value;

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
        // var response = JSON.parse(this.responseText);
        document.getElementById("inputTerminalOutput").value = this.responseText;
        document.getElementById("inputTerminalInput").value = "";
        $("#terminalForm #inputTerminalInput").prop('disabled', false);
        document.getElementById("inputTerminalInput").focus();
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("sendTerminalCommand=" + command);

  }

document.getElementById("inputTerminalInput").addEventListener("keyup", function(a) {
    var b = a.which || a.keyCode;
    if (13 == b) return sendTerminalCommand(), !1
})