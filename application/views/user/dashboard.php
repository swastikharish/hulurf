
    <!-- Shopby Category -->
    <section class="shopby-category-wrapper">
      <div class="container">
        <div class="">
          <h2 class="mb-4">Dashboard</h2>
        </div>
        
      </div>
    </section>
    <!-- END  Shopby Category -->
    <section class="profie-wrapper">
      <div class="container">
        <div style="min-height: 400px;"></div>
        <div class="row d-none">
          <div class="col-md-4">
            <div class="profile-widget position-relative">
              <div class="profile-img" style="background-image: url(<?php echo site_url('assets/theme/images/profile.png'); ?>);"></div>
                <div class="profile-edit position-absolute">
                  <label for="UserProfile">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                      <path
                      d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z" />
                    </svg>
                  </label>
                  <input type="file" id="UserProfile">
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="profile-form">
              <form action="">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label>First Name</label>
                      <input type="text" class="form-control" value="Ashish" placeholder="First Name">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label>Last Name</label>
                      <input type="text" class="form-control" value="Sahu" placeholder="Last Name">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label>Mobile Number</label>
                      <input type="number" class="form-control" value="9900990099" placeholder="Number">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label>Old Password</label>
                      <input type="Password" class="form-control" placeholder="Old Password">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label>Change Password</label>
                      <input type="Password" class="form-control" placeholder="New Password">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <label>Re Password</label>
                      <input type="Password" class="form-control" placeholder="Change Password">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-4">
                      <button class="btn custom-btn" type="submit">Save</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="activity-wrapper d-none">
      <div class="container">
        <div class="activity-heading">
          <h4>Courses</h4>
        </div>
        <div class="activity-widget">
          <div class="product-card p-0 pe-3">
            <div class="row">
              <div class="col-lg-2 product-img">
                <img class="img-fluid" src="<?php echo site_url('assets/theme/images/shop/gallery2.jpg'); ?>" alt="">
              </div>
              <div class="col-lg-8 product-content pe-5 py-3">
                <h3><a href="#">Very Profitable Used Car Operation</a></h3>
                <p class="description m-0">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                  Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
              </div>
              <div class="col-lg-2 product-action py-3">
                <h5 class="price">$2,250,000</h5>
                <a href="product-detail.html" class="btn custom-btn green_btn mt-2">View more</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
