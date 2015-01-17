<?php

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

// Input information for the new summoner
$Group_Name = $_GET["Group"];
$Input_Password = $_GET["Password"];
$Input_Summoner_Name = $_GET["Summoner_Name"];
$Input_Name = $_GET["Name"];


// Check that a name was entered
if($Input_Name == "" or $Input_Name === null){
	echo "No name Entered.";
	die();
}


// -- TO DO --
// Need to re-evaluate code, to accept summoner into group, even if they are already in the database, but not in the specified group

// Check the password is correct
if($Input_Password == "Lollipoppy"){
	// Check the summoner name is valid
	$API_Request_Name = file_get_contents("https://oce.api.pvp.net/api/lol/oce/v1.4/summoner/by-name/{$Input_Summoner_Name}?api_key=e3d79a6c-bc93-450b-b5ee-ca2022044f46");
	$Request_Data = json_decode($API_Request_Name, true);


	if(empty($Request_Data)){
		echo " Invalid Summoner Name. ";
	}
	else {

		// Key is in lowercase
		$Input_Summoner_Name = strtolower($Input_Summoner_Name);

		// Retrieve summoner ID, from the JSON
		$Summoner_ID = $Request_Data[$Input_Summoner_Name];
		$Summoner_ID = $Summoner_ID["id"];


		// Check if this is a New or Existing User
		$Sql_New_User_Check = "SELECT * FROM UserNames WHERE Summoner_ID = '{$Summoner_ID}'";
		$Result_New_User_Check = $Conn_Info->query($Sql_New_User_Check);


		// New User
		if ($Result_New_User_Check->num_rows == 0) {

			// Insert our new USER
			$Insert_User = "INSERT INTO UserNames (Summoner_ID, Summoner_Name, Name) 
				VALUES ('{$Summoner_ID}', '{$Input_Summoner_Name}', '{$Input_Name}')";

			// If Insert worked
			if ($Conn_Info->query($Insert_User) === TRUE) {
			    echo "New User Added. ";
			} 
			else 
			{
			    echo "Error: {$Insert_User}<br>" . $Conn_Info->error;
			}
		}

		// Load User into specified group
		$Insert_User_Into_Group = "INSERT INTO UserNames_GameGroup (GameGroup_ID, UserNames_ID) 
			VALUES ((SELECT Gamer_group_ID FROM GameGroup WHERE Group_Name = '{$Group_Name}'), (SELECT ID FROM UserNames WHERE Summoner_ID = '{$Summoner_ID}'))";

		if ($Conn_Info->query($Insert_User_Into_Group) === TRUE) {
		    echo " Added to {$Group_Name} ";
		} else {
		    echo "Error: {$Insert_User_Into_Group}<br>" . $Conn_Info->error;
		}

	}

}
else {
	echo "Invalid Password - Whats my favourite skin?";
}






?>