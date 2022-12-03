var currentPercentComplete = document.getElementById("percentComplete").value;
var percentComplete = document.getElementById("percentComplete").value;
var bandwidthUsage = "";

if(percentComplete < 100){
var getNetworkStatusDB = setInterval(getNetworkStatus, 20000);
}

function progressComplete() {
  if (percentComplete < (parseFloat(currentPercentComplete) + parseFloat(20))) {
      percentComplete = parseFloat(percentComplete) + parseFloat(0.048);
  }
  document.getElementById("deployProgress").style = "Width: " + percentComplete + "%";
}

var progressBar = setInterval(progressComplete, 100);

function secondsToDaysHms(d) {
  d = Number(d);
  var days = Math.floor(d / 86400);
  var h = Math.floor(d % 86400 / 3600);
  var m = Math.floor(d % 3600 / 60);
  var s = Math.floor(d % 3600 % 60);

  var daysDisplay = days > 0 ? days + (days == 1 ? " Day, " : " Days, ") : "";
  var hDisplay = h > 0 ? h + (h == 1 ? " Hour, " : " Hours, ") : "";
  var mDisplay = m > 0 ? m + (m == 1 ? " Minute, " : " Minutes, ") : "";
  var sDisplay = s > 0 ? s + (s == 1 ? " Second" : " Seconds") : "";
  return daysDisplay + hDisplay + mDisplay + sDisplay; 
}

function getNetworkStatus(){

var id = document.getElementById("networkID").value;

  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if (this.status == 200) {

      var response = JSON.parse(this.responseText),
          networkID = response.networkid,
          psk = response.ipsec_psk,
          l2tp_pass = response.l2tp_pass,
          networkIP = response.networkip,
          lanIP = response.lanip,
          wanIP = response.wanip,
          subnetMask = response.subnet,
          remoteIP = response.ipsec_remotewanip,
          remoteLanIP = response.ipsec_remotelanip,
          remoteSubnetMask = response.ipsec_remotesubnet;

      if(percentComplete < response.buildstatus) { percentComplete = parseFloat(percentComplete) + parseFloat(10); }
      currentPercentComplete = response.buildstatus;

      document.getElementById("networkid").innerText = networkID;
      document.getElementById("networkip").innerText = networkIP + " / " + subnetMask;
      document.getElementById("lanip").innerText = lanIP;
      document.getElementById("wanip").innerText = wanIP;
      document.getElementById("remotewanip").innerText = remoteIP;
      document.getElementById("remotelanip").innerText = remoteLanIP + " / " + remoteSubnetMask;
      document.getElementById("l2tp_pass").innerText = l2tp_pass;
      document.getElementById("psk").innerText = psk;
      document.getElementById("peerid").innerText = remoteIP;

      if(response.buildstatus == 100) {
      getBandwidthUsage();
      clearInterval(getNetworkStatusDB);
      clearInterval(progressBar);
      document.getElementById("firewallTabBtnListItem").style = "";
      document.getElementById("infoTabBtn").setAttribute("onclick","infoTab()");
      document.getElementById("firewallTabBtn").setAttribute("onclick","firewallTab()");
      document.getElementById("firewallTabBtn").classList.remove("disabled");
      document.getElementById("rebootNetworkBtn").classList.remove("disabled");
      document.getElementById("deployProgress").style = "Width: 100%";
      document.getElementById("deployProgress").setAttribute("class","progress-bar bg-success");
      
      if(!document.getElementById("draytekConfigBtn")) {
      draytekConfig("JSPC-Cloud-LAN2LAN.bak",`0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
NumberOfCfg:32,0,32,200,6,1,JSPC Cloud,0,0,300,0,` + wanIP + `,0,0,3,0,0,443,` + wanIP + `,0,0,0,0,???,,0,1,0,1,,0,` + psk + `,40,2,0,28800,3600,0,0,0,0,0,1,1,0,0,0,1,,Cloud,???,,1,0,` + psk + `,0,0,1,1,1,0,0,0,0,0,` + convertIpToDec(wanIP) + `,` + convertIpToDec(remoteLanIP) + `,` + convertIpToDec(convertSubnetMask(remoteSubnetMask)) + `,` + convertIpToDec(networkIP) + `,` + convertIpToDec(convertSubnetMask(subnetMask)) + `,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
0,???,0,0,300,0,0.0.0.0,0,0,2,0,0,0,,0,0,0,0,???,,0,1,0,1,,0,,40,0,0,28800,3600,0,0,0,0,1,1,0,1,1,0,0,,,???,,1,0,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,4294967040,1,0,0,0,0,LAN1,0
remotemore:0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
localmore:0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
0,0,0
virtualmap:0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
0,0,0,0
endoflan2lanfile!`
); }
    }
  }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getNetworkStatusDB=" + id);

}

