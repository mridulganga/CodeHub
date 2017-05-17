<?php include_once("base/header.php");?>

<?php 

	if ($_SERVER['REQUEST_METHOD']==="GET"){
		//User Wants to do something
		$operation = $_GET["op"];	//What does the user want to do?
		$conn = connectDB();
		$limit=getSetting("codes_per_page");	//no of items per page
		
		switch($operation){
			
			case "viewall":	
				echo titlePrint("Code Submissions");
				//show toolbar
				$result =queryDB($conn,getSQL("viewall"));
				$count=pagesCount($result,$limit);
				if($count>1 && isset($_GET["page"]))
					$result =queryDB($conn,getSQL("viewall")." lIMIT ".getStartLimit($_GET["page"],$limit).",".$limit);
				elseif($count>1 && isset($_GET["page"])===FALSE){
					$result =queryDB($conn,getSQL("viewall")." lIMIT 0,".$limit);
					$_GET["page"]=1;
					$_SERVER["QUERY_STRING"]=$_SERVER["QUERY_STRING"]."&page=1";
				}
				else{$_GET["page"]=0;}
				
				echo getnerateListTable($result);
				
				echo paginationPrint($_GET["page"],$count);
				//echo pagesCount($result,$limit);
				break;



			case "search":
				$query = $_GET["q"];
				echo titlePrint("Search - ".$query,TRUE);
				$result =queryDB($conn,getSQL("search",$query));
				if ($result===FALSE)
					break;
				$count=pagesCount($result,$limit);
				if($count>1 && isset($_GET["page"]))
					$result =queryDB($conn,getSQL("search",$query)." lIMIT ".getStartLimit($_GET["page"],$limit).",".$limit);
				elseif($count>1 && isset($_GET["page"])===FALSE){
					$result =queryDB($conn,getSQL("search",$query)." lIMIT 0,".$limit);
					$_GET["page"]=1;
					$_SERVER["QUERY_STRING"]=$_SERVER["QUERY_STRING"]."&page=1";
				}
				else{$_GET["page"]=0;}
				
				echo getnerateListTable($result);
				
				echo paginationPrint($_GET["page"],$count);
				//echo pagesCount($result,2);
				break;



			case "filter":
				$fvar = $_GET["fvar"];	//How the user wants to filter ex - Author , Language etc
				$fval = $_GET["fval"];
				echo titlePrint("Filter - ".$fval,TRUE);
				
				$result =queryDB($conn,getSQL("filter",$fvar,$fval));
				$count=pagesCount($result,$limit);
				if($count>1 && isset($_GET["page"]))
					$result =queryDB($conn,getSQL("filter",$fvar,$fval)." lIMIT ".getStartLimit($_GET["page"],$limit).",".$limit);
				elseif($count>1 && isset($_GET["page"])===FALSE){
					$result =queryDB($conn,getSQL("filter",$fvar,$fval)." lIMIT 0,".$limit);
					$_GET["page"]=1;
					$_SERVER["QUERY_STRING"]=$_SERVER["QUERY_STRING"]."&page=1";
				}
				else{$_GET["page"]=0;}
				echo getnerateListTable($result);
				
				echo paginationPrint($_GET["page"],$count);
				//echo pagesCount($result,2);
				break;



			case "sort":
				$fvar = $_GET["fvar"];
				echo titlePrint("Sort",TRUE);
				
				$result =queryDB($conn,getSQL("sort","A",$fvar));
				$count=pagesCount($result,$limit);
				if($count>1 && isset($_GET["page"]))
					$result =queryDB($conn,getSQL("sort","A",$fvar)." lIMIT ".getStartLimit($_GET["page"],$limit).",".$limit);
				elseif($count>1 && isset($_GET["page"])===FALSE){
					$result =queryDB($conn,getSQL("sort","A",$fvar)." lIMIT 0,".$limit);
					$_GET["page"]=1;
					$_SERVER["QUERY_STRING"]=$_SERVER["QUERY_STRING"]."&page=1";
				}
				else{$_GET["page"]=0;}
				echo getnerateListTable($result);
				
				echo paginationPrint($_GET["page"],$count);
				//echo pagesCount($result,2);
				break;



			case "edit":
				$id=$_GET["id"];
				echo "Will be added soon";
                redirect(" edit-code.php?id=".$id);
				break;



			case "remove":
				$id=$_GET["id"];
                removeCode($id);
                echo "The Code has been Removed";
                echo '<br><a href="code.php?op=viewall" class="btn btn-lg btn-default">View All</a>';
				break;



			case "insert":
				redirect(" submit-code.php");
				break;



			case "single":
				$id=$_GET["id"];
				$result =queryDB($conn,getSQL("single",$id));
				echo includeHighlighter();
				echo includeFB();
				echo titlePrint("Code -".getTitle($id),TRUE);
				
				echo generateSingleTable($result);
				
				echo voteBtnPrint(getVotes($id),$id);
					if (isset($_SESSION["name"])){
						$uid = getUserID($_SESSION["email"]);
						$uuid = getUserID(getCodeEmail($id));
						if ($uid===$uuid)
							echo '<a href="code.php?op=remove&id='.$id.'" class="btn btn-danger">Remove</a>';
					}
				echo "<br>";
				addView($id);	//Increase the no of views
				echo loadComments('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				
				break;



			case "vote":
				$url=$_SERVER["HTTP_REFERER"];
				$id = $_GET["id"];
				if (isset($_SESSION["name"])){
					$uid = getUserID($_SESSION["email"]);
				if(executeDB($conn,getSQL("vote",$uid,$id)))
					redirect($url);	
				}
				else
				{
					echo errorHTML(301,4);
					echo '<a href="'.$url.'" class="btn btn-warning">Go Back</a><a class="btn btn-primary" href="user.php?op=login">Login or Register</a>';
				}
				break;



			case "unvote":
				$uid = getUserID($_SESSION["email"]);
				$url=$_SERVER["HTTP_REFERER"];
				$id = $_GET["id"];
				if(executeDB($conn,getSQL("unvote",$uid,$id)))
					//redirect($url);
					echo "";
				else
					echo errorHTML(302,4);
			
		}
	}//if ends
		disconnectDB($conn);


	
	function includeHighlighter(){
		$html="<link rel='stylesheet' href='base/css/highlight/hybrid.css'>";
		$html .="<script src='base/highlight.pack.js'></script>";
		$html .="<script>hljs.initHighlightingOnLoad();</script>";
		return $html;
	}

	function includeFB(){
			$html= '<div id="fb-root"></div>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, \'script\', \'facebook-jssdk\'));</script>';
			return $html;
	}

	function loadComments($url){
		$html='<div class="fb-comments" data-href="'.$url.'" data-numposts="5"></div>';
		return $html;
	}
	
?>

<?php include_once("base/footer.php");?>