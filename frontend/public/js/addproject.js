  function addNewProject() {

    var name = document.getElementById("inputProjectName"),
    notes = document.getElementById("inputProjectNotes"),
    terms = document.getElementById("terms"),
    invalidFeedback = document.getElementById("invalid-feedback"),
    addNewProjectBtn = document.getElementById("addNewProjectBtn");

    if(terms.checked === false || name.value == "") { 
      
    if(terms.checked === false) { invalidFeedback.innerText = "Please agree to the terms and conditions"; }
    if(name.value == "") { invalidFeedback.innerText = "Please enter a project name"; }

    } else { invalidFeedback.innerText = "" }

    if(invalidFeedback.innerText == "") { 

    addNewProjectBtn.innerText = "New Project Adding";
    $("#addNewProjectForm :input").prop('disabled', true);

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onload = function () {
      if (this.status == 200) {
        window.location.href = 'projects';
      }
    }
    xmlhttp.open("POST", "control/control.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("addNewProject=" + name.value);

  }
  
  }