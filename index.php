<?php require_once('lib/news.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" type="text/css" href="css/style.css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">
 
    <!-- Include Editor style. -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.6.0/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.6.0/css/froala_style.min.css" rel="stylesheet" type="text/css" />
  </head>

</head>
<body>

<?php include('include/header.php'); ?>
<div class="container">    
  <div class="col-sm-9">
    <h4><small>ОСТАННІ ПОДІЇ</small></h4>
     <div class="content"><?php include('include/content.php'); ?>
       <script>
document.getElementById('shareBtn').onclick = function() {
  FB.ui({
    method: 'share',
    display: 'popup',
    href: 'https://developers.facebook.com/docs/',
  }, function(response){});
}
</script>
     </div>
   
</div>
    <div class="bordera col-sm-3">
    <h4><small>ПОПУЛЯРНІ ПОДІЇ</small></h4>
    <hr>
    <div class="sidebar"><?php include('include/sidebar.php'); ?></div>
    </div>
</div><br>
        
        </div>
        <div class="footer"><?php include('include/footer.php'); ?></div>





</body>
</html>
