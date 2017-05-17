<?php 

if (isset($_GET["t"]))
	$varr = $_GET["t1"];
else
 	$varr="Title not set";
 

?>

<html>
<head>
	<title><?php echo $varr; ?></title>
</head>
<body>
<form action="sample.php" method="GET">
	<input type="text" name="t1" value="sample title">
	<input type="text" name="t2" value="sample title">

	<input type="radio" name="op" value="add">Add &nbsp;&nbsp;&nbsp;
	<input type="radio" name="op" value="substract">Substract

	<input type="submit" value="Calculate">

</form>
<h1>Answer: <?php 

if (isset($_GET["op"])){
	switch($_GET["op"]){
		case "add":
		$ans = $_GET["t1"]+$_GET["t2"];
		break;

		case "substract":
		$ans = $_GET["t1"]-$_GET["t2"];
		break;
	}
	echo $ans;
}

?></h1>
</body>
</html>
