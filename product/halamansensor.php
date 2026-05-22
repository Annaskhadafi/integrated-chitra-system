<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<?php include 'header.php';?>

<style>
.tpms-card {
  border: 4px solid #ff4d6d;
  border-radius: 12px;
  padding: 12px;
  margin-bottom: 20px;
  background: #f8f9fa;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.tpms-header {
  text-align: center;
  font-size: 18px;
  font-weight: bold;
  color: #2c3e50;
}

.tpms-body {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-top: 10px;
}

.tpms-left img {
  width: 120px;
}

.tpms-right td {
  padding: 4px;
  font-size: 14px;
}

.critical {
  background-color: #ff4d4d;
  color: white;
  animation: blink 1s infinite;
}

@keyframes blink {
  50% { opacity: 0.5; }
}
</style>

<body class="nav-md">

<div class="container body">
<div class="main_container">

<?php include('template_menu.php');?>

<div class="top_nav">
<div class="nav_menu">
<div class="nav toggle">
<a id="menu_toggle"><i class="fa fa-bars"></i></a>
</div>

<ul class="nav navbar-nav navbar-right">
<li>
<h3>
<a style="margin-right:20px;">
LIVE DATA ● <?php echo date("l, Y-m-d"); ?>
</a>
</h3>
</li>
</ul>
</div>
</div>

<?php if($name!=""){?>

<div class="right_col" role="main">
<div class="row">
<div class="x_panel">

<div class="x_title">
<h2>Industrial Sensor Monitoring</h2>
</div>

<div class="x_content">
<div class="row" id="panelContainer">
  <h3>Loading...</h3>
</div>
</div>

</div>
</div>
</div>

<?php }?>

</div>
</div>

<script src="../vendors/jquery/dist/jquery.min.js"></script>
<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<script>

// =====================
const API_URL = "api_dummy.php";

// =====================
// AUDIO
// =====================
let alarmAudio = new Audio("https://actions.google.com/sounds/v1/alarms/alarm_clock.ogg");
let lastAlarm = Date.now() - 5000;
let audioUnlocked = false;

// unlock audio
document.addEventListener("click", function(){
    if(!audioUnlocked){
        alarmAudio.play().then(()=>{
            alarmAudio.pause();
            alarmAudio.currentTime = 0;
            audioUnlocked = true;
        }).catch(()=>{});
    }
});

// =====================
// STATUS RULE
// =====================
function getStatus(temp, pressure){

    let status = [];
    let critical = false;

    // TEMPERATURE >= 140
    if(temp >= 140){
        status.push("OVERHEAT");
        critical = true;
    }

    // PRESSURE >= 32
    if(pressure >= 32){
        status.push("OVER PRESS");
        critical = true;
    }

    if(status.length === 0){
        status.push("NORMAL");
    }

    return {
        text: status.join(" & "),
        critical: critical
    };
}

// =====================
async function loadData(){

    try{

        const res = await fetch(API_URL);
        const result = await res.json();

        if(result.status !== "success"){
            document.getElementById("panelContainer").innerHTML =
            "<h3 style='color:red'>API ERROR</h3>";
            return;
        }

        renderData(result.data);

    }catch(e){
        document.getElementById("panelContainer").innerHTML =
        "<h3 style='color:red'>Gagal load</h3>";
    }
}

// =====================
// RENDER DATA
// =====================
function renderData(data){

    let html = "";

    // HANYA TAMPILKAN 1 DEVICE
    data.slice(0,1).forEach(d=>{

        let temp = parseFloat(d.temperature);
        let pressure = parseFloat(d.pressure);

        let res = getStatus(temp, pressure);

        let cls = res.critical ? "critical" : "";

        // ALARM
        if(res.critical){
            let now = Date.now();

            if(now - lastAlarm > 5000){
                alarmAudio.currentTime = 0;
                alarmAudio.play().catch(()=>{});
                lastAlarm = now;
            }
        }

        html += `
        <div class="col-md-4">

            <div class="tpms-card ${cls}">

                <div class="tpms-header">
                    ${d.device_id}
                </div>

                <div class="tpms-body">

                    <div class="tpms-left">
                        <img src="images/control panel.png">
                    </div>

                    <div class="tpms-right">
                        <table>
                            <tr>
                                <td>Temp</td>
                                <td>${temp} °C</td>
                            </tr>

                            <tr>
                                <td>Pressure</td>
                                <td>${pressure} psi</td>
                            </tr>

                            <tr>
                                <td>Status</td>
                                <td><b>${res.text}</b></td>
                            </tr>
                        </table>
                    </div>

                </div>

                <div style="font-size:12px; margin-top:10px;">
                    ${d.timestamp}
                </div>

            </div>

        </div>
        `;
    });

    document.getElementById("panelContainer").innerHTML = html;
}

// =====================
window.onload = function(){
    loadData();
    setInterval(loadData, 3000);
}

</script>

</body>
</html>