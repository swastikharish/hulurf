<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8" />
  <title><?php echo $app_name; ?></title>
  <!-- <link rel="shortcut icon" href="<?php echo $app_path; ?>assets/admin/svg/m-icon.svg" /> -->
  <meta name="description" content="Meal prep, workouts, accountability, and community all in one program to help you reach your goals." />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/assets/animate.css/animate.css" type="text/css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/fontawesome-5/css/all.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/assets/simple-line-icons/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/jquery/toastr/toastr.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/jquery/select2/css/select2.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/jquery/select2/css/select2-bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/jquery/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/lightbox/dist/css/lightbox.min.css" type="text/css" />

  <?php if(isset($plp_styles) && count($plp_styles)){ ?>
  <?php for($i=0; $i < count($plp_styles); $i++){ ?>
  <link href="<?php echo $plp_styles[$i]; ?>" rel="stylesheet" type="text/css" />
  <?php } ?>      
  <?php } ?>

  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/css/font.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/css/app.css?vkmf=<?php echo time(); ?>" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/css/loader.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/css/custom.css?vkmf=<?php echo time(); ?>" type="text/css" />
  
  <script><!--
  var app_path                = '<?php echo $app_path; ?>';
  var admin_folder            = '<?php echo $this->admin_folder; ?>';
  --></script>
</head>

