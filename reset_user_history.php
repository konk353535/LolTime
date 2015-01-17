<?php

// Every time this is run, it will update the Match_History_Checked to false for all user

// Example, Every 3 hours all users Match_History_Checked will be reset, 
// which will let get_user_history know that it needs to update there recent games




$Password = $_GET["Password"];
$CONSTANT_PASSWORD = "Penelope";


if($Password == "Penelope"){

$Server_Location = "localhost";
$Server_User_Name = "crewcutc_organcl";
$Server_Password = "organclean12";
$Database_Name = "crewcutc_lolTimePlayed";

// Create connection
$Conn_Info = new mysqli($Server_Location, $Server_User_Name, $Server_Password, $Database_Name);

// Check connection
if ($Conn_Info->connect_error) {
    die("Connection failed: " . $Conn_Info->connect_error);
} 


$Reset_Match_History_Checked_Sql = "UPDATE UserNames SET Reset_Match_History = False WHERE Summoner_ID != 0";

if ($Conn_Info->query($Reset_Match_History_Checked_Sql) === TRUE) {
	echo "Reset!";
} 
else {
    echo "Error: {$Reset_Match_History_Checked_Sql}<br>" . $Conn_Info->error;
}












}




?>