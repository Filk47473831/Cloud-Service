<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once("../handlers/main.php"); ?>
<?php authenticated_landing_page(); ?>
<!DOCTYPE html>
<html lang="en" style="scroll-behavior: smooth">

<head>
  <title>JSPC Cloud Services</title>

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

  <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
  <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet" />
  <link href="css/aos.css" rel="stylesheet" />
  <link href="css/extra.css" rel="stylesheet" />

  <script data-search-pseudo-elements defer src="js/all.min.js" crossorigin="anonymous"></script>
  <script src="js/feather.min.js" crossorigin="anonymous"></script>
</head>

<body id="page-top">
  <div id="layoutDefault" class="unselectable">
    <div id="layoutDefault_content">
      <main>
        <nav class="navbar navbar-marketing navbar-expand-lg bg-white navbar-light">
          <div class="container">
<a class="navbar-brand text-primary" href="/">JSPC Cloud Services</a><?php if(!isset($_SESSION['id'])) { ?><a style="font-size: 0.5rem;" class="btn-primary btn rounded-pill px-4 ml-lg-4" href="https://cloud.jspc.co.uk/login">Login<i class="fas fa-arrow-right ml-1"></i></a><?php } else { ?><a style="font-size: 0.5rem;" class="btn-success btn rounded-pill px-4 ml-lg-4" href="vms">Dashboard<i class="fas fa-arrow-right ml-1"></i></a><?php } ?>
          </div>
    </div>
    </nav>
    <header class="page-header page-header-light bg-white">
      <div class="page-header-content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-10 text-center" data-aos="fade">
              <h1 class="page-header-title">Virtualise your Office with the JSPC Cloud</h1>
              <p class="page-header-text">Power down your energy hungry on-premise servers and migrate your applications and databases to our secure cloud infrastructure</p>
              <a class="btn btn-primary btn-marketing rounded-pill lift lift-sm" href="#pricing">See Pricing</a><a class="btn btn-link btn-marketing rounded-pill" href="#features">Feature List</a>
            </div>
          </div>
        </div>
      </div>
      <div class="svg-border-rounded text-light">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0"></path></svg>
      </div>
    </header>
    <section class="bg-light pb-10 pt-1">
      <div class="container">
        <div class="device-laptop text-gray-200 mt-n10" data-aos="fade-up">
          <svg class="device-container" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="83.911 298.53 426.962 243.838"><path d="M474.843 516.208V309.886c0-6.418-4.938-11.355-11.354-11.355H131.791c-6.417 0-11.354 4.938-11.354 11.355v206.816H83.911v13.326c4.938 7.896 31.098 12.34 40.969 12.34h345.024c10.366 0 36.526-4.936 40.969-12.34v-13.326h-36.03v-.494zM134.26 313.341h326.762v203.361H134.26V313.341z"></path></svg><img
            class="device-screenshot" src="img/splash.png" />
        </div>
      </div>
    </section>
    <section class="bg-light pb-10 pt-0">
      <div class="container">
        <div class="row brands text-gray-500 align-items-center">
          <div class="col-sm-12 col-lg-3 d-flex justify-content-center mb-5 mb-lg-0">
            <img src="img/microsoft.png" style="height:60px">
          </div>
          <div class="col-sm-12 col-lg-3 d-flex justify-content-center mb-5 mb-lg-0">
            <img src="img/hpe.png" style="height:60px">
          </div>
          <div class="col-sm-12 col-lg-3 d-flex justify-content-center mb-5 mb-lg-0">
            <img src="img/dell.png" style="height:75px">
          </div>
          <div class="col-sm-12 col-lg-3 d-flex justify-content-center mb-5 mb-lg-0">
            <img src="img/ubuntu.png" style="height:50px">
          </div>
        </div>
      </div>
      <div class="svg-border-rounded text-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0"></path></svg>
      </div>
    </section>
    <section class="bg-white py-10" id="features">
      <div class="container">
        <div class="row text-center">
          <div class="col-lg-4 mb-5 mb-lg-0">
            <div class="icon-stack icon-stack-xl bg-gradient-primary-to-secondary text-white mb-4"><i data-feather="layers"></i></div>
            <h3>Enterprise SSD Storage</h3>
            <p class="mb-0">Optimized for greater performance and endurance. Perfect for high read, high write databases.</p>
          </div>
          <div class="col-lg-4 mb-5 mb-lg-0">
            <div class="icon-stack icon-stack-xl bg-gradient-primary-to-secondary text-white mb-4"><i data-feather="smartphone"></i></div>
            <h3>1Gbps Connection</h3>
            <p class="mb-0">Bandwidth intensive applications are a breeze.</p>
          </div>
          <div class="col-lg-4">
            <div class="icon-stack icon-stack-xl bg-gradient-primary-to-secondary text-white mb-4"><i data-feather="code"></i></div>
            <h3>Managed Backups</h3>
            <p class="mb-0">Stress free daily backup checkpoints and manual checkpointing controlled by you.</p>
          </div>
        </div>
      </div>
    </section>
    <section class="bg-light py-10">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <div class="text-center">
              <div class="text-xs text-uppercase-expanded text-primary mb-2">Features</div>
              <h2 class="mb-5">Cloud Services that can free up your workflow</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-6 mb-5" data-aos="fade-up">
            <div class="card text-center text-decoration-none h-100 lift">
              <div class="card-body py-5">
                <div class="icon-stack icon-stack-lg bg-green-soft text-green mb-4"><i data-feather="layers"></i></div>
                <h5>Setup in 5mins</h5>
                <p class="card-text small">Spin up a new server while you're grabbing a drink!</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5" data-aos="fade-up" data-aos-delay="150">
            <div class="card text-center text-decoration-none h-100 lift">
              <div class="card-body py-5">
                <div class="icon-stack icon-stack-lg bg-orange-soft text-orange mb-4"><i class="fab fa-windows"></i></div>
                <h5>Windows Server</h5>
                <p class="card-text small">Fully licensed Server 2019 Standard from Microsoft.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5" data-aos="fade-up" data-aos-delay="100">
            <div class="card text-center text-decoration-none h-100 lift">
              <div class="card-body py-5">
                <div class="icon-stack icon-stack-lg bg-red-soft text-red mb-4"><i class="fab fa-ubuntu"></i></div>
                <h5>Ubuntu Server</h5>
                <p class="card-text small">We also offer this lightweight Linux distro.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="150">
            <div class="card text-center text-decoration-none h-100 lift">
              <div class="card-body py-5">
                <div class="icon-stack icon-stack-lg bg-yellow-soft text-yellow mb-4"><i data-feather="layout"></i></div>
                <h5>Private Network</h5>
                <p class="card-text small">Create your own private network in the Cloud and connect your devices.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5 mb-lg-0" data-aos="fade-up">
            <div class="card text-center text-decoration-none h-100 lift">
              <div class="card-body py-5">
                <div class="icon-stack icon-stack-lg bg-purple-soft text-purple mb-4"><i data-feather="book"></i></div>
                <h5>Dedicated Support</h5>
                <p class="card-text small">Managed support ticketing system for any help you require.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
            <div class="card text-center text-decoration-none h-100 lift">
              <div class="card-body py-5">
                <div class="icon-stack icon-stack-lg bg-blue-soft text-blue mb-4"><i data-feather="code"></i></div>
                <h5>Additional Storage</h5>
                <p class="card-text small">Add Additional Storage Disks and move easily between devices.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="bg-white py-10">
      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-lg-6">
            <div class="mb-5">
              <h2>Tier III Data Centre</h2>
              <p class="lead">When you choose to host with us, you reap the benefits of our chosen colocation provider.</p>
            </div>
            <div class="row">
              <div class="col-md-6 mb-4">
                <h6>South Coast, UK</h6>
              </div>
              <div class="col-md-6 mb-4">
                <h6>600mm raised floor</h6>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-4">
                <h6>Cold aisle containment</h6>
              </div>
              <div class="col-md-6 mb-4">
                <h6>No single point of failure</h6>
              </div>
              <div class="col-md-6 mb-4">
                <h6>Dual grid supplies</h6>
              </div>
              <div class="col-md-6 mb-4">
                <h6>N+1 Air Conditioning</h6>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-4">
                <h6>2(N+1) UPS protection with 20 minutes autonomy</h6>
              </div>
              <div class="col-md-6 mb-4">
                <h6>N+1 Generator protection with 5(10) days fuel</h6>
              </div>
            </div>
          </div>
          <div class="col-md-9 col-lg-6" data-aos="slide-left">
            <div class="mb-4">
              <div class="content-skewed content-skewed-left"><img class="img-fluid content-skewed-item shadow-lg rounded-lg" src="img/datacentre.jpg" /></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="bg-light pt-10" id="pricing">
      <div class="container">
        <div class="text-center mb-5">
          <h2>Our Pricing</h2>
          <p class="lead">Simple pricing, no surprises.</p>
        </div>
        <div class="row z-1">
          <div class="col-lg-4 mb-5 mb-lg-n10" data-aos="fade-up" data-aos-delay="100">
            <div class="card pricing h-100">
              <div class="card-body p-5">
                <div class="text-center">
                  <div class="badge badge-light badge-pill badge-marketing badge-sm">Silver</div>
                  <div class="pricing-price"><sup>£</sup>3.95<span class="pricing-price-period">/day*</span></div>
                </div>
                <ul class="fa-ul pricing-list">
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">1 x 2GB 2/Core VPS</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">60GB SSD</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Built-in Backups</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Private Virtual Network</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Basic Support</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Windows Server</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-circle text-gray-200"></i></span><span class="text-dark">Ubuntu Server</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-circle text-gray-200"></i></span><span class="text-dark">Additional Storage</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-5 mb-lg-n10" data-aos="fade-up">
            <div class="card pricing h-100">
              <div class="card-body p-5">
                <div class="text-center">
                  <div class="badge badge-yellow-soft badge-pill badge-marketing badge-sm text-primary">Gold</div>
                  <div class="pricing-price"><sup>£</sup>5.92<span class="pricing-price-period">/day*</span></div>
                </div>
                <ul class="fa-ul pricing-list">
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">1 x 4GB 4/Core VPS</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">60GB SSD</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Built-in Backups</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Private Virtual Network</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Full Support</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Windows Server</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Ubuntu Server</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-circle text-gray-200"></i></span><span class="text-dark">Additional Storage</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-lg-n10" data-aos="fade-up" data-aos-delay="100">
            <div class="card pricing h-100">
              <div class="card-body p-5">
                <div class="text-center">
                  <div class="badge badge-secondary-soft badge-pill badge-marketing badge-sm text-primary">Platinum</div>
                  <div class="pricing-price"><sup>£</sup>7.89<span class="pricing-price-period">/day*</span></div>
                </div>
                <ul class="fa-ul pricing-list">
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">1 x 8GB 8/Core VPS</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">60GB SSD</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Built-in Backups</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Private Virtual Network</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Full Support</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Windows Server</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Ubuntu Server</span>
                  </li>
                  <li class="pricing-list-item">
                    <span class="fa-li"><i class="far fa-check-circle text-teal"></i></span><span class="text-dark">Additional Storage</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="svg-border-rounded text-dark">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144.54 17.34" preserveAspectRatio="none" fill="currentColor"><path d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0"></path></svg>
      </div>
    </section>
    <section class="bg-dark pb-10 pt-15">
      <div class="container">
        <div class="row mb-10 mt-5">
          <div class="col-lg-6 mb-5">
            <div class="d-flex h-100">
              <div class="icon-stack flex-shrink-0 bg-teal text-white"><i class="fas fa-question"></i></div>
              <div class="ml-4">
                <h5 class="text-white">Are there any bandwidth limitations?</h5>
                <p class="text-white-50">You get up to 3TB of shared bandwidth per month. Additional bandwidth is charged per 50GB (approx £5)</p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-5">
            <div class="d-flex h-100">
              <div class="icon-stack flex-shrink-0 bg-teal text-white"><i class="fas fa-question"></i></div>
              <div class="ml-4">
                <h5 class="text-white">Is there a money back guarantee?</h5>
                <p class="text-white-50">Yes! If you're not happy with the performance within the first 7 days, then we will give you your money back.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="d-flex h-100">
              <div class="icon-stack flex-shrink-0 bg-teal text-white"><i class="fas fa-question"></i></div>
              <div class="ml-4">
                <h5 class="text-white">Do I get free updates?</h5>
                <p class="text-white-50">When the next versions of Windows Server and Ubuntu are released, you will be able to use them.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="d-flex h-100">
              <div class="icon-stack flex-shrink-0 bg-teal text-white"><i class="fas fa-question"></i></div>
              <div class="ml-4">
                <h5 class="text-white">Do you guarantee uptime?</h5>
                <p class="text-white-50">We do! We can guarantee 99.9% uptime throughout the year.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="bg-white pb-10">
      <div class="container">
        <div class="mt-5 row align-items-center">
          <div class="col-lg-6">
            <h4>Register your interest</h4>
            <p class="lead text-gray-500 mb-0">We will contact you to see if we can fit your requirements!</p>
          </div>
          <div class="col-lg-6">
            <div class="input-group mt-3 mb-2">
              <input class="form-control form-control-solid" type="text" placeholder="youremail@example.com" aria-label="Recipient's username" aria-describedby="button-addon2" />
              <div class="input-group-append"><button disabled class="btn btn-primary" id="button-addon2" type="button">I'm interested!</button></div>
            </div>
          </div>
        </div>
      </div>
      <div class="svg-border-waves text-dark">
        <svg class="wave" style="pointer-events: none" fill="currentColor" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1920 75">
                                <defs>
                                    <style>
                                        .a {
                                            fill: none;
                                        }
                                        .b {
                                            clip-path: url(#a);
                                        }
                                        .d {
                                            opacity: 0.5;
                                            isolation: isolate;
                                        }
                                    </style>
                                    <clippath id="a"><rect class="a" width="1920" height="75"></rect></clippath>
                                </defs>
                                <title>wave</title>
                                <g class="b"><path class="c" d="M1963,327H-105V65A2647.49,2647.49,0,0,1,431,19c217.7,3.5,239.6,30.8,470,36,297.3,6.7,367.5-36.2,642-28a2511.41,2511.41,0,0,1,420,48"></path></g>
                                <g class="b"><path class="d" d="M-127,404H1963V44c-140.1-28-343.3-46.7-566,22-75.5,23.3-118.5,45.9-162,64-48.6,20.2-404.7,128-784,0C355.2,97.7,341.6,78.3,235,50,86.6,10.6-41.8,6.9-127,10"></path></g>
                                <g class="b"><path class="d" d="M1979,462-155,446V106C251.8,20.2,576.6,15.9,805,30c167.4,10.3,322.3,32.9,680,56,207,13.4,378,20.3,494,24"></path></g>
                                <g class="b"><path class="d" d="M1998,484H-243V100c445.8,26.8,794.2-4.1,1035-39,141-20.4,231.1-40.1,378-45,349.6-11.6,636.7,73.8,828,150"></path></g>
                            </svg>
      </div>
    </section>
    </main>
  </div>
  <div id="layoutDefault_footer">
    <footer class="footer pt-10 pb-5 mt-auto bg-dark footer-dark">
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <div class="footer-brand">JSPC Cloud Services</div>
            <div class="mb-3">Work free, work in the Cloud</div>
            <div class="icon-list-social mb-5">
              <a class="icon-list-social-link" href="https://en-gb.facebook.com/jspccomputerservices/"><i class="fab fa-facebook"></i></a>
              <a class="icon-list-social-link" href="https://twitter.com/jspccomputers"><i class="fab fa-twitter"></i></a>
              <a class="icon-list-social-link" href="https://www.jspc.co.uk"><i class="fas fa-desktop"></i></a>
            </div>
          </div>
          <div class="col-lg-9">
            <div class="row">
              <div class="col-12 mb-2 mb-lg-0">
                <p><small>* 3 Year Fixed Term Agreement. First year payable upfront.</small></p>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-5" />
        <div class="row align-items-center">
          <div class="col-md-6 small">Copyright &copy; Chris Groves - JSPC Computer Services 2022</div>
        </div>
      </div>
    </footer>
  </div>
  </div>
  
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="js/landing.js"></script>
  <script src="js/aos.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
  <script>
    AOS.init({
      disable: 'mobile',
      duration: 600,
      once: true
    });
  </script>
</body>

</html>