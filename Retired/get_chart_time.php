


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Jumbotron Template for Bootstrap</title>

    <!-- Jquery -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Bootstrap core CSS -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- Bootstrap style css -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- Jumbotron css -->
	<link href="../jumbotron.css" rel="stylesheet">
	
	<script src="../js/highcharts.js"></script>
	<script src="../js/modules/exporting.js"></script>
	<script src="../js/themes/sand-signika.js"></script>
   

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
    
	</script>
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
              <li class="active"><a href="#">Time Group</a></li>
              <li><a href="#">Groups Dropdown</a></li>
              <li><a href="#">Reports</a></li>
            </ul>
       		
   		    <ul class="nav navbar-nav navbar-right">
		        <li class="dropdown">
		          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Jason Romaior <span class="caret"></span></a>
		          	<ul class="dropdown-menu" role="menu">
			            <li><a href="#">Profile</a></li>
			            <li><a href="#">Change Units</a></li>
			            <li><a href="#">Change Organisation</a></li>
			            <li class="divider"></li>
			            <li><a href="#">Logout</a></li>
		          	</ul>
	        	</li>
	      </ul>
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
		  <button type="button" class="btn btn-default" onmousedown="loadGraph()">Graph</button>
		</form>
      <!-- Output information from php my admin, as a graph in the jumbotron -->
  
			<div id="graph_container"></div>

      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>
      <hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>
    </div> <!-- /container -->

    <!-- Javascript and xml request for data for graph -->
	<script text="text/javascript">
	function graph(){

		var groupName = document.getElementById('GroupNameTextBox').value;
		var timeOption = document.getElementById('TimeTextBox').value;
		// Checks if there is no name in the text box
		if(groupName == ""){
			// If no name in text, check for link name (name is in the url)
			$getGroupName = <?php echo json_encode($_GET['GroupName']); ?>;
			if($getGroupName == ""){
				// If nothing default to ninjas
				groupName = "Ninjas";
			}
			else {
				groupName = $getGroupName;
			}

		}
		if(timeOption == ""){
			timeOption = "7_D";
		}

		var xmlhttp = new XMLHttpRequest();
		var url = "display_played_times.php?GroupName=" + groupName + "&timeOption=" + timeOption;

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

	graph();


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
		            height: 500
		        },
		        title: {
		            text: 'Group: ' + <?php echo json_encode($_GET['GroupName']); ?>
		        },
		        subtitle: {
		            text: 'Time Played'
		        },
		        xAxis: {
		            categories: labelsRaw
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


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
