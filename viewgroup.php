

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Jumbotron Template for Bootstrap</title>

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- Bootstrap core CSS -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- Bootstrap style css -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<!-- Jumbotron css -->
	<link href="custom_styling.css" rel="stylesheet">

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
              <li><a href="overview.php">Overview</a></li>
              <li class="active"><a href="#">Group</a></li>
            </ul>
       		
   		    <ul class="nav navbar-nav navbar-right">
		        <li class="dropdown">
		          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Jason <span class="caret"></span></a>
		          	<ul class="dropdown-menu" role="menu">
			            <li><a href="#">Profile</a></li>
			            <li class="divider"></li>
			            <li><a href="#">Logout</a></li>
		          	</ul>
	        	</li>
	      </ul>
          </div><!-- /.navbar-collapse -->

        </div><!-- /.container-fluid -->
         
      </nav>

	<div class="container">
		
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3>Group: Ninjas</h2>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
						<h3> Members</h3>
							<?php 
							
								$_GET["GroupName"] = $_GET["GroupName"];
								$_GET["Augment"] = "Total_Time";
								include("get_group_members.php");

							?>
						</div>
						<div class="col-md-4">
						<h3>New Member</h3>
							<div class="input-group input-group-sm">
								<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user"></i></span>
								<input type="text" class="form-control" placeholder="Name" aria-describedby="sizing-addon1" id="Create_Name">
							</div>

							<div class="input-group input-group-sm">
							   <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tower"></i></span>
							   <input type="text" class="form-control" placeholder="Summoner Name" aria-describedby="basic-addon1" id="Create_Summoner_Name">
							</div>

							<div class="input-group input-group-sm">
								<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
								<input type="text" class="form-control" placeholder="Password" aria-describedby="sizing-addon1" id="Create_Password">
							</div>		

									<button class="btn btn-primary btn-block" type="button" onclick="Submit_User()">Submit</button>

							<span class="label label-danger" id="Create_Status"></span>
						</div>
					</div>
				</div>
			</div>
		

			
		  <hr>

		  <footer>
		    <p>&copy; Company 2014</p>
		  </footer>

		</div> <!-- /container -->

	</div>
    <!-- Javascript and xml request for data for graph -->
	<script text="text/javascript">
	function Submit_User(){
		var Create_Name = document.getElementById('Create_Name').value;
		var Create_Summoner_Name = document.getElementById('Create_Summoner_Name').value;
		var Create_Password = document.getElementById('Create_Password').value;
		var Group_Name = <?php echo json_encode($_GET['GroupName']); ?>;

		// Check that they atleast entered a name

		var xmlhttp = new XMLHttpRequest();
		var url = "new_summoner.php?Group=" + Group_Name + "&Name=" + Create_Name + "&Summoner_Name=" + Create_Summoner_Name + "&Password=" + Create_Password;

		xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		        Create_User(xmlhttp.responseText);
		    }
		}

		xmlhttp.open("GET", url, true);
		xmlhttp.send();

	}
	function Create_User(Response_Text){
		document.getElementById('Create_Status').innerHTML = Response_Text;
	}


	</script>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
