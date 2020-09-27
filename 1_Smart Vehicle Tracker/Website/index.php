

<?php  
$servername = "127.0.0.1"; 
$username = "root"; 
$password = ""; 
$dbname = "assam_police_hackathon"; 

$conn = new mysqli($servername, $username, $password, $dbname); 



if ($conn->connect_error) { 
  die("Connection failed: " . $conn->connect_error); 
} 

$lat = '';
$longitude = '';
$speed = '';
$time = '';
$altitude = '';
$temperature = '';
$direction = '';
$km = '';


// fetch  
$sql1 = "SELECT altitude,time FROM 1_smart_vehicle_tracker
ORDER BY id DESC 
LIMIT 0,20"; 
$sql2 = "SELECT lat,longitude  FROM 1_smart_vehicle_tracker
ORDER BY id DESC 
LIMIT 0,20";
$sql3 = "SELECT speed, temperature, direction, km  FROM 1_smart_vehicle_tracker
LIMIT 0,1";

$result = mysqli_query($conn, $sql1);
$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);

while ($row = mysqli_fetch_array($result)) {
  $altitude = $altitude . '"'. $row['altitude'] .'",';
	$time = $time . '"'. $row['time'] .'",';
}
while ($row = mysqli_fetch_array($result2)) {
	$lat = $row['lat'];
	$longitude =$row['longitude'];
}
while ($row = mysqli_fetch_array($result3)) {
	$temperature = $row['temperature'];
	$speed = $row['speed'];
  $direction = $row['direction'];
  $km = $row['km'];
}

$temperature = trim($temperature,",");
$lat = trim($lat,","); 
$time = trim($time,",");
$speed = trim($speed,",");
$longitude = trim($longitude,","); 
$direction = trim($direction,",");
$altitude = trim($altitude,",");
$km = trim($km,",");


?> 

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Team-Parko</title>
  </head>
<body>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <!-- Insert your API key -->
    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBxNa7ZbMb4hYRdf6Npi-RUaZyPMpLqmA&callback=initMap&libraries=places&v=weekly"
    defer
    ></script>
    <script src="dist/gauge.min.js"></script>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white">
      <a class="navbar-brand" href="https://www.instagram.com/dhanuzch/" target="_blank">
        <img src="/assamhack/img/logo.png" width="50" height="50" class="d-inline-block align-top" alt="">
          <span class="navbar-text text-danger .display-3">
            For Assam Police by Team-Parko
          </span>
        </a> 
        <form >
          <input type="text1" name="search" placeholder="   Enter device ID">
        </form>  
    </nav>

<!-- Content -->
<div class="container bg-white">
  <div class=" row">
    <div class="container col-xs-12 col-sm-4 bg-dark  text-dark">
        <!-- Map 1 -->
        <div class="card" style="width: 100%; height: 100%">
          <div class="card-body"  >
            <h3 class=" .display-3 text-center">Pre-planned Route</h3>
            <div style="display: none"class="container col-xs-12 col-sm-4">
              <div> 
                <input
                  id="origin-input"
                  class="controls"
                  type="text"
                  placeholder="Origin"
                />
                
                <input
                  id="destination-input"
                  class="controls"
                  type="text"
                  placeholder="Destination"
                />
              </div>
              <div class="controls2">
                <div id="mode-selector" class="controls">
                  <input
                    type="radio"
                    name="type"
                    id="changemode-driving"
                    checked="checked"
                  />
                  <label for="changemode-driving">Driving</label>
                  <br>
                  <input type="radio" name="type" id="changemode-transit" />
                  <label for="changemode-transit">Transit</label>
                  <br>
                  <input type="radio" name="type" id="changemode-walking" />
                  <label for="changemode-walking">Walking</label>
                </div>
              </div>
            </div>
            <div id="map1"></div>
          </div>
        </div>
      </div>
        
    <div class="container col-xs-12 col-sm-4 bg-danger">
      <!-- Map 2 -->
      
        <div class="card-body">
        <div class="card" style="width: 100%; height: 70%">
        <div>
          <img src="/assamhack/img/textlogo.png" class="img-rounded center" style="width:100%;height:150px;" alt="Text Logo">
        </div>
          <h3 class=" .display-3 text-center  text-dark">Live-Tracking</h3>
          <div id="map"></div>
        </div>
      </div>
    </div>

    <div class="container col-xs-12 col-sm-4 bg-dark  text-white">
      <div class="card" style="width: 100%;">
        <div class="card-body">
          <!-- Speedometer--> 
          <h3 class=" .display-3 text-dark text-center">Speed</h3>          
          <div id="gauge" class="gauge-container"></div>
        </div>
      </div>

      <div class="card" style="width: 100%;">
        <div class="card-body">
          <div class="row3 text-dark">
            <!-- Temperature--> 
            <h3 class=" text-dark .display-3 text-center">Temperature</h3>
            <div class="containertemp">
            <div class="temperature text-white"><?php echo $temperature; ?>°C</div>
            </div> 
          </div>
        </div>
      </div>
      <div class="card" style="width: 100%;">
        <div class="card-body">
          <div class="row4 text-light">
              <!-- Altitude Graph-->
            <div class="chart-container1" style="width: 100%">
                  <h3 class=" .display-3 text-dark text-center">Altitude Graph</h3>   
                  <canvas id="myChart1" style="width: 100%; background: #FEFEFE; border: 1px solid #555652; margin-top: 10px;"></canvas>
          </div>
        </div>
      </div>

      </div>
    </div>       
  </div>
