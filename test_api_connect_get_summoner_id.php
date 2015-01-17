<?php

$servername = "localhost";
$username = "crewcutc_organcl";
$password = "organclean12";
$db_name = "crewcutc_lolTimePlayed";



// Create connection
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


$sql = "SELECT Summoner_Name From UserNames WHERE Summoner_ID = 0";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	
    	echo $row["Summoner_Name"];
    }
} else {
    echo "0 results";
}


$conn->close();

$response = file_get_contents('https://oce.api.pvp.net/api/lol/oce/v1.4/summoner/by-name/NinjaXWatermelon?api_key=e3d79a6c-bc93-450b-b5ee-ca2022044f46');
$results = json_decode($response, true);

foreach($results as $rows){
	echo $rows["id"];
}




?>
