function updatePassword() {

    var currentPassword = document.getElementById("currentPassword"),
    newPassword = document.getElementById("newPassword"),
    confirmPassword = document.getElementById("confirmPassword"),
    invalidFeedback = document.getElementById("invalid-feedback");
    invalidFeedback.className = "ml-3 text-small text-danger";

    if(currentPassword.value == "" || newPassword.value == "" || confirmPassword.value == "" || newPassword.value.length < 10 || confirmPassword.value !== newPassword.value) { 
      
    if(currentPassword.value == "") { invalidFeedback.innerText = "Enter your current passphrase"; }
    if(newPassword.value == "" || newPassword.value.length < 10) { invalidFeedback.innerText = "Enter a passphrase of 10 characters or more"; }
    if(confirmPassword.value !== newPassword.value) { invalidFeedback.innerText = "New passphrases do not match"; }

    } else { invalidFeedback.innerText = "" }

    if(invalidFeedback.innerText == "") {

     $("#profileForm :input").prop('disabled', true);

     if (window.XMLHttpRequest) {
       xmlhttp = new XMLHttpRequest();
     } else {
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
     }
     xmlhttp.onload = function () {
       if (this.status == 200) {
        if(this.responseText == "Passphrase Updated Successfully") { 
            invalidFeedback.className = "ml-3 text-small text-success";
            invalidFeedback.innerText = this.responseText;
            currentPassword.value = "";
            newPassword.value = "";
            confirmPassword.value = "";
            $("#profileForm :input").prop('disabled', false);
        } else {
            invalidFeedback.className = "ml-3 text-small text-danger";
            invalidFeedback.innerText = this.responseText;
            $("#profileForm :input").prop('disabled', false);
        }
       }
     }
     xmlhttp.open("POST", "control/control.php", true);
     xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     xmlhttp.send(`updatePassword&currentPassword=${encodeURIComponent(currentPassword.value)}&newPassword=${encodeURIComponent(newPassword.value)}&confirmPassword=${encodeURIComponent(confirmPassword.value)}`);

    }
  }