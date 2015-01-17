
<?php

// List of champion IDS -> Names
$Champion_List = array(266 => "Aatrox", 103=>"Ahri", 84 => "Akali", 12 => "Alistar", 32 => "Amumu", 34 => "Anivia", 1 => "Annie", 22 => "Ashe", 53 => "Blitzcrank", 63 => "Brand", 51 => "Caitlyn", 69 => "Cassiopeia", 31 => "Cho'Gath", 42 => "Corki", 122 => "Darius", 131 => "Diana", 36 => "Dr. Mundo", 119 => "Draven", 60 => "Elise", 28 => "Evelyn", 81 => "Ezreal", 9 => "Fiddlesticks", 114 => "Fiora", 105 => "Fizz", 3 => "Galio", 41 => "Gangplank", 86 => "Garen", 150 => "Gnar", 79 => "Gragas", 104 => "Graves", 120 => "Hecraim", 74 => "Heimerdinger", 39 => "Irelia", 40 => "Janna", 59 => "Jarvan IV", 24 => "Jax", 126 => "Jayce", 222 => "Jinx", 429 => "Kalisa", 43 => "Karma", 30 => "Karthus", 38 => "Kassadin", 55 => "Katarina", 10 => "Kayle", 85 => "Kennen", 121 => "Kha'Zix", 96 => "Kog'Maw", 7 => "LeBlanc", 64 => "Lee Sin", 89 => "Leona", 127 => "Lissandra", 236 => "Lucian", 117 => "Lulu", 99 => "Lux", 54 => "Malphite", 90 => "Malzahar", 57 => "Maokai", 11 => "Master Yi", 21 => "Miss Fortune", 82 => "Mordekasier", 25 => "Morgana", 267 => "Nami", 75 => "Nasus",111 => "Nautilus",  76 => "Nidalee", 56 => "Nocturne", 20 => "Nunu", 2 => "Olaf", 61 => "Orianna", 80 => "Pantheon", 78 => "Poppy", 33 => "Rammus", 421 => "Rek'Sai", 58 => "Renekton", 107 => "Rengar", 92 => "Riven", 68 => "Rumble", 13 => "Ryze", 113 => "Sequani", 35 => "Shaco", 98 => "Shen", 102 => "Shyvana", 27 => "Singed", 14 => "Sion", 15 => "Sivir", 72 => "Skarner", 37 => "Sona", 16 => "Soraka", 50 => "Swain", 91 => "Talon", 44 => "Taric", 17 => "Teemo", 412 => "Thresh", 18 => "Tristana", 48 => "Trundle", 23 => "Tryndamere", 4 => "Twisted Fate", 29 => "Twitch", 77 => "Udyr", 6 => "Urgot",110 => "Varus", 67 => "Vayne", 45 => "Veigar", 161 => "Vel'Koz", 254 => "Vi",  112 => "Viktor", 8 => "Vladimir", 106 => "Volibear", 19 => "Warwick", 62 => "Wukong", 101 => "Xerath", 5 => "Xin Zhao", 83 => "Yorick", 154 => "Zac", 238 => "Zed", 115 => "Ziggs", 26 => "Zilean", 143 => "Zyra");

// Connection Information
$server_name = "localhost";
$server_username = "crewcutc_organcl";
$server_password = "organclean12";
$database_name = "crewcutc_lolTimePlayed";

// Create connectio
$connection_info = new mysqli($server_name, $server_username, $server_password, $database_name);

// Check connection
if ($connection_info->connect_error) {
    die("connection_infoection failed: " . $connection_info->connect_error);
} 

$Group_Name = $_GET['GroupName'];

// Initalize array to store all random stats
$Group_Members_Data;

// Gets all the User_ID's of member in Group_Name
$members_ids_sql = "SELECT ID FROM UserNames WHERE ID IN(
    Select UserNames_ID FROM UserNames_GameGroup WHERE GameGroup_ID=(
      Select Gamer_group_ID FROM GameGroup WHERE Group_Name= '". $Group_Name ."' ))";

// Gets AVG Min/Day
// Joins games and usernames to get Players Name
// Rounds Sum(timePlayed)/60 to get minutes played total
// / Total Mins by Days elapsed (max_date - min_date)
$avg_min_day_sql = "Select Name, ROUND((sum(timePlayed)/60)/datediff(max(my_Date), min(my_Date))) as AVG_Min_Day, UserNames_ID  
FROM games_database, UserNames 
WHERE games_database.UserNames_ID=UserNames.ID 
GROUP BY UserNames_ID 
HAVING UserNames_ID IN (". $members_ids_sql .")";

