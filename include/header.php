
    <div class="heada jumbotron">
  <div class="container text-center">
    <h1>Останні новини</h1>      
    <p>Останні новини тільки для вас!</p>
  </div>
</div>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">

        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>  

      </button>
      <a class="navbar-brand" href="#">Logo</a>
    </div>

    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
       <!-- <li><a href="Z:/home/kr/www/index.php">Home</a></li>-->
        <?php echo print_categories(); ?>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Your Account</a></li>
      </ul>
    </div>
  </div>
</nav>