</div>     
</div>         


<div class="container bg-white">
  <div class=" row">
    <!-- Number of km travelled-->
    <div class="container bg-dark col-xs-12 col-sm-4">
      <div class="card bg-white text-dark">
        <h5 class="text-center">   Distance travelled: <?php echo $km; ?> km </h5>
        <div class="col text-center"><button class="buttonreset">Reset</button></div>
      </div>
    </div>

    <div class="container bg-danger col-xs-12 col-sm-4">
      <div class="card bg-white text-dark">
        <!-- Time taken from start of the trip-->
        <h5 class="text-center">   Time taken from start of the trip: 		<div class="stopwatch"></div>
        <ul class="results"></ul></h5>
        <nav class="controls">
        <div class="col text-center">
        <button class="buttonreset" onClick="stopwatch.start()";>Start</button>
        <button class="buttonreset" onClick="stopwatch.restart()";>Reset</button>
        <button class="buttonreset" onClick="stopwatch.stop()";>Stop</button>
        </div>
		    </nav>
      </div> 
    </div>

    <div class="container bg-dark col-xs-12 col-sm-4">
          <!-- Direction -->
          <div class="row">
            <div class="col col-xs-6 col-sm-6">
              <div class="card bg-white text-dark">
                <h5 class="text-center ">  Direction: </h5>
                <h5 class="text-center "> <?php echo $direction; ?> </h5>
              </div>
            </div>

              <div class="col col-xs-6 col-sm-6">
                <div class="card bg-white text-dark">
                  <div class="row2 text-dark">
                    <!-- Speed Alert-->
                    <div class="col">
                      <h1 class="text-center ">  </h1>
                      <button class="button">Overspeeding</button>
                      <h1 class="text-center ">  </h1>
                    </div>
                  </div>
                </div>
              </div>
        </div> 
    </div>
  </div>
</div>
</body>


<!--=========================
            Styles
===========================-->

<style>
body {
/* Hide Scroll Bar */
  overflow-x: hidden;
}
</style>
<style type="text/css">
/* Background */
    body { background:white !important; } 
</style>

<style>
/* Reset Button */
  .buttonreset {
  background-color:red;
  border-radius: 8px;
  color: white;
  padding: 10px 22x;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
  border: none;
  font-family: Arial;
  font-size: 18px;
}
</style>
<style>
  .button {
  /* Overspeeding Alert */
  background-color: #B20000;
  -webkit-border-radius: 18px;
  border-radius: 18px;
  border: none;
  color: #FFFFFF;
  cursor: not-allowed;
  font-family: Arial;
  font-size: 20px;
  -webkit-animation: glowing 1500ms infinite; /* Use this while scripting*/


}
@-webkit-keyframes glowing {
  0% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; -webkit-box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; -webkit-box-shadow: 0 0 3px #B20000; }
}

@-moz-keyframes glowing {
  0% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; -moz-box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; -moz-box-shadow: 0 0 3px #B20000; }
}

@-o-keyframes glowing {
  0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
}

@keyframes glowing {
  0% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
  50% { background-color: #FF0000; box-shadow: 0 0 40px #FF0000; }
  100% { background-color: #B20000; box-shadow: 0 0 3px #B20000; }
  }
}
</style>

<style>
/* Map 2 */   
#map {
height: 100%;
}
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}
</style>


<style> /*Speedometer */
.gauge-container > .gauge > .dial {
  stroke: #334455;
  stroke-width: 4;
}

.gauge-container > .gauge > .value {
  
  stroke-width: 8;
  color:grey;
  stroke: rgba(255, 0, 43, 0.959);
}

