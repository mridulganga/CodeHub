//embed code i.e. get code
//get votes
//get views
//get code listing
//submit code
//
<?php

include("base/misc.php");

	//error Strings
	function getErrorString($num){
		switch($num){
			case 404:return "404 Not Found";
			case 400:return "Bad Request";
			case 401:return "Unauthorised";
			case 403:return "Forbidden";
			case 405:return "Method Not Allowed";
			case 406:return "Not Acceptable";
			case 408:return "Request Timeout";

			case 200:return "User Not Found";
			case 201:return "User Already Exists with given email";
			case 202:return "Password Incorrect";
			case 203:return "Invalid Email Format";
			case 204:return "Username can't contain special characters";
			case 205:return "Password should contain atleast 6 characters";
			case 206:return "Username can't be left blank";
			case 207:return "email can't be left blank";
			case 208 :return "Passwords do not match";

			case 300:return "Session has expired";
			case 301:return "Information couldn't be retrived";

			case 1:return "Title can't be left Blank";
			case 2:return "Code can't be left Blank";
			case 3:return "Username can't be left blank";
			case 4:return "Email can't be left blank";
		}
	}

	function errorHTML($num,$state){
		switch($state){
			case 1:$stat="alert alert-success";break;	//1=success
			case 2:$stat="alert alert-info";break;		//2=information
			case 3:$stat="alert alert-warning";break;	//3=warning
			case 4:$stat="alert alert-danger";break;	//4=danger
		}
		$html = '<div class="'.$stat.'" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>'.getErrorString($num).'</strong> 
				</div>';
		return $html;
	}

	//USER Functions===============================================================
	function createUser($user){
		$sql="insert into users values(0,";	//user_id - auto increment
		$sql.="'".$user["name"]."',";		//user_name
		$sql.="'".$user["email"]."',";		//email
		$sql.="'".hash("sha256",$user["pass"])."'"			//Password - sha256 encrypted
		$sql.=")";
	}
	?>