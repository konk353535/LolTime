


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="favicon.ico">
    <title>LolTime - Overivew</title>

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Bootstrap core CSS -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	  <!-- Bootstrap style css -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<!-- Custom styling for page -->
		<link href="custom_styling.css" rel="stylesheet">
		<!-- Jumbotron css -->
		<link href="jumbotron.css" rel="stylesheet">
		<!-- HighCharts JS -->
		<script src="../js/highcharts.js"></script>
		<script src="../js/modules/exporting.js"></script>
		<script src="../js/themes/sand-signika.js"></script>
   
  </head>

  <body>
	  <nav class="navbar navbar-default" role="navigation">
	    <div class="container">
	      <!-- Brand and toggle get grouped for better mobile display -->
	      <div class="navbar-header">
	        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	          <span class="sr-only">Toggle navigation</span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	          <span class="icon-bar"></span>
	        </button>
	        <a class="navbar-brand" href="#">LolTime</a>
	      </div>

	      <!-- Collect the nav links -->
	      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	        <ul class="nav navbar-nav">
	          <li class="active"><a href="#">Overview</a></li>
	          <li><a id="Group_Link">Group</a></li>
	          Ahhh HA!
	          This is another addition, mwawhawhahwhahwha
	        </ul>
	   		
	   		<!-- Right Nav bar 
			    <ul class="nav navbar-nav navbar-right">
	        <li class="dropdown">
	          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Jason Romaior <span class="caret"></span></a>
	          	<ul class="dropdown-menu" role="menu">
		            <li><a href="#">Profile</a></li>
		            <li class="divider"></li>
		            <li><a href="#">Logout</a></li>
	          	</ul>
	      	</li>
	    	</ul>
	    	-->
	      </div><!-- /.navbar-collapse -->
	    </div><!-- /.container-fluid -->
		</nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron" >
      <div class="container">
		    <form class="navbar-form navbar-right" role="search">
				  <div class="form-group">
				    <input type="text" class="form-control" id="GroupNameTextBox" placeholder="Ninjas">
				    <input type="text" class="form-control" id="TimeTextBox" placeholder="7_D">
				  </div>
				  <button type="button" class="btn btn-default" onmousedown="update_page()">Graph</button>
				</form>
		    <!-- Output from JS Func Update_Graph (data pulled From get_group_game_time.php) -->
				<div id="graph_container" style="height: 450px"></div>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-6">
		      <h2 id="Group_Heading">Group: Ninjas</h2>
		      <div id="Group_Names_Table"></div>
        </div>
        <div class="col-md-6">
          <h2>Random Stats</h2>
          <div id="Group_Random_Table"></div>
				</div>
      </div>
      <hr>
      <footer>
        <p>&copy; Romaior Co 2014s</p>
      </footer>
    </div> <!-- /container -->

	<!-- Lots of Javascript!!!!! and xml request for data for graph -->
	<script text="text/javascript">

	function Update_Group_Links(){
		var Group_Link = document.getElementById("Group_Link");
		Group_Link.href = "viewgroup.php?GroupName=" + groupName
	}

	function Update_Group_Name(){
		groupName = document.getElementById('GroupNameTextBox').value;

		if(groupName == ""){

			// If no name in text, check for link name (name is in the url)
			getGroupName = <?php echo json_encode($_GET['GroupName']); ?>;

			if(getGroupName == "" || getGroupName == null){

				// If nothing default to ninjas
				groupName = "Ninjas";
			}
			else {
				groupName = getGroupName;
			}

		}
	}
	function getTimeOption(){
		timeOption = document.getElementById('TimeTextBox').value;

		if(timeOption == ""){
			timeOption = "7_D";
		}
	}

	function update_page(){
		// Update Group Name();
		Update_Group_Name();
		getTimeOption();

		// Update Group Links
		Update_Group_Links()

		// Alter the graph
		graph();

		// Load into the table members of the group
		update_group_members_ajax();
		update_group_random_ajax()
	}

	function update_group_random_ajax(){
		// Retrieves data about members in the group using ajax
		var xmlhttp = new XMLHttpRequest();
		var url = "random_member_stats.php?GroupName=" + groupName;

		xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		        update_group_random(xmlhttp.responseText);
		    }
		}

		xmlhttp.open("GET", url, true);
		xmlhttp.send();
	}

	function update_group_random(group_member_data){
		// Update Table with names of members in the group
		document.getElementById('Group_Random_Table').innerHTML = group_member_data;
	}

	function update_group_members_ajax(){
		// Retrieves data about members in the group using ajax
		var xmlhttp = new XMLHttpRequest();
		var url = "get_group_members.php?GroupName=" + groupName;

		xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		        update_group_members(xmlhttp.responseText);
		    }
		}

		xmlhttp.open("GET", url, true);
		xmlhttp.send();
	}

	function update_group_members(group_member_data){
		// Update Table with names of members in the group
		document.getElementById('Group_Names_Table').innerHTML = group_member_data;
	}

	function graph(){
		

		var xmlhttp = new XMLHttpRequest();
		var url = "get_group_game_time.php?GroupName=" + groupName + "&timeOption=" + timeOption;

		xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		        update_graph(xmlhttp.responseText);
		    }
		}
		xmlhttp.open("GET", url, true);
		xmlhttp.send();

		labelsRaw = [];
		dataRaw = [];
	}
	function Get_Time_Options(){
		var Time_Parts = timeOption.split("_");
		
		if(Time_Parts[1] == "H" || Time_Parts == "h"){
			var dayorhours = " Hours";
			var Time_Multiplier = Time_Parts[0];
		}
		else if(Time_Parts[1] == "D" || Time_Parts[1] == "d"){
			var dayorhours = " Days";
			var Time_Multiplier = Time_Parts[0];
		}

		return Time_Multiplier + dayorhours;

	}
	update_page();


	function update_graph(graph_data) {
	    var arr = JSON.parse(graph_data);
	    var i;

	    for(i = 0; i < arr.length; i++) {
	        labelsRaw.push(arr[i].Name);
	        dataRaw.push(parseInt(arr[i].Minutes));
	    }

	    $(function () {
		    $('#graph_container').highcharts({
		        chart: {
		            type: 'column',
		            height: 400
		        },
		        title: {
		            text: 'Group: ' + groupName
		        },
		        subtitle: {

		            text: 'Time Played last ' + Get_Time_Options()
		        },
		        xAxis: {
		            categories: labelsRaw,
		        },
		        yAxis: {
		            min: 0,
		            title: {
		                text: 'Lol played (Minutes)'
		            }
		        },
		        tooltip: {
		            headerFormat: '<a href="bla.php"><span style="font-size:10px">{point.key}</span><table>',
		            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
		                '<td style="padding:0"><b>{point.y:.0f} Minutes</b></td></tr>',
		            footerFormat: '</table></a>',
		            shared: true,
		            useHTML: true
		        },
		        plotOptions: {
		            column: {
		                pointPadding: 0.0,
		                borderWidth: 0,
		                color: '#5190BD'
		            }
		        },
		        series: [{
	            name: 'League',
	            data: dataRaw
	        }]
		    });
		});
		}

	
	

	</script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
