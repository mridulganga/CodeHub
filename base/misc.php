
<?php

session_start();
	//Basic DB Functions-----------------------------------------------------------------
	function connectDB(){
		
		
		//parsing doesnt work. 

		$host = getDBSetting("host");
		$user = getDBSetting("user");
		$pass = getDBSetting("pass");
		$db = getDBSetting("db");
		
		$conn = new mysqli($host, $user, $pass, $db);
		// Check connection
		if ($conn->connect_error) {
			return FALSE;
		} 
		return $conn;
	}
	
	function disconnectDB($conn){
		$conn->close();
	}
	
	function queryDB($conn, $sql){
		$result = $conn->query($sql);
		//return table if its not empty
				return $result;
	}

	function executeDB($conn,$sql){
		if ($conn->query($sql) === TRUE)
			return TRUE;
		else
			echo  $conn->error;
	}


//SQL Statement Generation-----------------------------------------------------------------
	function getSQL($op,$v1="",$v2=""){
		switch($op){
			case "search":
				$sterms = explode(" ", $v1);
				$sstr = "(";
				foreach ($sterms as $term)
				$sstr .= " (code_title like('%".$term."%') or tags like('%".$term."%') or author_name like('%".$term."%') or lang like('%".$term."%') ) and";
				$sstr = substr($sstr,0,-3).")";
				$sql = "SELECT * FROM code where ".$sstr;
				break;
			case "viewall":
				$sql = "SELECT * FROM code";
				break;
			case "filter":
					switch($v1){
						case "author": $sql = "SELECT * FROM code where author_name='".$v2."'";break;
						case "lang":$sql = "SELECT * FROM code where lang='".$v2."'";break;
					}
			break;
			case "sort":
				if ($v1==="A")
					$direc = "ASC";
				else
					$direc = "DESC";

				switch($v2){
					case "author": $sql = "SELECT * FROM code order by author_name ".$direc;break;
					case "lang":$sql = "SELECT * FROM code order by lang ".$direc;break;
					case "title":$sql = "SELECT * FROM code order by code_title ".$direc;break;
				}
				break;
			case "remove":
				$sql = "DELETE from code where code_id=".$v1;
				break;
			case "submissions":	//for My Submissions section
				$sql = "SELECT * from code where author_email='".$v1."'";
				break;
			case "single":
				$sql = "SELECT * FROM code where code_id=".$v1;
				break;
			case "vote":
				$sql = "replace into votes set uid = '".$v1."' , cid = '".$v2."'";
				break;
			case "unvote":
				$sql="DELETE from votes where uid='".$v1."' and cid='".$v2."'";
				break;
			case "register":
				$sql="insert into users values(0,'".$_SESSION["form-name"]."','".$_SESSION["form-email"]."','".hash("sha256",$_SESSION["form-pass"])."')";
		}
		return $sql;
	}



//Add or Remove Submission-----------------------------------------------------------------
	function insertCode($code_title,$code_lang,$usage,$code,$in_out,$tags,$a_name,$a_email){
		// Create connection
		$conn = connectDB();
		//convert all html special chars to escape sequence
		$code_title=$conn->real_escape_string($code_title);
		$code_lang=$conn->real_escape_string($code_lang);
		$usage = $conn->real_escape_string($usage);
		$code = $conn->real_escape_string($code);
		$in_out = $conn->real_escape_string($in_out);



		$sql = "INSERT INTO code  VALUES (0,'".$code_title."','".$code_lang."','".$usage."','".$code."','".$in_out."','".$tags."','".$a_name."','".$a_email."')";					
		if (executeDB($conn,$sql) === TRUE) {
			disconnectDB($conn);
			return TRUE;
		} 
		
	}

	function removeCode($code_id){
				$conn = connectDB();

		$sql = "DELETE from code where code_id=".$code_id;
		if (executeDB($conn,$sql) === TRUE) {
			disconnectDB($conn);
			return TRUE;
		} 
	}


