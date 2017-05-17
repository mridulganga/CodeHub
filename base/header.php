<?php include_once("misc.php");?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Codio</title>

    <!-- Bootstrap core CSS -->
    <link href="base/css/bootstrap.min.css" rel="stylesheet">
    <link href="base/css/style.css" rel="stylesheet">
  </head>
  <body role="document">

<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

  	<!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"  style="margin-right:20px; font-size:1.5em;">Codio.in</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="submit-code.php" title="Submit Code" ><span class="glyphicon glyphicon-edit"></span> Submit</a></li>
            <li><a href="code.php?op=viewall"  title="View Code"><span class="glyphicon glyphicon-console"></span> View</a></li>
            <li><a href="run.php"  title="Run Code"><span class="glyphicon glyphicon-play"></span> Run</a></li>
          </ul>

          <ul class="nav navbar-nav navbar-right">
          	<li>
          		<form role="form" class="navbar-form" action="code.php" method="get" style="width:100%;">
                      <input type="hidden" name="op" value="search">
                      <input type="text" class="form-control" placeholder="Search " name="q" >
          		</form>
          	</li>

            <li class="dropdown" style="float:left; margin-right:10px;">
              <a class="dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="glyphicon glyphicon-th-large" ></span>&nbsp;</a>
              <ul class="dropdown-menu">
                <li><a href="code.php?op=viewall"><span class="glyphicon glyphicon-console"></span>&nbsp;View</a></li>
                <li><a href="submit-code.php"><span class="glyphicon glyphicon-edit"></span>&nbsp;Submit</a></li>
                <li><a href="run.php"><span class="glyphicon glyphicon-play"></span>&nbsp;Run</a></li>
              </ul>
            </li>  

          	<?php
            if (isset($_SESSION["name"]))
            {echo "<li class='dropdown'>";
             echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>";
              echo $_SESSION["name"];
              echo "<span class='caret'></span></a>";
              echo "<ul class='dropdown-menu navbar-right'>";
              echo "<li><a href='user.php?op=profile'>Profile</a></li>";
              echo "<li><a href='user.php?op=submissions'>My Submissions</a></li>";
              //echo "<li><a href='#'>My Favorites</a></li>";
              echo "<li role='separator' class='divider'></li>";
              echo "<li class='dropdown-header'>Current Session</li>";
              echo "<li><a href='user.php?op=logout'>Logout</a></li>";
              echo "</ul>";
              echo "</li>";
            }
            else
            {
              echo '<li><form role="form" class="navbar-form" action="user.php" method="get">';
              echo '<input type="hidden" name="op" value="login">
                    <div class="input-group">';
              echo '<input type="submit" value="Login" class="btn btn-primary">';
              echo '</div></form></li>';
            }
            ?>
          </ul>

        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <br><br><br>

<div class="container">
<div>
<!-- Split this page from here-->
