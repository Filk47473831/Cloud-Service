<?php require_once("header.php"); ?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Profile</h1>
  </div>

  <div class="row">

    <div class="col-md-12 col-lg-12 col-xl-10">

      <div class="card position-relative">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user"></i>&nbsp;&nbsp;Update Profile</h6>
        </div>
        <div id="view-profile" class="card-body">
          <div class="row">
          <div class="col-xl-8">
            <div id="profileForm">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="profileName">Name</label>
                  <input disabled type="text" class="form-control" id="profileName" value="<?php echo getUser()['fullName']; ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="profileEmail">Email</label>
                  <input disabled type="text" class="form-control" id="profileEmail" value="<?php echo getUser()['emailAddress']; ?>">
                </div>
              </div>
            
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="currentPassword">Current Passphrase</label>
                  <input type="password" class="form-control" id="currentPassword" value="">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="newPassword">New Passphrase</label>
                  <input type="password" class="form-control" id="newPassword" value="">
                </div>
              </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="confirmPassword">Confirm Passphrase</label>
                  <input type="password" class="form-control" id="confirmPassword" value="">
                </div>
            </div>

            <button id="updatePasswordBtn" class="btn btn-success" onclick="updatePassword()">Update Passphrase</button><p id="passwordUpdateStatus" class="inlineElement"></p><span id="invalid-feedback" class="ml-3 text-small text-danger"></span><br>
            <button href="#" data-toggle="modal" data-target="#removeUserAccountModal" id="deleteAccountBtn" class="mt-3 btn btn-danger" >Delete Account</button>

            </div>

            

          </div>
          <div class="col-xl-4 d-none d-xl-block">
            <div>
                  <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="img/undraw_dev_productivity_umsq.svg" alt="">
            </div>
          </div>  
          </div>
        </div>
      </div>

    </div>

  </div>

</div>

</div>

<div class="modal fade" id="removeUserAccountModal" tabindex="-1" role="dialog" aria-labelledby="removeUserAccountModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="removeUserAccountModalLabel">Remove Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Remove your JSPC Cloud Services account?</p>
          <p class="text-danger">Warning: This will remove and destroy all Projects, all Virtual Networks, all Virtual Machines and all Disks created with this account. Destruction is non-reversible. You will then be logged out and your account will be permanently removed.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button disabled type="button" class="btn btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>

<script src="js/profile.js"></script>
<?php require_once("footer.php"); ?>