//Table HTML Generation-----------------------------------------------------------------
	function getnerateListTable($result){
			$html='<table class="table table-striped">
				<tr>
				<th width=20%><a href="code.php?op=sort&fvar=title">Title</a></th>
				<th width=50%>Usage</td>
				<th width=15%><a href="code.php?op=sort&fvar=lang">Language</a></th>
				<th width=15%><a href="code.php?op=sort&fvar=author">Author</a></th>
				</tr>';
			while($row = $result->fetch_assoc()) {
				$html .= "<tr>";
				$html .= "<td> <a href='code.php?op=single&id=".$row["code_id"]."' >" . $row["code_title"]. "</a> <span title='Votes' class='badge'>".getVotes($row["code_id"])."<span></td>";
				$html .= "<td>" . $row["usage"]. "</td>";
				$html .= "<td> <a href='code.php?op=filter&fvar=lang&fval=".$row["lang"]."'>" . $row["lang"]. "</a></td>";
				$html .= "<td> <a href='code.php?op=filter&fvar=author&fval=".$row["author_name"]."'>" . $row["author_name"]."</a></td>";
				$html .= "</tr>";
			}
			return $html."</table>";
	}

	function generateSingleTable($result){
		$html="<table class='table table-striped'>";
		if ($result===FALSE)
			return "";
		$row = $result->fetch_assoc();
			$html .= "<tr><th>Code Title</th><td> ".$row["code_title"]."</td></tr>";
			$html .= "<tr><th>Language</th><td> ".$row["lang"]."</td></tr>";
			$html .= "<tr><th>Usage</th><td> ".str_replace(["\r\n", "\r", "\n"], "<br/>", $row["usage"])."</td></tr>";
			$html .= "<tr><th>Code </th><td><pre> <code class='".$row["lang"]."'>". htmlspecialchars($row["code"])."</code></pre></td></tr>"; 

			$html .= '<tr><td></td><td><form action="run.php" method="GET" style="float:left;"><input type="hidden" name="lang" value="'.$row["lang"].'"><textarea name="code" style="display:none;" >'.$row["code"].'</textarea><input type="submit" class="btn btn-primary" value="Compile"></form>';
			$html .= '<a class="btn btn-default" onclick="alert(\'doesnt Work\')">Download</a>';
			$html .= '<a class="btn btn-default" onclick="alert(\'doesnt Work\')">embed</a></td></tr>';

			$html .= "<tr><th>Input Output</th><td> <pre>".str_replace(["\r\n", "\r", "\\n"], "<br/>", $row["in_out"])."</pre></td></tr>";
			$html .= "<tr><th>Author</th><td><a href='mailto:".$row["author_email"]."'> ".$row["author_name"]."</a></td></tr>";
			$html .= "<tr><th>Views</th><td>".getViews($row["code_id"])."</td></tr>";
			return $html."</table>";
		
	}
	function titlePrint($title,$back=FALSE){
		$html= '<div class="page-header">
				<h1 style="text-align:center;">'.$title.'</h1>
				</div>';
		if ($back===TRUE)
			$html .= "<a href='code.php?op=viewall' class='btn btn-default'><span class='glyphicon glyphicon-chevron-left'></span>View All Submissions</a><br><br>";
		else
			$html .="<br><br>";
		return $html;
	}
		function tableHeader(){
		$html='<table class="table table-striped">
				<tr>
				<th width=20%><a href="code.php?op=sort&fvar=title">Title</a></th>
				<th width=50%>Usage</td>
				<th width=15%><a href="code.php?op=sort&fvar=lang">Language</a></th>
				<th width=15%><a href="code.php?op=sort&fvar=author">Author</a></th>
				</tr>';
		return $html;
	}
	function tableFooter(){
		$html="</table>";
		return $html;
	}
	function paginationPrint($current,$maximum){
		$html='<div class="btn-group" role="group" aria-label="" style="margin:0 auto;">';
		if ($maximum>1)
			
		{if ($current>1){
			$purl = str_replace("page=".$current,"page=".($current-1),"code.php?".$_SERVER["QUERY_STRING"]);
			$html.="<a href='".$purl."' class='btn btn-default'>Previous</a>";
		}
		if ($current>0 && $maximum>1){
			for($i=1;$i<=$maximum;$i++){
				$tmpurl = str_replace("page=".$current,"page=".$i,"code.php?".$_SERVER["QUERY_STRING"]);
				if ($i==$current)
					$html.='<a href="'.$tmpurl.'" class = "btn btn-info">'.$i.'</a>';
				else
					$html.='<a href="'.$tmpurl.'" class = "btn btn-default">'.$i.'</a>';
			}
		}

		if ($current<$maximum) {
			$purl = str_replace("page=".$current,"page=".($current+1),"code.php?".$_SERVER["QUERY_STRING"]);
			$html.="<a href='".$purl."' class='btn btn-default'>Next</a>";
		}
		}
		$html.="</div>";
		return $html;
	}
	function voteBtnPrint($vote,$id){
		if (hasVoted($id)==TRUE)
			$html='<a href="code.php?op=unvote&id='.$id.'" class="btn btn-default"> Voted <span class="badge">'.$vote.'</span></a>';
		else
			$html='<a href="code.php?op=vote&id='.$id.'" class="btn btn-info"> Vote <span class="badge">'.$vote.'</span></a>';
		return $html;
	}


//Get Single Values-----------------------------------------------------------------
	function getCodeID($title,$email,$author,$lang){
		$sql="select code_id from code where author_name='".$author."' and code_title='".$title."' and author_email='".$email."' and lang='".$lang."'";
		$conn = connectDB();
		$result =queryDB($conn,$sql);
		while($row = $result->fetch_assoc()){
			$id = $row["code_id"];
			disconnectDB($conn);
			return $id;
		}
	}

	function getTitle($id){
		$conn = connectDB();
		$sql="select code_title from code where code_id=".$id;
		$result =queryDB($conn,$sql);
		disconnectDB($conn);
		while($row = $result->fetch_assoc()){
			$title = $row["code_title"];
			return $title;
		}
	}