$member_avg_min_day_results = $connection_info->query($avg_min_day_sql);

if($member_avg_min_day_results-> num_rows > 0){
    while($member_avg_min_day = $member_avg_min_day_results->fetch_assoc()) {

        $User_ID = $member_avg_min_day["UserNames_ID"];
        $AVG_Min_Day = $member_avg_min_day["AVG_Min_Day"];
        $Name = $member_avg_min_day["Name"];

        $Group_Members_Data[$User_ID] = [$AVG_Min_Day, $Name];

    }
}
else {
    echo "No AVG MIN RESULTS returned. Something went wrong :(, random mem stats";
}


// Gets the max minutes played day for each player in the group
$max_min_day_sql = "SELECT MAX(Avg_Min_Day) as Max_Min_Day, UserNames_ID, day_Date FROM 
                      (SELECT UserNames_ID, AVG_Min_Day, day_Date FROM 
                        (SELECT UserNames_ID, ROUND((sum(timePlayed)/60)) as AVG_Min_Day, date(my_Date) as day_Date FROM games_database 
                          GROUP BY date(my_Date), UserNames_ID 
                          ORDER BY AVG_MIN_DAY DESC) 
                        AS MAIN_DATA) 
                      AS MINS_EACH_DAY 
                    GROUP BY UserNames_ID 
                    HAVING UserNames_ID IN (". $members_ids_sql .")";

$member_Max_Min_Day_Results = $connection_info->query($max_min_day_sql);

if($member_Max_Min_Day_Results-> num_rows > 0){

    while($member_Max_Min_Day = $member_Max_Min_Day_Results->fetch_assoc()) {
        $User_ID = $member_Max_Min_Day["UserNames_ID"];
        $Max_Min_Day = $member_Max_Min_Day["Max_Min_Day"];
        $Max_Day_Date = $member_Max_Min_Day["day_Date"];

        array_push($Group_Members_Data[$User_ID], $Max_Min_Day);
        array_push($Group_Members_Data[$User_ID], $Max_Day_Date);
    }
}
else {
    echo "Something went wrong :(, random mem stats";
}

// Gets the max minutes played day for each player in the group
$most_Played_Champ_Sql = "SELECT MAX(TotalTime) as MostPlayedChampTime, champion, UserNames_ID 
                            FROM (SELECT UserNames_ID, champion, ROUND(SUM(timePlayed)/60) as TotalTime 
                                FROM games_database 
                                    GROUP BY champion, UserNames_ID 
                                    ORDER BY TotalTime DESC) 
                                    AS Time_Played_Champion 
                            GROUP BY UserNames_ID 
                            HAVING UserNames_ID IN (". $members_ids_sql .")";

$member_Most_Played_Results = $connection_info->query($most_Played_Champ_Sql);

if($member_Most_Played_Results-> num_rows > 0){
    while($member_Most_Champ = $member_Most_Played_Results->fetch_assoc()) {
        $User_ID = $member_Most_Champ["UserNames_ID"];

        $Champion_Name = $Champion_List[$member_Most_Champ["champion"]];
        $Champion_Played_Time = $member_Most_Champ["MostPlayedChampTime"];

        array_push($Group_Members_Data[$User_ID ], $Champion_Played_Time);
        array_push($Group_Members_Data[$User_ID ], $Champion_Name);

    }
}
else {
    echo "Something went wrong :(, random mem stats";
}


?>
<table class="table table-striped table-hover table-bordered">
    <tr>
        <th>Name</th>
        <th>Min/Day (AVG)</th>
        <th>Min/Day (Max)</th>
        <th>Most Plyd Champ</th>
    </tr>
    <?php
    foreach($Group_Members_Data as $Member_Data) {
    ?>
    <tr>
        <td><?php echo $Member_Data[1]; ?> </td>
        <td><?php echo $Member_Data[0]; ?> </td>
        <td><?php echo $Member_Data[2]; ?> </td>
        <td><?php echo $Member_Data[4]; ?> - <?php echo $Member_Data[5]; ?></td>
    </tr>
    <?php
    }
    ?>
</table>

