<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once("../handlers/main.php"); ?>
<?php authenticated(); ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="JSPC Cloud Services" />
  <meta name="keywords" content="JSPC Cloud Services" />
  <meta name="author" content="Chris Groves, https://www.jspc.co.uk/">
  <meta name="date" content="2020-09-01" />
  <meta name="Referrer-Policy" value="no-referrer" />
  <meta name="robots" content="noindex,nofollow">
  <meta property="og:description" content="JSPC Cloud Services" />
  <meta property="og:title" content="JSPC Cloud Services">
  <meta property="og:url" content="https://cloud.jspc.co.uk" />
  <meta property="og:image" content="img/apple-touch-icon.png" />
  <meta property="og:logo" content="img/apple-touch-icon.png" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="JSPC Cloud Services" />

  <title>JSPC Cloud Services - Management</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link href="css/extra.css" rel="stylesheet">
  <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

  <script src="vendor/jquery/jquery.js"></script>

</head>

<body id="page-top" class="sidebar-toggled">


  <div id="wrapper" class="unselectable">


    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">


      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon">
          <i style="font-size: 20px" class="fas fa-home"></i>
        </div>
        <div class="sidebar-brand-text mx-2">Cloud Services</div>
      </a>


      <hr class="sidebar-divider my-0">


      <li class="nav-item active">
        <a class="nav-link" href="vms">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>


      <hr class="sidebar-divider">


      <div class="sidebar-heading">
        Manage
      </div>


      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProjects"
          aria-expanded="true" aria-controls="collapseProjects">
          <i class="fas fa-project-diagram"></i>
          <span>Projects</span>
        </a>
        <div id="collapseProjects" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Actions:</h6>
            <a class="collapse-item" href="addproject">Add Project</a>
            <a class="collapse-item" href="projects">View Projects</a>
          </div>
        </div>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseNetworks" aria-expanded="true"
          aria-controls="collapseNetworks">
          <i class="fas fa-network-wired"></i>
          <span>Virtual Networks</span>
        </a>
        <div id="collapseNetworks" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Actions:</h6>
            <a class="collapse-item" href="addnetwork">Add Network</a>
            <a class="collapse-item" href="networks">View Networks</a>
          </div>
        </div>
      </li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVMs" aria-expanded="true"
          aria-controls="collapseVMs">
          <i class="fas fa-tv"></i>
          <span>Virtual Machines</span>
        </a>
        <div id="collapseVMs" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Actions:</h6>
            <a class="collapse-item" href="addvm">Add VM</a>
            <a class="collapse-item" href="vms">View VMs</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDisks" aria-expanded="true"
          aria-controls="collapseDisks">
          <i class="fas fa-fw fa-hdd"></i>
          <span>Virtual Disks</span>
        </a>
        <div id="collapseDisks" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Actions:</h6>
            <a class="collapse-item" href="adddisk">Add Disk</a>
            <a class="collapse-item" href="disks">View Disks</a>
          </div>
        </div>
      </li>

      <hr class="sidebar-divider">


      <div class="sidebar-heading">
        Misc.
      </div>


      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
          aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-wrench"></i>
          <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Actions:</h6>
            <a class="collapse-item" href="#">Terminal</a>
            <a class="collapse-item" href="#">Monitoring</a>
            <a class="collapse-item" href="#">Images</a>
          </div>
        </div>
      </li>


      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Reports</span></a>
      </li>

      <hr class="sidebar-divider d-none d-md-block">

      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
      
      <hr class="sidebar-divider d-none d-md-block">

    </ul>



    <div id="content-wrapper" class="d-flex flex-column">

      <div id="content">

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>


<!--           <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input disabled type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button disabled class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form> -->


          <ul class="navbar-nav ml-auto">


        <!--    <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a> -->

              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input disabled type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                      aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button disabled class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo getUser()['fullName']; ?></span>
                <i class="fas fa-user fa-1x"></i>
              </a>

              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="activity">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>