//Code View get or Add View-----------------------------------------------------------------
	function addView($code_id){
		$conn = connectDB();
		$sql = "select * from views where code_id=".$code_id;
		$result  = queryDB($conn,$sql);

		if ($result->num_rows===0){
			$sql = "insert into views values(".$code_id.",1)";
			if (executeDB($conn,$sql))
				return TRUE;
		}
		else{
			$sql = "update views set views = (views+1) where code_id=".$code_id;
			if (executeDB($conn,$sql))
				return TRUE;
		}
		return FALSE;
	}
	function getViews($code_id){
		$conn = connectDB();
		$sql =  "select * from views where code_id=".$code_id;
		$result  = queryDB($conn,$sql);

		if ($result->num_rows===0){
			return 0;
		}
		else
		{
			$row  =$result->fetch_assoc();
			return $row["views"];
		}
	}



//Pagination Functions-----------------------------------------------------------------
	function pagesCount($result,$limit){
		
		if ($result->num_rows===0)	//just some error handling if there is no table
			return 0;
		else{
			$rcount = $result->num_rows;	//get the total no of records for the given query
			
			if (($rcount%$limit)!==0)	//calculate the no of pages
				return ($rcount - ($rcount%$limit))/$limit+1;
			else
				return ($rcount - ($rcount%$limit))/$limit;
		}
	}
	function getStartLimit($page,$limit){
		return ($page-1)*$limit;
	}


//Voting Functions-----------------------------------------------------------------
	function hasVoted($id){
		if(isset($_SESSION["name"])){
			$uid = getUserID($_SESSION["email"]);
			$conn = connectDB();
			$result = queryDB($conn,"select * from votes where uid='".$uid."' and cid='".$id."'");
			disconnectDB($conn);
				if($result->num_rows===0)
					return FALSE;
				else
					return TRUE;	//User has voted
			}
		else
			return FALSE;
	}
	function getVotes($id){
		$conn = connectDB();
		$result=queryDB($conn,"select count(*) from votes where cid='".$id."'");
		disconnectDB($conn);
		if ($result->num_rows>0)
		{
			$row = $result->fetch_assoc();
			return $row["count(*)"];
		}
		else
			return 0;
	}

		
//User Account Functions-----------------------------------------------------------------
	function getUserID($email){
		$conn = connectDB();
		$result=queryDB($conn,"Select user_id from users where email='".$email."'");
		disconnectDB($conn);
		if($result->num_rows===0)
			return FALSE;
		else{
			$row = $result->fetch_assoc();
			return $row["user_id"];
		}
	}
	function getCodeEmail($id){
		$conn = connectDB();
		$result=queryDB($conn,"Select author_email from code where code_id=".$id);
		disconnectDB($conn);
		if($result->num_rows===0)
			return FALSE;
		else{
			$row = $result->fetch_assoc();
			return $row["author_email"];
		}
	}

	function changeUserPass($uid,$old,$new){
		$conn = connectDB();
		$old = hash("sha256",$old);
		$new = hash("sha256",$new);
		$result = queryDB($conn,"select password from users where user_id=".$uid);
		if ($result->num_rows>0){
			$row = $result->fetch_assoc();
			if ($row["password"]===$old){
				if(executeDB($conn,"update users set password='".$new."' where user_id=".$uid))
					return TRUE;
			}
		}
		return FALSE;
	}



	function getSetting($setting){
		$set=parse_ini_file("settings.ini",true);
		//print_r($set);
		switch($setting){
			case "codes_per_page":return $set["general"]["codes_per_page"];

			case "index_bg":return $set["index"]["background"];
			case "index_footer_bg":return $set["index"]["footer_background"];
			case "index_panel_heading":return $set["index"]["panel_heading_background"];
			case "index_button":return $set["index"]["button"];
			case "index_button_active":return $set["index"]["button_active"];
			case "index_button_hover":return $set["index"]["button_hover"];
			

		}
	}


	function getDBSetting($setting){
		$set=parse_ini_file("dbset.ini",true);
		//print_r($set);
		switch($setting){
			case "host":return $set["general"]["host"];
			case "user":return $set["general"]["user"];
			case "pass":return $set["general"]["pass"];
			case "db":return $set["general"]["database"];

		}
	}




	function getExtension($lang){
		$ext="";
		switch ($lang) {
			case 'java':
				$ext=".java";
				break;
			
			default:
				# code...
				break;
		}
	}

	function redirect($url){
		echo "<script type='text/javascript'>window.location.href = '".$url."';</script>";
        exit();
	}

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
			case 301:return "User is not logged in";
			case 302:return "Information couldn't be retrived";

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

?>
