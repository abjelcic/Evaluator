<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Chat</title>
	<link rel="stylesheet" type="text/css" href="css/chatstyle.css">
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
					$href = "index.php?rt=chat/" . $menukey;

					echo "<li><a href=\"" . $href . "\"";
					if( isset( $selected ) && $selected === $menukey )
					{
						echo " class=\"SelectedMenuItem\" ";
					}
					echo ">" . $menuvalue . "</a></li>\n";
				}
			?>

			<!-- <li><a href="index.php?rt=chat/mychannels">My channels</a></li> 			-->
			<!-- <li><a href="index.php?rt=chat/allchannels">All channels</a></li>			-->
			<!-- <li><a href="index.php?rt=chat/newchannel">Start a new channels</a></li>	-->
			<!-- <li><a href="index.php?rt=chat/mymessages">My messages</a></li>			-->
			<!-- <li><a href="index.php?rt=chat/logout">Logout</a></li>						-->
		
		</ul>
	</nav>
	<hr>

	
