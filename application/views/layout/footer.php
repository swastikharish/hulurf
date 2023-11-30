    </main>
    
    <!-- Main Footer -->
    <footer class="main-footer pb-3">
      <div class="container">
        <div class="f-widget">
          <div class="f-logo text-center mb-4">
            <img src="<?php echo $assets_path; ?>/theme/images/logo-white.png" alt="logo" class="img-fluid">
          </div>
          <div class="social-icon">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa-brands fa-facebook-f"></i></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa-brands fa-linkedin"></i></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa-solid fa-envelope"></i></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa-solid fa-phone"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
    <!-- END Main Footer -->

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <script src="<?php echo $assets_path; ?>/theme/js/owl.carousel.min.js" type="text/javascript"></script>
  <script src="<?php echo $assets_path; ?>/theme/js/kit.fontawesome.js" type="text/javascript"></script>
  <script src="<?php echo $assets_path; ?>/theme/js/custom.js" type="text/javascript"></script>

<?php
if (count($this->layout_scripts) > 0)  {
	for($i=0; $i < count($this->layout_scripts); $i++) {
?>
  <script src="<?php echo $assets_path.'/theme/js/'.$this->layout_scripts[$i]; ?>" type="text/javascript"></script>
<?php
	}
}
?>
    
  </body>
</html>