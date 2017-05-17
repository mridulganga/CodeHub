<?php include_once("base/header.php");?>
<?php include("base/compile.php");

//Setting up the Hackerearth API
$hackerearth = Array(
		'client_secret' => '3c197943972f7c7d62393b1ece291ac557ebd8db', //(REQUIRED) Obtain this by registering your app at http://www.hackerearth.com/api/register/
        'time_limit' => '5',   //(OPTIONAL) Time Limit (MAX = 5 seconds )
        'memory_limit' => '262144'  //(OPTIONAL) Memory Limit (MAX = 262144 [256 MB])
	);
?>
<?php echo titlePrint("Run Code");?>

<form method="GET" action="run.php" style="max-width:800px; margin:0 auto;">
	<div class="input-group input-group-lg">
		<span class="input-group-addon" id="basic-addon2">Language</span>
		<select name="lang" class="form-control" aria-describedby="basic-addon2">
			<option value="JAVA" <?php if(isset($_GET["lang"])){if($_GET["lang"]==="JAVA"){echo "selected";}} ?>  >Java</option>
			<option value="CPP"  <?php if(isset($_GET["lang"])){if($_GET["lang"]==="CPP"){echo "selected";}} ?>  >C++</option>
			<option value="C"  <?php if(isset($_GET["lang"])){if($_GET["lang"]==="C"){echo "selected";}} ?>  >C</option>
			<option value="CSHARP"  <?php if(isset($_GET["lang"])){if($_GET["lang"]==="CSHARP"){echo "selected";}} ?>  >C#</option>
			<option value="PHP"  <?php if(isset($_GET["lang"])){if($_GET["lang"]==="PHP"){echo "selected";}} ?>  >PHP</option>
			<option value="PYTHON"  <?php if(isset($_GET["lang"])){if($_GET["lang"]==="PYTHON"){echo "selected";}} ?>  >Python</option>
			<option value="JAVASCRIPT"  <?php if(isset($_GET["lang"])){if($_GET["lang"]==="JAVASCRIPT"){echo "selected";}} ?>  >JavaScript</option>
			<option value="PERL"  <?php if(isset($_GET["lang"])){if($_GET["lang"]==="PERL"){echo "selected";}} ?>  >Perl</option>
			</select>
	</div><br>
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon4">Code</span>
  		<textarea name="code" class="form-control" aria-describedby="basic-addon4" style="min-height:350px;" placeholder="The code itself!"><?php if(isset($_GET["code"])){echo $_GET["code"];}?></textarea>
	</div><br>	

	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon1">Custom Input</span>
  		<textarea name="inp" class="form-control" aria-describedby="basic-addon4" style="min-height:180px;" placeholder="Only if your program needs input"><?php if(isset($_GET["inp"])){echo trim($_GET["inp"]);}?></textarea>
  		
	</div><br>
	<input type="hidden" name="run" value="yes">
	<input type="submit" value="Run Code" class="btn btn-lg btn-primary">
</form>
<br><br><br>


<?php

if (isset($_GET["run"]) && isset($_GET["code"]) && isset($_GET["inp"]) && isset($_GET["lang"])){

//Feeding Data Into Hackerearth API
$config = Array();
$config['time']='5';	 	//(OPTIONAL) Your time limit in integer and in unit seconds
$config['memory']='262144'; //(OPTIONAL) Your memory limit in integer and in unit kb
$config['source']=$_GET["code"];    	//(REQUIRED) Your formatted source code for which you want to use hackerEarth api, leave this empty if you are using file
$config['input']=$_GET["inp"];     	//(OPTIONAL) formatted input against which you have to test your source code
$config['language']=$_GET["lang"];  //(REQUIRED) Choose any one of the below
						 	// C, CPP, CPP11, CLOJURE, CSHARP, JAVA, JAVASCRIPT, HASKELL, PERL, PHP, PYTHON, RUBY
//Sending request to the API to compile and run and record JSON responses
$response = run($hackerearth,$config);     // Use this $response the way you want , it consists data in PHP Array
//Printing the response
	if (isset($response["run_status"]["output_html"])){
		echo"<hr><h2>Output</h2><div class='well'><pre>".$response["run_status"]["output_html"]."</pre></div>";	
	}
	elseif ($response["run_status"]["status"]==="CE") {
		echo"<hr><h2>Compilation Error, Please Check Your Syntax</h2>";
	}
	else
	{
		echo"<pre>".print_r($response,1)."</pre>";
	}
	
	
}
?>
<?php include_once("base/footer.php");?>