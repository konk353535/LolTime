
<?php


$server_name = "localhost";
$server_username = "crewcutc_organcl";
$server_password = "organclean12";
$database_name = "crewcutc_lolTimePlayed";

// Create connection_infoection
$connection_info = new mysqli($server_name, $server_username, $server_password, $database_name);

// Check connection_infoection
if ($connection_info->connect_error) {
    die("connection_infoection failed: " . $connection_info->connect_error);
} 

$Group_Name = $_GET['GroupName'];
$time_format = $_GET['timeOption'];


$time_format_piece = explode("_", $time_format);

if($time_format_piece[1] == "D"){
    $time_number = (int)($time_format_piece[0]);
    $week_from = date('Y-m-d H:i:s', strtotime("-" . $time_number ." days"));
}
else if($time_format_piece[1] == "H"){
    $time_number = (int)($time_format_piece[0]);
    $week_from = date('Y-m-d H:i:s', time() - 3600 * $time_number);
}

$today = date('Y-m-d H:i:s');


$sql = "SELECT Summoner_ID, COUNT(*) as totalGames From games_database WHERE UserNames_ID IN 
(Select ID FROM UserNames WHERE ID IN 
    (SELECT UserNames_ID FROM UserNames_GameGroup WHERE GameGroup_ID=
        (SELECT Gamer_group_ID FROM GameGroup WHERE Group_Name = '" . $Group_Name ."')
    )
) and my_Date between '".$week_from."' AND '".$today."' group by Summoner_ID ORDER by totalGames DESC";


$result = $connection_info->query($sql);

$outp = "[";

if ($result->num_rows > 0) {
    
    while($row = $result->fetch_assoc()) {
        if($outp != "["){$outp .= ",";}

        // Get Name from ID
        $sql_name = "Select Name FROM UserNames WHERE Summoner_ID = '" . $row["Summoner_ID"] ."'";
        $results_name = $connection_info->query($sql_name);

        if($results_name->num_rows > 0){
                $actual_results = $results_name->fetch_assoc();
                $name = $actual_results["Name"];
        }
        else {
            echo "no names found with those IDs";
        }
        $outp .= '{"Name":"' . $name .  '", "Games":"' . (int)($row["totalGames"]) . '"}';



    }
} else {
    echo "0 results";
}

$outp .= "]";

$connection_info->close();

echo($outp);

?>