<body>
<div class="app app-header-fixed ">

  <!-- header -->
  <header id="header" class="app-header navbar" role="menu">
    <!-- navbar header -->
    <div class="navbar-header bg-dark">
      <button class="pull-left visible-xs" ui-toggle-class="off-screen" target=".app-aside" ui-scroll="app">
        <i class="glyphicon glyphicon-align-justify"></i>
      </button>           
      <!-- brand -->
      <a href="<?php echo site_url($this->admin_folder.'dashboard'); ?>" class="navbar-brand text-lt text-center">
        Admin Panel
        <!-- <img src="<?php echo $app_path; ?>assets/admin/svg/m-icon.svg" alt="MFOB" class="visible-folded m-icon">
        <img src="<?php echo $app_path; ?>assets/admin/svg/macro-word-white.svg" alt="MFOB" class="hidden-folded m-logo"> -->
      </a>
      <!-- / brand -->
      <button class="pull-right visible-xs dk" ui-toggle-class="show" target=".navbar-collapse">
        <i class="glyphicon glyphicon-cog"></i>
      </button> 
    </div>
    <!-- / navbar header -->

    <!-- navbar collapse -->
    <div class="collapse pos-rlt navbar-collapse box-shadow bg-white-only">
      <!-- buttons -->
      <div class="nav navbar-nav hidden-xs">
        <a href="#" class="btn no-shadow navbar-btn" ui-toggle-class="app-aside-folded" target=".app">
          <i class="fa fa-dedent fa-fw text"></i>
          <i class="fa fa-indent fa-fw text-active"></i>
        </a>
      </div>
      <!-- / buttons -->

      <form name="header_customer_search_form" action="<?php echo site_url($this->admin_folder.'users'); ?>" class="header-customer-search-form navbar-form navbar-form-sm navbar-left shift" id="header-customer-search-form">
        <div class="form-group">
          <div class="input-group">
            <input type="text" name="q" class="form-control input-sm bg-light no-border rounded padder w-xl" placeholder="Search user by name or email">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-sm bg-light rounded"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </div>
      </form>

      <!-- nabar right -->
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" data-toggle="dropdown" class="dropdown-toggle clear" data-toggle="dropdown">
            <span class="thumb-sm avatar avatar-text pull-right m-t-n-sm m-b-n-sm m-l-sm">
              <?php echo strtoupper($this->admin_session_data['name'][0]); ?>
              <!-- <img src="<?php echo $app_path; ?>assets/admin/svg/m-icon.svg" alt="<?php echo $this->admin_session_data['name']; ?>"> -->
              <i class="on md b-white bottom"></i>
            </span>
            <span class="hidden-sm hidden-md"><?php echo $this->admin_session_data['name']; ?></span> <b class="caret"></b>
          </a>
          <!-- dropdown -->
          <ul class="dropdown-menu w">            
            <li>
              <a href="<?php echo site_url($this->admin_folder.'users');?>">Users</a>
            </li>
            <li>
              <a href="<?php echo site_url($this->admin_folder.'logout');?>">Logout</a>
            </li>
          </ul>
          <!-- / dropdown -->
        </li>
      </ul>
      <!-- / navbar right -->
    </div>
    <!-- / navbar collapse -->
  </header>
  <!-- / header -->

  <!-- aside -->
  <aside id="aside" class="app-aside hidden-xs bg-dark">
    <div class="aside-wrap">
      <div class="navi-wrap">
        <!-- nav -->
        <nav ui-nav class="navi clearfix">
          <ul class="nav">
            
            <li class="<?php echo ($this->menu == 'dashboard') ? 'active' : ''; ?>">
              <a href="<?php echo site_url($this->admin_folder.'dashboard'); ?>">
                <i class="glyphicon glyphicon-stats icon"></i>
                <span class="font-bold">Dashboard</span>
              </a>
            </li>

            <li class="<?php echo ($this->menu == 'users') ? 'active' : ''; ?>">
              <a href="<?php echo site_url($this->admin_folder.'users'); ?>">
                <i class="fa fa-users"></i>
                <span class="font-bold">Users</span>
              </a>
            </li>

            <li class="<?php echo ($this->menu == 'page') ? 'active' : ''; ?>">
              <a href class="auto">      
                <span class="pull-right text-muted">
                  <i class="fa fa-fw fa-angle-right text"></i>
                  <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="glyphicon glyphicon-edit"></i>
                <span class="font-bold">CMS</span>
              </a>
              <ul class="nav nav-sub dk">
                <li class="<?php echo ($this->menu == 'page') ? 'active' : ''; ?>">
                  <a href="<?php echo site_url($this->admin_folder.'pages'); ?>">
                    <!-- <i class="fa fa-file icon"></i> -->
                    <span class="font-bold">Pages</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="<?php echo ($this->menu == 'forum') ? 'active' : ''; ?>">
              <a href class="auto">      
                <span class="pull-right text-muted">
                  <i class="fa fa-fw fa-angle-right text"></i>
                  <i class="fa fa-fw fa-angle-down text-active"></i>
                </span>
                <i class="fa fa-forumbee"></i>
                <span class="font-bold">Contents</span>
              </a>
              <ul class="nav nav-sub dk">
                <li class="<?php echo ($this->submenu == 'category') ? 'active' : ''; ?>">
                  <a href="<?php echo site_url($this->admin_folder.'categories'); ?>">
                    <span class="font-bold">Categories</span>
                  </a>
                </li>
                <li class="<?php echo ($this->submenu == 'forum') ? 'active' : ''; ?>">
                  <a href="<?php echo site_url($this->admin_folder.'forums'); ?>">
                    <span class="font-bold">Forums</span>
                  </a>
                </li>
                <li class="<?php echo ($this->submenu == 'topic') ? 'active' : ''; ?>">
                  <a href="<?php echo site_url($this->admin_folder.'topics'); ?>">
                    <span class="font-bold">Topics</span>
                  </a>
                </li>
              </ul>
            </li>
            <li class="<?php echo ($this->menu == 'setting') ? 'active' : ''; ?>">
              <a href="<?php echo site_url($this->admin_folder.'setting'); ?>">
                <i class="glyphicon glyphicon-cog icon"></i>
                <span class="font-bold">Settings</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- nav -->
      </div>
    </div>
  </aside>
  <!-- / aside -->
  
  <!-- content -->
  <div id="content" class="app-content" role="main">
    <div class="app-content-body ">