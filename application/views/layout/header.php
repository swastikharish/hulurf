<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8" />
  <title><?php echo $meta_title; ?> | <?php echo $page_name; ?></title>

  <!-- Favicon -->
  <!-- <link rel="shortcut icon" type="image/icon" href="<?php echo $assets_path; ?>/theme/images/favicon.ico.png"/> -->

  <meta name="description" content="<?php echo $meta_description; ?>">
  <meta name="keywords" content="<?php echo $meta_keyword; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  
  <!-- CSS ================================================== -->
  <!-- Bootstrap -->
  <link href="<?php echo $assets_path; ?>/theme/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- style css-->
  <link href="<?php echo $assets_path; ?>/theme/css/owl.carousel.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
  <!-- style css-->
  <link href="<?php echo $assets_path; ?>/theme/css/style.css?vkcss=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
  <link href="<?php echo $assets_path; ?>/theme/css/custom.css?vkcss=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
  <!-- Responsive css-->
  <link href="<?php echo $assets_path; ?>/theme/css/responsive.css" rel="stylesheet" type="text/css" />

  <?php
  if (count($this->layout_styles) > 0)  {
  	for($i=0; $i < count($this->layout_styles); $i++) {
  ?>
    <link href="<?php echo $assets_path.'/theme/css/'.$this->layout_styles[$i]; ?>" rel="stylesheet">
  <?php
  	}
  }
  ?>

  <script>
    var app_path = '<?php echo $app_path; ?>';
  </script>

  <script type="application/json" class="js-hypothesis-config">
    {
      "showHighlights": true
    }
  </script>
  <script async src="https://hypothes.is/embed.js"></script>
  
</head>

<body> 

    <!-- Main Header -->
    <header class="header fixed-top">
      <div class="header-first-menu">
        <div class="container d-flex justify-content-between align-items-center">
          <ul class="left-nav logo-header">
            <li><a href="<?php echo site_url('/home'); ?>"><img src="<?php echo $assets_path; ?>/theme/images/logo.png" class="img-fluid logo" alt=""></a></li>
          </ul>

<?php if ($this->user_session_data) { ?>

          <ul class="right-nav user-logined">
            <li><a href="<?php echo site_url('/forum'); ?>">Forum</a></li>
            <li><a href="<?php echo site_url('/about'); ?>">About</a></li>
            <li><a href="<?php echo site_url('/contact'); ?>">Contact</a></li>
          
            <li class="child-menu"><a href="javascript:void(0)" class="user"><i class="fa-solid fa-user"></i> <svg
                  xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512" class="caret-down">
                  <path
                    d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z" />
                </svg></a>
              <div class="dropdown_list">
                <ul class="dropdown">
                  <li><a href="<?php echo site_url('/dashboard'); ?>">Dashboard</a></li>
                  <li><a href="<?php echo site_url('/logout'); ?>">Logout</a></li>
                </ul>
              </div>
            </li>
          </ul>
          <div class="mobile_menu_icon">
            <li class="child-menu"><a href="javascript:void(0)" class="user"><i class="fa-solid fa-user"></i> <svg
                  xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512" class="caret-down">
                  <path
                    d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z" />
                </svg></a>
              <div class="dropdown_list">
                <ul class="dropdown">
                  <li><a href="<?php echo site_url('/dashboard'); ?>">Dashboard</a></li>
                  <li><a href="<?php echo site_url('/logout'); ?>">Logout </a></li>
                </ul>
              </div>
            </li>
            <div id="nav-icon2" class="menu-toggler">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
          </div>

<?php } else { ?>

          <ul class="right-nav signin-btn-nav">
            <li><a href="<?php echo site_url('/forum'); ?>">Forum</a></li>
            <li><a href="<?php echo site_url('/about'); ?>">About</a></li>
            <li><a href="<?php echo site_url('/contact'); ?>">Contact</a></li>
            <li class="signin-btn"><a href="<?php echo site_url('/signup'); ?>">Signup</a></li>
            <li class="signin-btn"><a href="<?php echo site_url('/login'); ?>">Login</a></li>
            <div class="mobile_menu_icon">
              <li class="signin-m-btn"><a class="ms-0" href="<?php echo site_url('/login'); ?>">login</a></li>
              <li class="signin-m-btn"><a class="ms-0" href="<?php echo site_url('/signup'); ?>">Signup</a></li>
              <div id="nav-icon2" class="menu-toggler">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
              </div>
            </div>
          </ul>

<?php } ?>

        </div>
      </div>
    </header>
    <!-- END Main Header -->

    <main class="page-body">