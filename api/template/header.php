<?php 

if (isset($_POST["logout"])) {
  session_destroy();
  header("Location: index.php");
}

?>
<!--Header-->
<header>
    <div class="container">
      <div class="row">
      
      <div class="col-lg-7 col-md-5 col-sm-3">
          <p><a href="#">your website address</a></p>
      </div>
      <div class="col-lg-5 col-md-4 col-sm-3 col-6">
          <div class="icons">
              <div class="social-icons">
                  <a href="#"><i class="fa fa-facebook-official fa-lg" aria-hidden="true"></i></a>
                  <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                  <a href="#"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
                  <a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a>
              </div>
           </div>
       </div>
      </div>
        <!--Row-End-->
    </div>
    <!--Container-End-->
</header>
<!--Header-End-->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
 <div class="container">
  <a class="navbar-brand" href="index.php"><img src="template/image/logo.png" alt="logo"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li><a class="hidden-xs">/</a></li>
      <?php if(isset($_SESSION['username'])):?>
        <form action="" method="post">
        <li class="nav-item"><button class="nav-link" style="background: none!important;border: none;cursor: pointer;" type="submit" name="logout">Logout</button></li>
        </form>
        
      <?php endif ?> 
    </ul>
  </div>
 </div>
</nav>
<!--Nav-->


<!--Banner-Section-->

<!--Banner-Section-End-->