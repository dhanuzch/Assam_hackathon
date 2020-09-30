<?php  
$servername = "127.0.0.1"; 
$username = "root"; 
$password = ""; 
$dbname = "assam_police_hackathon"; 

$conn = new mysqli($servername, $username, $password, $dbname); 

if ($conn->connect_error) { 
  die("Connection failed: " . $conn->connect_error); 
} 

$query = "SELECT * FROM 2_entryexit_with_details
ORDER BY id DESC 
LIMIT 0,10"; 
$stolenquery = "SELECT * FROM 2_entryexit_stolen
ORDER BY id DESC 
LIMIT 0,10"; 

$countquery = "SELECT COUNT(*) FROM 2_entry_exit_control"; 


$result=mysqli_query($conn, $query);
$resultstolen=mysqli_query($conn, $stolenquery);
$resultcount=$conn->query ($countquery);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Team Parko</title>
</head>

<body class="w3-light-grey">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    
<style>
body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey"></body>
<div class="w3-content" style="max-width:1400px">

<!-- Header -->

<nav class="navbar navbar-light bg-white">
    <a class="navbar-brand" href="https://www.instagram.com/dhanuzch/" target="_blank">
        <img src="\Website\img\logo.png" width="50" height="50" class="d-inline-block align-top" alt="">
        <span class="navbar-text text-danger .display-3">
            <b>For Assam Police by Team-Parko</b>
        </span>
        </a> 
    </nav>


<!-- Grid -->
<div class="w3-row">

<!-- Blog entries -->
<div class="w3-col l8 s12">
  <!-- Blog entry -->
  <div class="w3-card-4 w3-margin w3-white">
    
    <div class="w3-container">
      <div class="box1">
        <img src="\2_Website\img\bg.jpg" alt="Road" style="width:100%">
        <?php 
        while($row=mysqli_fetch_assoc($resultcount))
        {
        $current_count = $row['current_count'];
        $entry_count = $row['entry_count'];

        
        ?>
        <div class="text1">
        <h5><?php echo $entry_count ?></h5>
        </div>
        <div class="text2">
        <h5><?php echo $current_count ?></h5>
        <?php 
        }
        ?>

      </div>
      </div>
    </div>
  </div>
  <hr>
</div>

<!-- vehicle db -->
<div class="w3-col l4">
  <!-- About Car -->
  <div class="w3-card w3-margin w3-margin-top">

    <div class="w3-container w3-white">
      <h4><b>Details of last 10 cars that entered</b></h4>
      <table class="w3-table-all w3-hoverable">
        <tr>
          <th>Reg. No.</th>
          <th>Car</th>
          <th>Colour</th>
        </tr>
        <?php 
                                    
        while($row=mysqli_fetch_assoc($result))
        {
            $registration_no = $row['registration_no'];
            $brand = $row['brand'];
            $model = $row['model'];
            $Colour = $row['Colour'];
            ?>
        <tr> 
        <td><?php echo $registration_no ?></td>
        <td><?php echo $brand ?> <?php echo $model ?></td>
        <td><?php echo $Colour ?></td>
        </tr>  

        <?php 
        }  
        ?> 
 
      </table>
    </div>
  </div><hr>

  <div class="w3-card w3-margin">
    <div class="w3-container w3-white w3-padding">
      <h4><b>Details of prohibited vehicles(if any)</b></h4>
      <table class="w3-table-all w3-hoverable">
        <tr>
          <th>Reg. No.</th>
          <th>Car</th>
          <th>Colour</th>
        </tr>
        <?php 
                                    
        while($row=mysqli_fetch_assoc($resultstolen))
        {
            $registration_no = $row['registration_no'];
            $brand = $row['brand'];
            $model = $row['model'];
            $Colour = $row['Colour'];
            ?>
        <tr> 
        <td><?php echo $registration_no ?></td>
        <td><?php echo $brand ?> <?php echo $model ?></td>
        <td><?php echo $Colour ?></td>
        </tr>  

        <?php 
        }  
        ?> 
 
      </table>
    </div>
    
<!-- END vehicle db -->
</div>

<!-- END GRID -->
</div><br>

<!-- END w3-content -->
</div>



</body>
</html>

<!--=========================
            Styles
===========================-->

<style>
    .box1{
        position: relative;
        display: inline-block; /* Make the width of box same as image */
    }

    .box1 .text2{
        position: absolute;
        z-index: 999;
        margin: 0;
        left: 40%;
        right: 0;        
        top: 50%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: #000000;
        width: 60%; /* Set the width of the positioned div */
    }
    .box1 .text1{
        position: absolute;
        z-index: 999;
        margin: 0;
        left: 5%;
        right: 0;        
        top: 50%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: #000000;
        width: 60%; /* Set the width of the positioned div */
    }
    
    
</style>