<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    
    <title> Your Movie DataBase </title>
    <meta charset='utf-8'>
	
        
    <script src="jqui-redmond/external/jquery/jquery.js"></script>
    <script src="jqui-redmond/jquery-ui.js"></script>
    <!-- <link rel="stylesheet" href="jqui-redmond/jquery-ui.css">   -->
	<link rel="stylesheet" href="font-awesome4/css/font-awesome.min.css">

	<link rel="stylesheet" href="ymdb.css">	
	<script src='JSLogic.js'></script>	
    <script type="text/javascript">
		
            <?php
			
			$user_addr = $_SERVER['REMOTE_ADDR'];
			
             if ( !file_exists('yourmovies.db') ){
				// Trigger Init DB-engine, if not present
                print (' init_func[0] = true; ');
             }
			 
			 if ($user_addr=="127.0.0.1" || $user_addr=="localhost" || $user_addr=="::1") {
				// Trigger Spoil admin data, if localhost
			 	print (' init_func[1] = true; ');
			 }
			 
            ?>
			
			// Trigger Detect user in system
			init_func[2] = true;
			init_func[3] = true;
			user_ip="<?php print($user_addr); ?>";
        
    </script>
	
</head>
<body>
    
	<div align="center">
	
    <h1 style="margin:7px 0px 3px 0px;"> Your movie database </h1>
    <h3 style="margin:0px 0px 0px 0px;"> Ваш персональный индекс фильмов и сериалов </h3>
		
	<?php require 'dailogs.php'; ?>
	
	<div id='MOVIES' style='width:100%; text-align:center;'>
	</div>

	</div>
	
	<div id='global_vars'></div>
    
</body>
</html>
