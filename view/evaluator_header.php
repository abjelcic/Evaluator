<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Evaluator</title>
	<link rel="stylesheet" type="text/css" href="css/evaluatorstyle.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

	<h1>
	<?php
	    require_once __DIR__ . '/../model/evaluatorservice.class.php';
	    echo 'Evaluator! @' . $username . (EvaluatorService::IsAdmin($username)?" (admin)":"");
	?>
	</h1>

	<hr>
	<nav>
		<ul id="navigationBarList">
		
			<?php 
				$MenuItems = array( "about"  		=>  "About"        ,
									"progress"    	=>  "Progress"	   ,
									"archives"    	=>  "Archives"     ,
									"news"        	=>  "News"		   ,
									"recent"     	=>	"Recent"       ,
									"logout"      	=>  "Logout"      );
				foreach( $MenuItems as $menukey => $menuvalue )
				{
					$href = "index.php?rt=evaluator/" . $menukey;

					echo "<li><a href=\"" . $href . "\"";
					if( isset( $selected ) && $selected === $menukey )
					{
						echo " class=\"SelectedMenuItem\" ";
					}
					echo ">" . $menuvalue . "</a></li>\n";
				}
			?>
					
		</ul>
	</nav>
	<hr>

	
