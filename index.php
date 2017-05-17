<?php include_once("base/header.php");?>

<style>
body{background:<?php echo getSetting("index_bg");?>; color:white;}
.panel{color:black; border:none;}
.alert-info{background:<?php echo getSetting("index_footer_bg");?>; color:white; border:0; padding:20px; margin-top:50px;}
.alert-info  a {color:white; text-decoration: none;}

 .panel-index .panel-heading{color:#fff; background-color:<?php echo getSetting("index_panel_heading");?>!important;}
 .panel-index .panel-heading .badge{color:#3d174d;background-color:#fff}

 .btn-primary{color:#fff;background-color:<?php echo getSetting("index_button");?>;border:none;}
 .btn-primary.focus,.btn-primary:focus{color:#fff;background-color:<?php echo getSetting("index_button_hover");?>;}
 .btn-primary:hover{color:#fff;background-color:<?php echo getSetting("index_button_hover");?>;}
 .btn-primary.active,.btn-primary:active,.open>.dropdown-toggle.btn-primary{color:#fff;background-color:<?php echo getSetting("index_button_active");?>;}

 .btn-primary .badge{color:#337ab7;background-color:#fff}
 nav {display:none;}

 div.fixed {
    position: fixed;
    top: 20;
    right: 80;

    border: 0px solid #73AD21;
    padding:5px;
}
</style>


<br>
<div class="fixed">
                <a href="code.php?op=viewall"><span class="glyphicon glyphicon-console"></span>&nbsp;View</a></li>
                <a href="submit-code.php"><span class="glyphicon glyphicon-edit"></span>&nbsp;Submit</a></li>
                <a href="run.php"><span class="glyphicon glyphicon-play"></span>&nbsp;Run</a>



             <?php if (isset($_SESSION["name"]))
            {echo "<div class='dropdown' style='float:right;'>";
             echo "<a  class='btn-lg btn btn-primary' href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>";
              echo $_SESSION["name"];
              echo "<span class='caret'></span></a>";
              echo "<ul class='dropdown-menu'>";
              echo "<li><a href='user.php?op=profile'>Profile</a></li>";
              echo "<li><a href='user.php?op=submissions'>My Submissions</a></li>";
              //echo "<li><a href='#'>My Favorites</a></li>";
              echo "<li role='separator' class='divider'></li>";
              echo "<li class='dropdown-header'>Current Session</li>";
              echo "<li><a href='user.php?op=logout'>Logout</a></li>";
              echo "</ul>";
              echo "</div>";
            }
            else
            {
              echo '<div style="float:right;"><form role="form" class="form" action="user.php" method="get">';
              echo '<input type="hidden" name="op" value="login">
                    <div class="input-group">';
              echo '<input type="submit" value="Login" class="btn btn-primary btn-lg">';
              echo '</div></form></div>';
            }
            ?>
</div>
<div class="page-header" style="border:none; padding-top:30px;">

  <center><img src="base/pics/codio.png" width=50%></center>
</div>

<div class="col-md-12" style="padding-bottom:80px;">
<div class="col-md-8" style="margin:0 auto; float:none;">

<form role="form" class="form" action="code.php" method="get" style="width:100%;">
  <input type="hidden" name="op" value="search">

   <div class="input-group input-group-lg">
      <input type="text" class="form-control" placeholder="Search code..." name="q">
      <span class="input-group-btn">
        <input type="submit" value="Search" class="btn btn-primary">
      </span>
    </div><!-- /input-group -->
</form>
</div>

</div>
</div>

<footer class="col-md-12">
<div class="" style="width:60%; position:fixed; bottom:10%; left:20%; padding:10px; background-color:#fff;color:<?php echo getSetting("index_button");?>;border:none;" role="alert"><p style="margin:0;">© Copyright 2016, Codio. All rights reserved.

</p></div><br><br>
</footer>
</div>


<script src="base/js/jquery.js"></script>
<script src="base/js/typed.js"></script>
<script>
  $(function(){
      $("#element").typed({
        strings: [
        "\“Talk is cheap. Show me the code.\”",
        "\"when you don't create things, you become defined by your tastes rather than ability. your tastes only narrow and exclude people. so create.\"",
        "\“Programs must be written for people to read, and only incidentally for machines to execute.\”",
        "\“Programming today is a race between software engineers striving to build bigger and better idiot-proof programs, and the Universe trying to produce bigger and better idiots. So far, the Universe is winning.\”",
        "\“Always code as if the guy who ends up maintaining your code will be a violent psychopath who knows where you live\”",
        "\“That\'s the thing about people who think they hate computers. What they really hate is lousy programmers.\” ",
        "\“I\'m not a great programmer; I'm just a good programmer with great habits.\”",
        "\“A language that doesn't affect the way you think about programming is not worth knowing.\”",
        "\“Walking on water and developing software from a specification are easy if both are frozen.\” ",
        "\“Life is like programming. We fail to compile, because realization comes from warnings and success comes from experience.\”"

        ],
        typeSpeed: 10,
        loop:true
      });
  });
</script>
<style>
@media screen and (max-width: 600px) {
  #element {
    visibility: hidden;
    clear: both;
    float: left;
    margin: 10px auto 5px 20px;
    width: 28%;
    display: none;
  }
}
</style>
<div id="element" style="float:left; position:absolute; top:20px; left:30px; font-size:1.12em; width:350px;" ></div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="base/js/bootstrap.min.js"></script>

  </body>
  </html>
