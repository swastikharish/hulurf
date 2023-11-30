
    <section class="signup-section mb-5">
      <div class="container">
        <h3 class="text-center mt-5 mb-4 global-heading">Login</h3>
        <div class="row">
          <div class="col-md-12 col-lg-8 mx-auto">
            <form class="signup_form" id="login-form">
              <div class="alert alert-danger d-none">            
                <span>You have some form errors. Please check below.</span>
              </div>
              <div class="alert alert-success d-none">
                <span>Your form validation is successful!</span>
              </div>
              <div class="form-group">
                <label for="form-control-email" class="form-label">Your Email</label>
                <input type="email" name="email" class="form-control" id="form-control-email" placeholder="Enter Your Email*">
              </div>
              <div class="form-group">
                <label for="form-control-password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="form-control-password" placeholder="Enter Password*">
              </div>              
              <div class="form-group text-center">
                <button type="submit" id="login-button" class="btn submit_btn custom-btn">Login</button>
              </div>
              <hr>
              <div class="justify-content-between d-flex flex-column text-center text-md-start">
                <a href="<?php echo site_url('/signup'); ?>">No account yet? Signup today!</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- END Signup Section -->