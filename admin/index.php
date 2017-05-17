<?php
include("../base/misc.php");

if(isset($_GET["logout"]))
	unset($_SESSION["admin"]);

if(isset($_SESSION["admin"]))
{
	
}
elseif(isset($_POST["admin"]))
{
	$_SESSION["admin"]="admin";
}	
else
{
	echo '<form action="index.php" method="post"><input type="submit" name="admin" value="Login as Admin"></form>';
	die();
}


echo '<ul>
			<li>Manage Users</li>
			<li>Manage Codes</li>
			<li><a href="index.php?logout=true">Logout</a></li>
		</ul>';

?>