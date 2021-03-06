<?php
  if(!isset($_SESSION['user']) && !(strpos($_SERVER['PHP_SELF'], 'index.php') || strpos($_SERVER['PHP_SELF'], 'register.php') ) ) {
    header( 'Location: index.php' );
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>GeekSoc Account System <?php echo $pageTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">GeekSoc Account System</a>
          <div class="nav-collapse">
            <!-- <ul class="nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul> -->
            <?php if(isset($_SESSION['user'])) : ?>
                <div class="btn-group pull-right">
                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                  <i class="icon-user"></i> <?php echo $_SESSION['user']; ?>
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <!-- <li><a href="#">Profile</a></li>
                    <li class="divider"></li> -->
                    <li><a href="logout.php">Sign Out</a></li>
                  </ul>
                </div>
            <?php endif; ?>
            <?php if ( isset($_SESSION['user']) && (isUserInGroup($con, $user, "gsag")) ) : ?>
                <form class="navbar-search pull-right" action="listusers.php" method='GET'>
                    <input type="text" name="search" class="search-query span2" placeholder="Search Users">
                </form>
            <?php endif; ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
