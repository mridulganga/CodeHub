<?php include_once("base/header.php");?>

<?php echo titlePrint("Submit Code");?>

<?php
if ($_SERVER['REQUEST_METHOD']==="POST"){
	
	$code_title = $_POST['code_title'];
	$code_lang = $_POST['lang'];
	$usage = $_POST['usage'];
	$code = $_POST['code'];
	$in = $_POST['in'];
	$out = $_POST['out'];
	$tags= $_POST['tags'];
	$a_name = $_POST['author_name'];
	$a_email = $_POST['author_email'];
	$in_out = "Input:\n".$in."\n\nOutput:\n".$out;

	if ($code_title === "")
		showError("Title can't be left Empty");
	elseif ($usage === "")
		showError("Usage can't be left Empty");
	elseif ($code==="") 
		showError("Code Can't be left Empty");
	elseif ($a_name==="") 
		showError("Author Name Can't be left Empty");
	elseif ($a_email==="")
		showError("Author Email Can't be left Empty");
	else
	{
		if(insertCode($code_title,$code_lang,$usage,$code,$in_out,$tags,$a_name,$a_email)===TRUE){
			$url = "code.php?op=single&id=".getCodeID($code_title,$a_email,$a_name,$code_lang);
			redirect($url);
		}
	}
}
function showError($error){
echo '<div class="alert alert-warning alert-dismissible" role="alert"><p>';
echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
echo $error;
echo '</p></div>';
}
?>
<style>
.input-group-addon{}
</style>

<form method="post" action="submit-code.php" style="max-width:800px; margin:0 auto;">
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon1">Title</span>
  		<input type="text" class="form-control" placeholder="Code Title" aria-describedby="basic-addon1" name="code_title">
	</div><br>
	<div class="input-group input-group-lg">
		<span class="input-group-addon" id="basic-addon2">Language</span>
		<select name="lang" class="form-control" aria-describedby="basic-addon2">
			<option value="Arduino">Arduino</option>
			<option value="JAVA">Java</option>
			<option value="CPP">C++</option>
			<option value="C">C</option>
			<option value="CSHARP">C#</option>
			<option value="PHP">PHP</option>
			<option value="PYTHON">Python</option>
			<option value="html">HTML</option>
			<option value="css">CSS</option>
			<option value="vb.net">VB.NET</option>
			<option value="vb6">VB6</option>
			<option value="PERL">Perl</option>
			<option value="JAVASCRIPT">Java Script</option>
			<option value="xml">XML</option>
			</select>
	</div><br>
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon3">Usage</span>
  		<textarea name="usage" class="form-control" aria-describedby="basic-addon3" style="min-height:150px;" placeholder="What is the use of this code and how to use it?"></textarea>
	</div><br>
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon4">Code</span>
  		<textarea name="code" class="form-control" aria-describedby="basic-addon4" style="min-height:350px;" placeholder="The code itself!"></textarea>
	</div><br>
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon3">Input</span>
  		<textarea name="in" class="form-control" aria-describedby="basic-addon3" style="min-height:150px;" placeholder="The Input this code will take"></textarea>
	</div><br>
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon3">Output</span>
  		<textarea name="out" class="form-control" aria-describedby="basic-addon3" style="min-height:150px;" placeholder="The Output this code will give"></textarea>
	</div><br>
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon1">Tags</span>
  		<input type="text" class="form-control" aria-describedby="basic-addon1" name="tags" placeholder="Related terms to make it searchable">
	</div><br>
<?php
	if (isset($_SESSION["name"])){
		echo '<input type="hidden" name="author_name" value="'.$_SESSION["name"].'">';
		echo '<input type="hidden" name="author_email" value="'.$_SESSION["email"].'">';
	}
	else{
	echo '<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon1">Author Name</span>
  		<input type="text" class="form-control" placeholder="" aria-describedby="basic-addon1" name="author_name">
	</div><br>
	<div class="input-group input-group-lg">
  		<span class="input-group-addon" id="basic-addon1">Author Email</span>
  		<input type="email" class="form-control" placeholder="" aria-describedby="basic-addon1" name="author_email">
	</div><br>';
	}

?><br><br>
			<input type="submit" value="Submit Code" class="btn btn-lg btn-primary">
			<input type="reset" value="Clear" class="btn btn-lg btn-default">
</form>


<?php include_once("base/footer.php");?>
