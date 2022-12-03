<?php require_once("header.php"); ?>

<div class="container-fluid">

          <h1 class="h3 mb-2 text-gray-800">Activity</h1>
          <p class="mb-4">Activities logged include user login, user logout, start/stop of Virtual Machines. Creation and destruction of Virtual Machines, Virtual Networks, Disks and Images.</p>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Log Entries</h6>
            </div>
            <div class="card-body">
              <div style="font-size:0.8rem" class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>User</th>
                      <th>Resource Type</th>
                      <th>Resource</th>
                      <th>Action</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>User</th>
                      <th>Resource Type</th>
                      <th>Resource</th>
                      <th>Action</th>
                      <th>Time</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php echo getLogEntriesForTable($_SESSION['id']); ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="js/demo/datatables-demo.js"></script>
  <script>

    $(document).ready( function () {
    $('#dataTable').DataTable();
    } );
    
  </script>

<?php require_once("footer.php"); ?>