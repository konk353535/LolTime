
<?php


$server_name = "localhost";
$server_username = "crewcutc_organcl";
$server_password = "organclean12";
$database_name = "crewcutc_lolTimePlayed";

$Augment_Total_Time = False;

if($_GET["Augment"] == "Total_Time"){
	$Augment_Total_Time = True;
}

// Create connection_info
$connection_info = new mysqli($server_name, $server_username, $server_password, $database_name);

// Check connection_info
if ($connection_info->connect_error) {
    die("connection_infoection failed: " . $connection_info->connect_error);
} 

$Group_Name = $_GET['GroupName'];

$Member_Ids_Sql = "SELECT ID FROM UserNames WHERE ID IN(
    Select UserNames_ID FROM UserNames_GameGroup WHERE GameGroup_ID=(
      Select Gamer_group_ID FROM GameGroup WHERE Group_Name= '". $Group_Name ."' ))";




$Group_Members_Sql = "SELECT Name, Summoner_Name, ROUND(SUM(timePlayed)/60) as Total_Time 
						FROM UserNames, games_database 
						WHERE UserNames.ID = games_database.UserNames_ID AND 
						games_database.UserNames_ID IN (".$Member_Ids_Sql.") 
						GROUP BY UserNames.ID 
						ORDER BY Total_Time DESC";

$member_Name_Array = $connection_info->query($Group_Members_Sql);

$rank = 1;

if ($member_Name_Array->num_rows > 0) {
?>
<table class="table table-striped table-hover table-bordered">
	<tr>
		<?php if($Augment_Total_Time == True){ echo "<th>#</th>"; } ?>
		<th>Name</th>
		<th>Summoner Name</th>
		<?php if($Augment_Total_Time == True){ echo "<th>Total Time (Hours)</th>"; } ?>
	</tr>
	<?php
	while($member_names = $member_Name_Array->fetch_assoc()) {
	?>
		<tr>
			<?php 
			if($Augment_Total_Time == True){
				echo "<td>" . $rank . "</td>";
			}
			?>
			<td><?php echo $member_names["Name"]; ?> </td>
			<td><?php echo $member_names["Summoner_Name"]; ?> </td>
			<?php 
			if($Augment_Total_Time == True){
				echo "<td>" . round($member_names["Total_Time"]/60, 2) . "</td>";
			}
			?>
		</tr>
	<?php
	$rank += 1;
	}
	?>
</table>
<?php
}
else {
	echo "No Results. check group name (Default: Ninjas)";
}

?>
