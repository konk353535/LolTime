<?php


date_default_timezone_set(date_default_timezone_get());
$Offset_Utc_Seconds =  date('Z');

echo $Offset_Utc_Seconds;

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


$Get_All_Users_Sql = "SELECT ID, Summoner_ID From UserNames WHERE Summoner_ID != 0 AND Reset_Match_History=False LIMIT 10";
$User_ID_Results = $Conn_Info->query($Get_All_Users_Sql);

// Make sure that there are users in our database
if ($User_ID_Results->num_rows > 0) {
    
    // output data of each user
    while($User_IDs = $User_ID_Results->fetch_assoc()) {

    	$Summoner_ID = $User_IDs["Summoner_ID"];


    	// Update, UserNames so that we know we've updated this persons games
    	$Updated_User_Games_Sql = "UPDATE UserNames SET Reset_Match_History = True WHERE Summoner_ID = {$Summoner_ID}";

		if ($Conn_Info->query($Updated_User_Games_Sql) === TRUE) {
			echo "";
		} 
		else {
		    echo "Error: {$Updated_User_Games_Sql}<br>" . $Conn_Info->error;
		}


    	// API reqest, for SUMMONER ID, return LAST 10 Games
    	$Game_Data_Request = file_get_contents("https://oce.api.pvp.net/api/lol/oce/v1.3/game/by-summoner/{$Summoner_ID}/recent?api_key=e3d79a6c-bc93-450b-b5ee-ca2022044f46");
    	$Game_Data_Results = json_decode($Game_Data_Request, true);
    	

    	foreach($Game_Data_Results as $key => $api_row){
    		//echo $key . ":" . $api_row;

    		if($key == "games"){
    			//echo "Games";
    			$api_games = $api_row;

    			foreach($api_games as $single_game_key => $Single_Game){
    				// Will execute from here for each game

    				$new_game = False; // Will default that this a new game to false, if we find it is a new game, will set to True
    				
    				$win;
    				$timePlayed;

    				$gameId = $Single_Game["gameId"];
    				$gameType = $Single_Game["subType"];
    				// Epoch To Date
    				// Will return seconds from UTC Time
    				$epoch = (int)($Single_Game["createDate"]/1000) + (int)($Offset_Utc_Seconds);
    				$dt = new DateTime("@$epoch");
    				$Game_Date = $dt->format('Y-m-d H:i:s');
    				
    				$champion = $Single_Game["championId"];

    				$Stats = $Single_Game["stats"];
    				$win = $Stats["win"];
    				$timePlayed = $Stats["timePlayed"];
    				
    				echo $User_IDs["ID"]. "<br />";
    				echo $gameId ."<br />";

    				$Sql_Game_Check = "SELECT * FROM games_database WHERE UserNames_ID = '" . $User_IDs['ID'] ."' and Match_ID= '".$gameId."'";
    				$Result_Game_Check = $Conn_Info->query($Sql_Game_Check);

    				
					if ($Result_Game_Check->num_rows == 0) {
						
						$new_game = True;
						$insert_game = "INSERT INTO games_database (Match_ID, my_Date, timePlayed, champion, win, Summoner_ID,UserNames_ID, Game_Type) 
						VALUES ('" . $gameId ."', '". $Game_Date ."', '". $timePlayed ."','" . $champion ."', '". $win ."', '{$Summoner_ID}', '". $User_IDs["ID"] ."', '" . $gameType ."')";

						if ($Conn_Info->query($insert_game) === TRUE) {
						    echo "New record created successfully";
						} else {
						    echo "Error: " . $insert_game . "<br>" . $Conn_Info->error;
						}
						
					}
					else {
						// Invalid game, we've already seen it before
						echo "Seen Before";
					}
    			}
    		}
    	}
    }
} 
else {
    echo "0 results";
}


$Conn_Info->close();





?>
