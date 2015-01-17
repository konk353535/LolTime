
<?php


$Server_Location = "localhost";
$Server_User_Name = "crewcutc_organcl";
$Server_Password = "organclean12";
$Database_Name = "crewcutc_lolTimePlayed";

// Create connection
$Con_Info = new mysqli($Server_Location, $Server_User_Name, $Server_Password, $Database_Name);

// Check connection
if ($Con_Info->connect_error) {
    die("Connection failed: " . $Con_Info->connect_error);
} 

$Group_Name = $_GET['GroupName'];
$Time_Format = $_GET['timeOption'];


$Time_Format_Piece = explode("_", $Time_Format);

// Day Length
if($Time_Format_Piece[1] == "D"){
    $Time_Multiplier = (int)($Time_Format_Piece[0]);
    $Date_From = date('Y-m-d H:i:s', strtotime("-" . $Time_Multiplier ." days"));
}
// Hour Length
else if($Time_Format_Piece[1] == "H"){
    $Time_Multiplier = (int)($Time_Format_Piece[0]);
    $Date_From = date('Y-m-d H:i:s', time() - 3600 * $Time_Multiplier);
}

$Now = date('Y-m-d H:i:s');

// Gets all users in group X
// Gets all gameTime Between 2 Dates
$Player_Game_Time_Sql = "SELECT Name, UserNames.Summoner_ID, sum(timePlayed) as totalTime From games_database, UserNames
        WHERE UserNames_ID IN 
            (Select ID FROM UserNames WHERE ID IN 
                (SELECT UserNames_ID FROM UserNames_GameGroup WHERE GameGroup_ID
                    =(SELECT Gamer_group_ID FROM GameGroup WHERE Group_Name = '" . $Group_Name ."')
                )
            ) 
        AND my_Date between '".$Date_From."' AND '".$Now."' 
        AND UserNames.ID = games_database.UserNames_ID
        Group BY Summoner_ID";

$Player_Game_Time_Result = $Con_Info->query($Player_Game_Time_Sql);

$Graph_Time_Output = "[";

if ($Player_Game_Time_Result->num_rows > 0) {
    
    while($Game_Time = $Player_Game_Time_Result->fetch_assoc()) {

        if($Graph_Time_Output != "["){
            $Graph_Time_Output .= ",";
        }


        $Graph_Time_Output .= '{"Name":"' . $Game_Time["Name"] .  '", "Minutes":"' . (int)($Game_Time["totalTime"]/60) . '"}';



    }
} else {
    echo "0 results";
}

$Graph_Time_Output .= "]";

$Con_Info->close();

echo($Graph_Time_Output);

?>




