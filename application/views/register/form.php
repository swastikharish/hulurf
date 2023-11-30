
<a id="signup-start"></a>
<section class="signup-section mb-5">
  <div class="container">
    <h3 class="text-center mt-5 mb-4 global-heading">Signup</h3>
    <div class="row">
      <div class="col-md-12 col-lg-8 mx-auto">
        <form class="signup_form" id="signup-form" method="post">
          <div class="alert alert-danger d-none">            
            <span>You have some form errors. Please check below.</span>
          </div>
          <div class="alert alert-success d-none">
            <span>Your form validation is successful!</span>
          </div>          
          <div id="account-infromation">
            <div class="form-group">
              <label for="form-control-name" class="form-label">Your Full Name</label>
              <input type="text" class="form-control" id="form-control-name" name="name" placeholder="Enter Your Full Name">
            </div>
            <div class="form-group">
              <label for="form-control-phone" class="form-label">Your Phone Number</label>
              <input type="text" class="form-control mask-phone" id="form-control-phone" name="phone" placeholder="Enter Your Phone Number" maxlength="10">
            </div>
            <div class="form-group">
              <label for="form-control-email" class="form-label">Your Email</label>
              <input type="email" class="form-control" id="form-control-email" name="email" placeholder="Enter Your Email">
              <div class="invalid-feedback">This email already has an account. Please click here to <a href="<?php echo site_url('login'); ?>">login</a>.</div>
            </div>
            <div class="form-group">
              <label for="form-control-password" class="form-label">Password</label>
              <input type="password" class="form-control" id="form-control-password" name="password" placeholder="Enter Password">
            </div>            
            <div class="form-group text-center">
              <button type="submit" id="signup-form-save-button" class="btn submit_btn custom-btn">Create Account</button>
            </div>            
          </div>
          <hr>          
          <div class="login_text mt-3 text-center text-md-start">
            Already have an account? <a href="<?php echo site_url('/login'); ?>">Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