getNetworkStatus();

function addNewFirewallRule() {
  
  var name = document.getElementById("newRuleName").value,
      publicPort = document.getElementById("newRulePublicPort").value,
      privatePort = document.getElementById("newRulePrivatePort").value,
      protocol = document.getElementById("newRuleProtocol").value,
      newRuleTarget = document.getElementById("newRuleTarget").value,
      newRuleTarget2 = document.getElementById("newRuleTarget2").value,
      newRuleTarget3 = document.getElementById("newRuleTarget3").value,
      newRuleTarget4 = document.getElementById("newRuleTarget4").value,
      newRuleSource = document.getElementById("newRuleSource").value,
      newRuleSource2 = document.getElementById("newRuleSource2").value,
      newRuleSource3 = document.getElementById("newRuleSource3").value,
      newRuleSource4 = document.getElementById("newRuleSource4").value,
      newRuleSource5 = document.getElementById("newRuleSource5").value,
      invalidFeedback = document.getElementById("invalid-feedback");

  if(name == "" || publicPort == "" || privatePort == "" || newRuleTarget == "" || newRuleTarget2 == "" || newRuleTarget3 == "" || newRuleTarget4 == "" || newRuleSource == "" || newRuleSource2 == "" || newRuleSource3 == "" || newRuleSource4 == "" || newRuleSource5 == "") { 
  
    if(newRuleSource5 == "") { invalidFeedback.innerText = "Please choose a source IP for this rule"; }
    if(newRuleSource4 == "") { invalidFeedback.innerText = "Please choose a source IP for this rule"; }
    if(newRuleSource3 == "") { invalidFeedback.innerText = "Please choose a source IP for this rule"; }
    if(newRuleSource2 == "") { invalidFeedback.innerText = "Please choose a source IP for this rule"; }
    if(newRuleSource == "") { invalidFeedback.innerText = "Please choose a source IP for this rule"; }
    if(newRuleTarget4 == "") { invalidFeedback.innerText = "Please choose a target IP for this rule"; }
    if(newRuleTarget3 == "") { invalidFeedback.innerText = "Please choose a target IP for this rule"; }
    if(newRuleTarget2 == "") { invalidFeedback.innerText = "Please choose a target IP for this rule"; }
    if(newRuleTarget == "") { invalidFeedback.innerText = "Please choose a target IP for this rule"; }
    if(privatePort == "") { invalidFeedback.innerText = "Please choose a private port for this rule"; }
    if(publicPort == "") { invalidFeedback.innerText = "Please choose a public port for this rule"; }
    if(name == "") { invalidFeedback.innerText = "Please enter a rule name"; }
    
    } else { invalidFeedback.innerText = "" }

    if(invalidFeedback.innerText == "") { 

      document.getElementById("addNewRuleCancelModalBtn").setAttribute("disabled","disabled")
      document.getElementById("newRuleName").setAttribute("disabled","disabled")
      document.getElementById("networkID").setAttribute("disabled","disabled")
      document.getElementById("newRulePublicPort").setAttribute("disabled","disabled")
      document.getElementById("newRulePrivatePort").setAttribute("disabled","disabled")
      document.getElementById("newRuleDirection").setAttribute("disabled","disabled")
      document.getElementById("newRuleProtocol").setAttribute("disabled","disabled")
      document.getElementById("newRuleTarget").setAttribute("disabled","disabled")
      document.getElementById("newRuleTarget2").setAttribute("disabled","disabled")
      document.getElementById("newRuleTarget3").setAttribute("disabled","disabled")
      document.getElementById("newRuleTarget4").setAttribute("disabled","disabled")
      document.getElementById("newRuleSource").setAttribute("disabled","disabled")
      document.getElementById("newRuleSource2").setAttribute("disabled","disabled")
      document.getElementById("newRuleSource3").setAttribute("disabled","disabled")
      document.getElementById("newRuleSource4").setAttribute("disabled","disabled")
      document.getElementById("newRuleSource5").setAttribute("disabled","disabled")
      document.getElementById("addNewRuleModalBtn").setAttribute("disabled","disabled")
      document.getElementById("addNewRuleModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`
  
  var rule = {};
  
  rule.networkid = document.getElementById("networkID").value;
  rule.name = document.getElementById("newRuleName").value;
  rule.protocol = document.getElementById("newRuleProtocol").value;
  // rule.direction = document.getElementById("newRuleDirection").value;
  rule.direction = "Inbound";
  rule.type = "Custom";
  rule.publicport = document.getElementById("newRulePublicPort").value;
  rule.privateport = document.getElementById("newRulePrivatePort").value;
  rule.sourceip = document.getElementById("newRuleSource").value + "." + document.getElementById("newRuleSource2").value + "." + document.getElementById("newRuleSource3").value + "." + document.getElementById("newRuleSource4").value + "/" + document.getElementById("newRuleSource5").value;
  rule.targetip = document.getElementById("newRuleTarget").value + "." + document.getElementById("newRuleTarget2").value + "." + document.getElementById("newRuleTarget3").value + "." + document.getElementById("newRuleTarget4").value;
  
  console.log(rule.sourceip)

    if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      location.reload()
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("addNewFirewallRule=" + JSON.stringify(rule));

}
  
}

function showRemoveRuleModal(networkid,id) {

  document.getElementById("removeRuleModalBtn").setAttribute("onclick","removeFirewallRule(" + networkid + "," + id + ")")
  $('#removeRuleModal').modal({
    backdrop: 'static',
    keyboard: false
  })
}

function removeFirewallRule(networkid, id) {

  document.getElementById("removeRuleModalCancelBtn").setAttribute("disabled","disabled")
  document.getElementById("removeRuleModalBtn").setAttribute("disabled","disabled")
  document.getElementById("removeRuleModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`

     if(window.XMLHttpRequest) {
     xmlhttp = new XMLHttpRequest();
   } else {
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onload = function () {
     if(this.status == 200) {
      location.reload()
     }
   }
   xmlhttp.open("POST", "control/control.php", true);
   xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   xmlhttp.send("removeFirewallRule=" + id + "&networkId=" + networkid);
  
}

function draytekConfig(filename, text) {
  var element = document.createElement('a');
  element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
  element.setAttribute('download', filename);
  element.setAttribute('class','btn btn-success');
  element.setAttribute('role','button');
  element.setAttribute('id','draytekConfigBtn');
  text = document.createTextNode("Draytek VPN Config");
  element.appendChild(text);
  document.getElementById("infoTab").appendChild(element);
}

function convertIpToDec(ip) {

  var ipAddressRegEx = /^(\d{0,3}\.){3}.(\d{0,3})$|^(\d{0,3}\.){5}.(\d{0,3})$/;
  var valid = ipAddressRegEx.test(ip);
  if (!valid) {
  return false;
  }
  var dots = ip.split('.');

  for (var i = 0; i < dots.length; i++) {
  var dot = dots[i];
  if (dot > 255 || dot < 0) {
    return false;
  }
  }
  if (dots.length == 4) {

  return ((((((+dots[0])*256)+(+dots[1]))*256)+(+dots[2]))*256)+(+dots[3]);
  } else if (dots.length == 6) {

  return ((((((((+dots[0])*256)+(+dots[1]))*256)+(+dots[2]))*256)+(+dots[3])*256)+(+dots[4])*256)+(+dots[5]);
  }
  return false;
}

function convertSubnetMask(subnet){

subnet = parseInt(subnet, 10);

switch(subnet){
    case 24:
      return '255.255.255.0';
    case 23:
      return '255.255.254.0';
    case 22:
      return '255.255.252.0';
    case 21:
      return '255.255.248.0';
    case 20:
      return '255.255.240.0';
    case 19:
      return '255.255.224.0';
    case 18:
      return '255.255.192.0';
    case 17:
      return '255.255.128.0';
    case 16:
      return '255.255.0.0';  
  }
}

function restartNetwork(networkid){

  document.getElementById("rebootNetworkModalCancelBtn").setAttribute("disabled", "disabled")
  document.getElementById("rebootNetworkModalBtn").setAttribute("disabled", "disabled")
  document.getElementById("rebootNetworkModalBtn").innerHTML = `<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>`

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      location.reload();
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("restartNetwork=" + networkid);
  
}

function monitoringTab(){
  document.getElementById("monitoringTab").style = "";
  document.getElementById("infoTab").style = "display:none";
  document.getElementById("firewallTab").style = "display:none";
  document.getElementById("monitoringTabBtn").setAttribute("class","nav-link active");
  document.getElementById("infoTabBtn").setAttribute("class","nav-link");
  document.getElementById("firewallTabBtn").setAttribute("class","nav-link");
}

function firewallTab(){
  document.getElementById("firewallTab").style = "";
  document.getElementById("monitoringTab").style = "display:none";
  document.getElementById("infoTab").style = "display:none";
  document.getElementById("firewallTabBtn").setAttribute("class","nav-link active");
  document.getElementById("monitoringTabBtn").setAttribute("class","nav-link");
  document.getElementById("infoTabBtn").setAttribute("class","nav-link");
}

function infoTab(){
  document.getElementById("infoTab").style = "";
  document.getElementById("firewallTab").style = "display:none";
  document.getElementById("monitoringTab").style = "display:none";
  document.getElementById("infoTabBtn").setAttribute("class","nav-link active");
  document.getElementById("firewallTabBtn").setAttribute("class","nav-link");
  document.getElementById("monitoringTabBtn").setAttribute("class","nav-link");
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

function getBandwidthUsage(){

  var id = document.getElementById("networkid").innerText;

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {

      var response = JSON.parse(this.responseText)
      document.getElementById("inboundTraffic").innerText = response[0].value + "MB";
      document.getElementById("outboundTraffic").innerText = response[1].value + "MB";
      document.getElementById("totalTraffic").innerText = (response[0].value + response[1].value) + "MB";
      loadPieChart(response[0].value, response[1].value)
      document.getElementById("monitoringTabBtn").setAttribute("onclick","monitoringTab()");
      document.getElementById("monitoringTabBtn").classList.remove("disabled");
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("getBandwidthUsage=" + id);
  
}

function resetBandwidthUsage(){

  var id = document.getElementById("networkid").innerText;

  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onload = function () {
    if(this.status == 200) {
      document.getElementById("inboundTraffic").innerText = "0MB";
      document.getElementById("outboundTraffic").innerText = "0MB";
      document.getElementById("totalTraffic").innerText = "0MB";
      loadPieChart("1","1")
      document.getElementById("resetBandwidthUsageLastReset").innerHTML = "<small>Last Reset: Just Now</small>";
      document.getElementById("resetBandwidthUsageBtn").classList.add("disabled");
      document.getElementById("resetBandwidthUsageBtn").setAttribute("onclick","");
    }
  }
  xmlhttp.open("POST", "control/control.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send("resetBandwidthUsage=" + id);

}