.gauge-container > .gauge > .value-text {
  fill: rgb(47, 227, 255);
  font-family: sans, 'sans-serif';
  font-weight: bold;
  font-size: 0.6em;
}
</style>
<style>
  /* Container Style */
  .container {
  margin: 0 auto;
  max-width: 1280px;
  width: 98%;
  border-radius: 10px;
}
@media only screen and (min-width: 601px) {
  .container {
    width: 98%;
    border-radius: 10px;
  }
}
@media only screen and (min-width: 993px) {
  .container {
    width: 98%;
    border-radius: 10px;
  }
}
</style>

<style>
  /* Temperature */
  * {
      box-sizing: border-box;
   }


   .containertemp {
      width: 100%; 
      background-color: #334455;
      border-radius: 20px;
   }

   .temperature {
      width: <?php echo $temperature * 2; ?>%; 
      text-align: center;
      font-weight: bolder;
      padding-top: 3px;
      padding-bottom: 3px;
      color: grey;
      border-radius: 20px;
      background-color:rgba(255, 0, 43, 0.959);
   }
</style>

<style>
  /* Container */
  .vertical-center {
  margin: 0;
  position: absolute;
  top: 50%;
  -ms-transform: translateY(-50%);
  transform: translateY(-48%);
}
</style>
<style>
/* Navbar ID search */
  input[type=text1] {
  width: 100%;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  background-color: white;
  background-image: url('/assamhack/img/target.png');
  background-position: 10px 10px; 
  background-repeat: no-repeat;
  padding: 12px 20px 12px 40px;
}
</style>
<style>
/* chart-Altitude */
  * {
  box-sizing: border-box;
  }

  
  .column {
  float: left;
  width: 50%;
  padding: 10px;

  }

  /* Clear floats after the columns */
  .row:after {
  content: "";
  display: table;
  clear: both;
  }
</style> 
<style>
/* Map 1 */

#map1 {
height: 90%;
}


.controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

.controls2 {
  margin-top: 50px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);

}

#origin-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 12px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 125px;
}
#destination-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 10px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 125px;


}

#origin-input:focus,
#destination-input:focus {
  border-color: #4d90fe;
}

#mode-selector {
  color: #fff;
  background-color: #d9534f;
  margin-left: 10px;
  padding: 15px 0 0 0;
  width: auto;
  height: 100px;

}

#mode-selector label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;



}
</style>

<!--=========================
            Scripts
===========================-->
<script> 
 /* Speedometer */
var myGauge = Gauge(
    document.getElementById("gauge"),
    {
      min: 0,
      max: 140,
      dialStartAngle: 180,
      dialEndAngle: 0,
      value: <?php echo $speed; ?>,
      viewBox: "0 0 100 57",
      gaugeColor: "Blue",
    }
  );
</script>

<script>
/* Map 1 */

function initMap() {
  const map1 = new google.maps.Map(document.getElementById("map1"), {
    mapTypeControl: false,
    center: { lat: -33.8688, lng: 151.2195 },
    zoom: 13
  });
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 4,
    center: {
      lat: -33,
      lng: 151,
    },
  });
  const image =
    "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";
  const beachMarker = new google.maps.Marker({
    position: {
      lat: -33.89,
      lng: 151.274,
    },
    map,
    icon: image,
  });
  console.log("Map2");
  new AutocompleteDirectionsHandler(map1);
}

