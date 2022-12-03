<?php require_once("header.php"); ?>
<div class="container-fluid">
   <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Virtual Networks</h1>
   </div>
   <div class="row">
      <?php if($_GET){ {
         $networkID = $_GET['id'];
         if(getNetworkStatusDB($networkID)) { $networkStatus = getNetworkStatusDB($networkID); } else { header("Location: /"); }
         if(!checkPermission($_SESSION['id'],2,$_GET['id'])) { header("Location: /"); }
         } } else { header("Location: /"); } ?>
      <input hidden id="percentComplete" value="<?php echo htmlspecialchars($networkStatus['buildstatus']); ?>">
      <input hidden id="networkID" value="<?php echo htmlspecialchars($networkStatus['id']); ?>">
      <div class="col-md-12 col-lg-10 col-xl-8">
         <div class="card position-relative">
            <div class="card-header py-3">
               <h6 class="m-0 font-weight-bold text-primary"><i class="far fa-hdd"></i>&nbsp;&nbsp;<?php echo htmlspecialchars($networkStatus['name']); ?></h6>
            </div>
            <div class="progress" style="border-radius:.0rem !important;">
               <div id="deployProgress" class="<?php if($networkStatus['buildstatus'] == 100) { echo "progress-bar bg-success"; } else { echo "progress-bar progress-bar-striped progress-bar-animated bg-success"; }; ?>" role="progressbar" aria-valuenow="<?php echo htmlspecialchars($networkStatus['buildstatus']); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo htmlspecialchars($networkStatus['buildstatus']); ?>%"></div>
            </div>
            <div class="card-header">
               <ul class="nav nav-tabs card-header-tabs scroll-nav-tabs">
                  <li class="nav-item">
                     <a id="infoTabBtn" class="nav-link active" href="#">Info</a>
                  </li>
                  <li id="firewallTabBtnListItem" class="nav-item">
                     <a id="firewallTabBtn" class="nav-link disabled" href="#">Firewall</a>
                  </li>
                  <li id="monitoringTabBtnListItem" class="nav-item">
                     <a id="monitoringTabBtn" class="nav-link disabled" href="#">Monitoring</a>
                  </li>
               </ul>
            </div>
            <div id="view-network" class="card-body">
               <div class="row">
                  <div id="infoTab" class="col-10">
                     <p class="text-success">Deployment Info</p>
                     <dl class="row">
                        <!-- <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9"><?php // echo $networkStatus['name']; ?></dd> -->
                        <dt class="col-sm-3">ID</dt>
                        <dd id="networkid" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['networkid']); ?></dd>
