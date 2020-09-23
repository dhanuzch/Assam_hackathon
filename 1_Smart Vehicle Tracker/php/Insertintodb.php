<?php
class svehicletracker{
 public $link='';
 function __construct($lat, $longitude, $speed, $time, $altitude, $temperature, $direction){
  $this->connect();
  $this->storeInDB($lat, $longitude, $speed, $time, $altitude, $temperature, $direction);
 }
 
 function connect(){
  $this->link = mysqli_connect('localhost','root','') or die('Cannot connect to the DB');
  mysqli_select_db($this->link,'assam_police_hackathon') or die('Cannot select the DB');
 }
 
 function storeInDB($lat, $longitude, $speed, $time, $altitude, $temperature, $direction){
  $query = "insert into 1_smart_vehicle_tracker set humidity='".$humidity."', temperature='".$temperature."'";
  $result = mysqli_query($this->link,$query) or die('Errant query:  '.$query);
 }
 
}
if($_GET['lat'] != '' and  $_GET['longitude'] != '' and  $_GET['speed'] != '' and  $_GET['time'] != '' and  $_GET['altitude'] != '' and  $_GET['temperature'] != '' and  $_GET['direction'] != ''){
 $svehicletracker=new svehicletracker($_GET['lat'],$_GET['longitude'],$_GET['speed'],$_GET['time'],$_GET['altitude'],$_GET['temperature'],$_GET['direction']);
}


?>
