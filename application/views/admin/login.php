<!DOCTYPE html>
<html lang="en" class="">
  <head>
    <meta charset="utf-8" />
    <title><?php echo $app_name; ?> Login</title>
    <!-- <link rel="shortcut icon" href="<?php echo $app_path; ?>assets/admin/svg/m-icon.svg" /> -->
    <meta name="description" content="Meal prep, workouts, accountability, and community all in one program to help you reach your goals." />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/assets/animate.css/animate.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/assets/font-awesome/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/assets/simple-line-icons/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />

    <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/css/font.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $app_path; ?>assets/admin/css/app.css" type="text/css" />

    <script><!--
    var app_path = '<?php echo $app_path; ?>';
    var admin_folder = '<?php echo $this->admin_folder; ?>';
    --></script>

  </head>
  <body>
    <div class="app app-header-fixed ">
      <div class="container w-xxl w-auto-xs">
        <p class="text-center block m-b">
          <a href="javascript:void(0);">
            <img class="logo-login img-responsive" src="<?php echo $app_path; ?>assets/admin/img/logo.png" alt="<?php echo $app_name; ?>">
          </a>  
        </p>

        <div class="m-b-lg">
          <form name="login_form" class="login-form" id="login-form">
            <?php if($this->error_message != NULL){ ?>
            <div class="alert alert-danger">            
              <strong>Error!</strong> <?php echo $this->error_message; ?>
            </div>
            <?php } ?>
            <?php if($this->warning_message != NULL){ ?>
            <div class="alert alert-warning">            
              <strong>Warning!</strong> <?php echo $this->warning_message; ?>
            </div>
            <?php } ?>
            <?php if($this->info_message != NULL){ ?>
            <div class="alert alert-info">            
              <strong>Info!</strong> <?php echo $this->info_message; ?>
            </div>
            <?php } ?>
            <?php if($this->success_message != NULL){ ?>
            <div class="alert alert-success">            
              <strong>Success!</strong> <?php echo $this->success_message; ?>
            </div>
            <?php } ?>

            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">

            <div class="alert alert-danger form-error-area hide">            
              <strong>Error!</strong> <span></span>
            </div>
            
            <div class="form-group">
              <input type="email" name="a_email" placeholder="Email" class="input-lg form-control" required>
            </div>

            <div class="form-group">
              <input type="password" name="a_password" placeholder="Password" class="input-lg form-control" required>
            </div>
            
            <button type="submit" id="button-login" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Loading..." class="btn btn-lg btn-info btn-block">Login</button>
          </form>
        </div>
        <div class="text-center">
          <p><small class="text-muted"><?php echo $app_name; ?> &copy; <?php echo date('Y'); ?> Copyright.</small></p>
        </div>
      </div>
    </div>

    <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery/dist/jquery.js"></script>
    <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
    <script src="<?php echo $app_path; ?>assets/admin/js/ui-load.js"></script>
    <script src="<?php echo $app_path; ?>assets/admin/js/ui-jp.config.js"></script>
    <script src="<?php echo $app_path; ?>assets/admin/js/ui-jp.js"></script>
    <script src="<?php echo $app_path; ?>assets/admin/js/ui-nav.js"></script>
    <script src="<?php echo $app_path; ?>assets/admin/js/ui-toggle.js"></script>
    <script src="<?php echo $app_path; ?>assets/admin/js/ui-client.js"></script>

    <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>

    <script src="<?php echo $app_path; ?>assets/admin/js/login.js?v=<?php echo time();?>" type="text/javascript"></script>

  </body>
</html>
