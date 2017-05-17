<?php include_once("base/header.php");?>

<?php
	if ($_SERVER['REQUEST_METHOD']==="GET"){
		//User Wants to do something
		$operation = $_GET["op"];	//What does the user want to do?
		$limit=50;
		switch($operation){
			case "login":
				if (isset($_GET["u"]) && isset($_GET["p"])){
					if (userLogin($_GET["u"],$_GET["p"])){
						redirect($_GET["redirect"]);
					}
					else
						echo errorHTML(202,4);
						echo printLoginForm($_GET["redirect"]);

				}
				else{
					//show login form
					echo printLoginForm($_SERVER["HTTP_REFERER"]);
				}

				break;
			case "logout":
				//session_destroy
				if (isset($_SESSION["name"])){
					$url=$_SERVER["HTTP_REFERER"];
					session_destroy();
					redirect($url);
				}
				else
				{
					echo "No user was logged in.";
				}
				break;
			case "profile":
				if (isset($_SESSION["name"])){
					echo titlePrint("Profile");
					echo "Username : ".$_SESSION["name"];
				}
				else{
					echo errorHTML(301,4);
					echo printLoginForm();
				}
				break;
			case "register":
			if (isset($_GET["name"])){


/*
				if (isset($_GET["otp"])){
					echo $_GET["otp"];
				echo $_SESSION["otp"];
					if($_GET["otp"]==$_SESSION["otp"]){
							$sql = getSQL("register");
							echo $sql;
							$conn=connectDB();
							executeDB($conn,$sql);
							disconnectDB($conn);
							$_SESSION["name"]=$_SESSION["form-name"];
							$_SESSION["email"]=$_SESSION["form-email"];
							redirect("user.php?op=profile");
						}//register here

				}
				elseif(isset($_GET["mob"])){
					$_SESSION["mob"] = $_GET["mob"];
					$_SESSION["otp"] = rand();
					$smsLink = "http://www.smszone.in/sendsms.asp?page=SendSmsBulk&username=919008726274&password=D348&number=".$_SESSION["mob"]."&message=Your%20OTP%20is:".$_SESSION["otp"];

					$ch = curl_init($smsLink);
					curl_exec($ch);
					curl_close($ch);
				$_SESSION["form-name"]=$_GET["name"];
				$_SESSION["form-email"]=$_GET["email"];
				$_SESSION["form-pass"]=$_GET["pass"];
				$_SESSION["form-mob"]=$_GET["mob"];
					echo printOTPForm();
				}
*/

				$_SESSION["form-name"]=$_GET["name"];
				$_SESSION["form-email"]=$_GET["email"];
				$_SESSION["form-pass"]=$_GET["pass"];
				$sql = getSQL("register");
				//echo $sql;
				$conn=connectDB();
				executeDB($conn,$sql);
				disconnectDB($conn);
			}
			else
			{
				echo printRegisterForm();
			}
				//get the required fields
				//check if user exists
				//add record and auto login
				//redirect to profile
				break;
			case "editprofile":
				//redirect to edit page
				break;
			case "submissions":
			if(isset($_SESSION["email"])==FALSE)
				redirect("index.php");
				$conn = connectDB();
				$result = queryDB($conn,getSQL("submissions",$_SESSION["email"]));
				if ($result===FALSE)
					break;
				$count=pagesCount($result,$limit);
				if($count>1 && isset($_GET["page"]))
					$result =queryDB($conn,getSQL("viewall")." lIMIT ".getStartLimit($_GET["page"],$limit).",".$limit);
				elseif($count>1 && isset($_GET["page"])===FALSE){
					$result =queryDB($conn,getSQL("viewall")." lIMIT 0,".$limit);
					$_GET["page"]=1;
					$_SERVER["QUERY_STRING"]=$_SERVER["QUERY_STRING"]."&page=1";
				}
				else{$_GET["page"]=0;}
				echo titlePrint("My Submissions",FALSE);

				echo getnerateListTable($result);

				echo paginationPrint($_GET["page"],$count);
				break;

		}
	}
	function printLoginForm($urltodirect)
	{	$html = titlePrint("Login",FALSE);
		$html.='<center>
				<form action="user.php" method="get" style="max-width:300px;">
				<input type="hidden" name="op" value="login">
					<div class="input-group">
  						<span class="input-group-addon" id="basic-addon1">Email</span>
  						<input type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" name="u">
					</div><br>
					<div class="input-group">
  						<span class="input-group-addon" id="basic-addon2">Password</span>
  						<input type="password" class="form-control" placeholder="" aria-describedby="basic-addon2" name="p">
					</div><br>
					<input type="hidden" name="redirect" value="'.$urltodirect.'">
				<input type="submit" class="form-control btn btn-primary"  value="Login">
				<br><br>New User?
				<a href="user.php?op=register" class="btn btn-info form-control">Register</a>
				</form>
				</center>';
		return $html;
	}
		/*function printOTPForm()
	{	$html = titlePrint("Enter OTP",FALSE);
		$html.='<center>
				<form action="user.php" method="get" style="max-width:300px;">
				<input type="hidden" name="op" value="register">
					<div class="input-group">
  						<span class="input-group-addon" id="basic-addon1">OTP</span>
  						<input type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" name="otp">
					</div><br>
					<input type="hidden" name="name" value="user">
				<input type="submit" class="form-control btn btn-primary"  value="Verify">
				<br>
				</form>
				</center>';
		return $html;
	}*/
	function printRegisterForm(){
		$html=titlePrint("Register",FALSE);
		$html .='<center>
				<form action="user.php" method="get" style="max-width:300px;">
				<input type="hidden" name="op" value="register">
					<div class="input-group">
  						<span class="input-group-addon" id="basic-addon2">Name</span>
  						<input type="text" class="form-control" placeholder="" aria-describedby="basic-addon2" name="name">
					</div><br>
					<div class="input-group">
  						<span class="input-group-addon" id="basic-addon1">Email</span>
  						<input type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" name="email">
					</div><br>
					<div class="input-group">
  						<span class="input-group-addon" id="basic-addon2">Password</span>
  						<input type="password" class="form-control" placeholder="" aria-describedby="basic-addon2" name="pass">
					</div><br>

					<!--div class="input-group">
  						<span class="input-group-addon" id="basic-addon2">Mobile</span>
  						<input type="text" class="form-control" placeholder="Only for authentication" aria-describedby="basic-addon2" name="mob">
					</div --><br>
				<input type="submit" class="form-control" value="Register">
				</form>
				</center>';
				return $html;
	}

	function userLogin($email,$pass){
		$conn = connectDB();
		$pass = hash("sha256",$pass);
				//do login
		$result =  queryDB($conn,"select * from users where email='".$email."' and password='".$pass."'");
		if ($result->num_rows>0)
			{
				$row  =$result->fetch_assoc();
				$_SESSION['email'] = $row['email'];
        		$_SESSION['name']    = $row['user_name'];
        		$_SESSION['logged']   = TRUE;
        		return TRUE;
			}
		else
			return FALSE;
	}
	function userLogout(){
		if (isset($_SESSION["name"])){
			session_destroy();
			return TRUE;
		}
		else
			echo "No user was logged in.";

	}

?>

<?php include_once("base/footer.php"); ?>