class AutocompleteDirectionsHandler {
  constructor(map1) {
    this.map1 = map1;
    this.originPlaceId = "";
    this.destinationPlaceId = "";
    this.travelMode = google.maps.TravelMode.WALKING;
    this.directionsService = new google.maps.DirectionsService();
    this.directionsRenderer = new google.maps.DirectionsRenderer();
    this.directionsRenderer.setMap(map1);
    const originInput = document.getElementById("origin-input");
    const destinationInput = document.getElementById("destination-input");
    const modeSelector = document.getElementById("mode-selector");
    const originAutocomplete = new google.maps.places.Autocomplete(originInput);
    // Specify just the place data fields that you need.
    originAutocomplete.setFields(["place_id"]);
    const destinationAutocomplete = new google.maps.places.Autocomplete(
      destinationInput
    );
    // Specify just the place data fields that you need.
    destinationAutocomplete.setFields(["place_id"]);
    this.setupClickListener(
      "changemode-walking",
      google.maps.TravelMode.WALKING
    );
    this.setupClickListener(
      "changemode-transit",
      google.maps.TravelMode.TRANSIT
    );
    this.setupClickListener(
      "changemode-driving",
      google.maps.TravelMode.DRIVING
    );
    this.setupPlaceChangedListener(originAutocomplete, "ORIG");
    this.setupPlaceChangedListener(destinationAutocomplete, "DEST");
    this.map1.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
    this.map1.controls[google.maps.ControlPosition.TOP_LEFT].push(
      destinationInput
    );
    this.map1.controls[google.maps.ControlPosition.TOP_LEFT].push(modeSelector);
  }
  // Sets a listener on a radio button to change the filter type on Places
  // Autocomplete.
  setupClickListener(id, mode) {
    const radioButton = document.getElementById(id);
    radioButton.addEventListener("click", () => {
      this.travelMode = mode;
      this.route();
    });
  }
  setupPlaceChangedListener(autocomplete, mode) {
    autocomplete.bindTo("bounds", this.map1);
    autocomplete.addListener("place_changed", () => {
      const place = autocomplete.getPlace();

      if (!place.place_id) {
        window.alert("Please select an option from the dropdown list.");
        return;
      }

      if (mode === "ORIG") {
        this.originPlaceId = place.place_id;
      } else {
        this.destinationPlaceId = place.place_id;
      }
      this.route();
    });
  }
  route() {
    if (!this.originPlaceId || !this.destinationPlaceId) {
      return;
    }
    const me = this;
    this.directionsService.route(
      {
        origin: { placeId: this.originPlaceId },
        destination: { placeId: this.destinationPlaceId },
        travelMode: this.travelMode
      },
      (response, status) => {
        if (status === "OK") {
          me.directionsRenderer.setDirections(response);
        } else {
          window.alert("Directions request failed due to " + status);
        }
      }
    );
  }
}
</script>
<script>
class Stopwatch {
    constructor(display, results) {
        this.running = false;
        this.display = display;
        this.results = results;
        this.laps = [];
        this.reset();
        this.print(this.times);
    }
    
    reset() {
        this.times = [ 0, 0, 0 ];
    }
    
    start() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
    }
    
    lap() {
        let times = this.times;
        let li = document.createElement('li');
        li.innerText = this.format(times);
        this.results.appendChild(li);
    }
    
    stop() {
        this.running = false;
        this.time = null;
    }

    restart() {
        if (!this.time) this.time = performance.now();
        if (!this.running) {
            this.running = true;
            requestAnimationFrame(this.step.bind(this));
        }
        this.reset();
    }
    
    clear() {
        clearChildren(this.results);
    }
    
    step(timestamp) {
        if (!this.running) return;
        this.calculate(timestamp);
        this.time = timestamp;
        this.print();
        requestAnimationFrame(this.step.bind(this));
    }
    
    calculate(timestamp) {
        var diff = timestamp - this.time;
        // Hundredths of a second are 100 ms
        this.times[2] += diff / 10;
        // Seconds are 100 hundredths of a second
        if (this.times[2] >= 100) {
            this.times[1] += 1;
            this.times[2] -= 100;
        }
        // Minutes are 60 seconds
        if (this.times[1] >= 60) {
            this.times[0] += 1;
            this.times[1] -= 60;
        }
    }
    
    print() {
        this.display.innerText = this.format(this.times);
    }
    
    format(times) {
        return `\
${pad0(times[0], 2)}:\
${pad0(times[1], 2)}:\
${pad0(Math.floor(times[2]), 2)}`;
    }
}

function pad0(value, count) {
    var result = value.toString();
    for (; result.length < count; --count)
        result = '0' + result;
    return result;
}

function clearChildren(node) {
    while (node.lastChild)
        node.removeChild(node.lastChild);
}

let stopwatch = new Stopwatch(
    document.querySelector('.stopwatch'),
    document.querySelector('.results'));
</script>

<script>
  /* Altitude */

  var ctx = document.getElementById('myChart1').getContext('2d');;
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [<?php echo $time; ?>],
      datasets: 
      [{
        label: 'Altitude(in meter)',
        data: [<?php echo $altitude; ?>],
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor:'rgba(255, 99, 132, 1)',
        borderWidth: 3
      }]
    },


    options: {scales: {scales:{
      yAxes: [{display: true, ticks: {
          min: 0,
          max: 10,
          stepSize: 2
      }}], 
      xAxes: [{display:true}]
      }
      },
      tooltips:{mode: 'index'},
      legend:{display: true, position: 'top', labels: {fontColor: 'rgb(0,0,0)', fontSize: 16}}
    }
  });
</script>
</html>