<!--                    <dt class="col-sm-3">Password</dt>
                        <dd id="routerPassword" class="col-sm-9"><?php // echo $networkStatus['routerPassword']; ?></dd> -->
                        <dt class="col-sm-3">Network</dt>
                        <dd id="networkip" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['networkip']); ?> /  <?php echo htmlspecialchars($networkStatus['subnet']); ?></dt>
                        <dt class="col-sm-3">LAN IP Address</dt>
                        <dd id="lanip" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['lanip']); ?></dt>
                        <dt class="col-sm-3">WAN IP Address</dt>
                        <dd id="wanip" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['wanip']); ?></dt>
                     </dl>
                     <p class="text-success">L2TP VPN</p>
                     <dl class="row">
                        <dt class="col-sm-3">Username</dt>
                        <dd class="col-sm-9">cloud</dt>
                        <dt class="col-sm-3">Password</dt>
                        <dd id="l2tp_pass" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['l2tp_pass']); ?> /  <?php echo htmlspecialchars($networkStatus['l2tp_pass']); ?></dt>
                     </dl>
                     <p class="text-success">LAN to LAN VPN</p>
                     <dl class="row">
                        <dt class="col-sm-3">Remote Network Public IP</dt>
                        <dd id="remotewanip" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['ipsec_remotewanip']); ?></dt>
                        <dt class="col-sm-3">Remote Network Internal</dt>
                        <dd id="remotelanip" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['ipsec_remotelanip']); ?> /  <?php echo htmlspecialchars($networkStatus['ipsec_remotesubnet']); ?></dt>
                        <dt class="col-sm-3">IPSec Phase 1 Proposal</dt>
                        <dd class="col-sm-9">AES (256 bits) + SHA256 + DH Group 14 + Mutual PSK</dt>
                        <dt class="col-sm-3">IPSec Pre-Shared Key</dt>
                        <dd id="psk" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['ipsec_psk']); ?></dt>
                        <dt class="col-sm-3">Identifier</dt>
                        <dd class="col-sm-9">Cloud</dt>
                        <dt class="col-sm-3">Peer ID</dt>
                        <dd id="peerid" class="col-sm-9"><?php echo htmlspecialchars($networkStatus['ipsec_remotewanip']); ?></dt>
                        <dt class="col-sm-3">IPSec Phase 2</dt>
                        <dd class="col-sm-9">Encryption Protocol: AES (128 bits)</dt>
                        <dd class="col-sm-3"></dt>
                        <dd class="col-sm-9">Authenticity Protocols: MD5, SHA1, SHA256</dt>
                        <dd class="col-sm-3"></dt>
                        <dd class="col-sm-9">PFS: Off</dt>
                     </dl>
                     <a id="rebootNetworkBtn" href="#" data-toggle="modal" data-backdrop="static" data-target="#rebootNetworkModal" class="btn btn-primary disabled" role="button">Reboot Network</a>
                  </div>
                  <div id="firewallTab" style="display:none" class="col-12">
                     <p class="text-success">Inbound</p>
                     <div class="row">
                        <div id="currentInboundRules" class="form-group col-md-12">
                           <?php echo showInboundRules($networkID); ?>
                        </div>
                     </div>
                     <p class="text-success">Outbound</p>
                     <div class="row">
                        <div class="form-group col-md-12">
                           <?php // echo ("<pre>".print_r(showOutboundRules($networkID),true)."</pre>"); ?>
                           <p>No Outbound Rules</p>
                        </div>
                     </div>
                     <a id="addNewRuleBtn" href="#" data-toggle="modal" data-backdrop="static" data-target="#addNewRuleModal"  class="btn btn-primary" role="button">Add New Rule</a>
                  </div>
                  <div id="monitoringTab" style="display:none" class="col-12">
                     <div class="row align-items-center">
                        <div class="col-6">
                           <div class="chart-pie pt-4 pb-2">
                              <div class="chartjs-size-monitor">
                                 <div class="chartjs-size-monitor-expand">
                                    <div class=""></div>
                                 </div>
                                 <div class="chartjs-size-monitor-shrink">
                                    <div class=""></div>
                                 </div>
                              </div>
                              <canvas id="myPieChart" width="386" height="208" class="chartjs-render-monitor" style="display: block; width: 386px; height: 208px;"></canvas>
                           </div>
                           <div class="mt-4 text-center small">
                              <span class="mr-2">
                              <i class="fas fa-circle text-primary"></i> Download (MB)
                              </span>
                              <span class="mr-2">
                              <i class="fas fa-circle text-success"></i> Upload (MB)
                              </span>
                           </div>
                        </div>
                        <div class="col-xs-12 col-6">
                           <div class="row">
                              <div class="text-center col-12">
                                 <h5>Download</h5>
                                 <h3 id="inboundTraffic" class="text-primary">
                                 </h4>
                              </div>
                              <div class="text-center col-12">
                                 <h5>Upload</h5>
                                 <h3 id="outboundTraffic" class="text-success">
                                 </h4>
                              </div>
                           </div>
                           <div class="row mt-4"></div>
                           <div class="row">
                              <div class="text-center col-12">
                                 <h5>Total</h5>
                                 <h3 id="totalTraffic" class="text-info">
                                 </h4>
                              </div>
                           </div>
                           <div class="row mt-4"></div>
                           <div class="row">
                            <div class="text-center col-12">
                             <a id="resetBandwidthUsageBtn" href="#" onclick="resetBandwidthUsage()" class="btn btn-primary btn-sm" role="button">Reset Usage Stats</a>
                             <p id="resetBandwidthUsageLastReset" class="mt-2"><small>Last Reset: <?php echo htmlspecialchars($networkStatus['resetNetworkStats']); ?></small></p>
                            </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<div class="modal fade" id="addNewRuleModal" tabindex="-1" role="dialog" aria-labelledby="addNewRuleModal"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="addNewRuleModalLabel">Add New Rule</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <!-- <span aria-hidden="true">&times;</span> -->
            </button>
         </div>
         <div class="modal-body">
            <div class="form-group col-md-12">
               <div class="form-row">
                  <label for="newRuleName">Name</label>
                  <input id="newRuleName" type="text" class="form-control">
               </div>
            </div>
            <div class="form-group col-md-12">
               <div class="form-row">
                  <label for="newRuleDirection">Direction</label>
                  <select id="newRuleDirection" class="form-control">
                     <!-- <option selected>Choose...</option> -->
                     <option disabled selected value="Inbound">Inbound</option>
                     <!--             <option value="Outbound">Outbound</option> -->
                  </select>
               </div>
            </div>
            <div class="form-group col-md-12">
               <div class="form-row">
                  <label for="newRuleProtocol">Protocol</label>
                  <select id="newRuleProtocol" class="form-control">
                     <option selected value="tcp">TCP</option>
                     <option value="udp">UDP</option>
                  </select>
               </div>
            </div>
            <div class="form-group col-md-5">
               <div class="form-row">
                  <label for="newRulePublicPort">Public Port</label>
                  <input type="number" min="1" max="65535" class="form-control" id="newRulePublicPort">
               </div>
            </div>
            <div class="form-group col-md-5">
               <div class="form-row">
                  <label for="newRulePrivatePort">Private Port</label>
                  <input type="number" min="1" max="65535" class="form-control" id="newRulePrivatePort">
               </div>
            </div>
            <div class="form-group col-md-8">
               <div class="form-row">
                  <label for="newRuleSource">Source IP ( 0.0.0.0 / 0 for allow all)</label>
               </div>
               <div class="form-row">
                  <p><input type="number" class="numberInput" id="newRuleSource" value="0" min="0" max="255"> . <input type="number" class="numberInput" id="newRuleSource2" value="0" min="0" max="255"> . <input type="number" class="numberInput" id="newRuleSource3" value="0" min="0" max="255"> . <input type="number" class="numberInput" id="newRuleSource4" value="0" min="0" max="255">&nbsp;&nbsp;/&nbsp;<input type="number" class="numberInput" id="newRuleSource5" value="0" min="0" max="32"></p>
               </div>
            </div>
            <div class="form-group col-md-8">
               <div class="form-row">
                  <label for="newRuleTarget">Target IP</label>
               </div>
               <div class="form-row">
                  <p><input type="number" class="numberInput" id="newRuleTarget" value="172" min="1" max="254"> . <input type="number" class="numberInput" id="newRuleTarget2" value="25" min="1" max="254"> . <input type="number" class="numberInput" id="newRuleTarget3" value="1" min="1" max="254"> . <input type="number" class="numberInput" id="newRuleTarget4" value="1" min="1" max="254"></p>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <span id="invalid-feedback" class="ml-3 text-small text-danger"></span>
            <button id="addNewRuleCancelModalBtn" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button id="addNewRuleModalBtn" type="button" class="btn btn-success"
               onclick="addNewFirewallRule();">Save</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="removeRuleModal" tabindex="-1" role="dialog" aria-labelledby="removeRuleModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="removeRuleModalLabel">Remove Firewall Rule</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Remove this Rule?</p>
          <p class="text-danger">Warning: Rule will be permanently removed. This action is non-reversible.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button id="removeRuleModalCancelBtn" type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button id="removeRuleModalBtn" type="button" class="btn btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rebootNetworkModal" tabindex="-1" role="dialog" aria-labelledby="rebootNetworkModal"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rebootNetworkModalLabel">Reboot Network</h5>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p>Reboot this network?</p>
          <p class="text-danger">Warning: IPSec VPN connection may not be automatically restored.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button id="rebootNetworkModalCancelBtn" type="button" class="btn btn-success" data-dismiss="modal">No</button>
        <button onclick="restartNetwork('<?php echo htmlspecialchars($networkStatus['networkid']); ?>')" id="rebootNetworkModalBtn" type="button" class="btn btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>

<script src="js/viewnetwork.js"></script>
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="js/bandwidth-pie.js"></script>
<?php require_once("footer.php"); ?>
