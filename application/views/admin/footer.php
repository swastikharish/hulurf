      </div>
    </div>
    <!-- /content -->
    
    <!-- footer -->
    <footer id="footer" class="app-footer" role="footer">
      <div class="wrapper b-t bg-light">
        <?php echo $app_name; ?> &copy; <?php echo date('Y')?> Copyright.
      </div>
    </footer>
    <!-- / footer -->

  </div>

  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery/dist/jquery.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery-ui/jquery-ui.min.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/js/ui-load.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/js/ui-jp.config.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/js/ui-jp.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/js/ui-nav.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/js/ui-toggle.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/js/ui-client.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/js/moment.min.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/toastr/toastr.min.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/select2/js/select2.full.min.js"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/lightbox/dist/js/lightbox.min.js" type="text/javascript"></script>  

  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery-inputmask/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/bootbox/bootbox.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery.blockui.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/heic2any/dist/heic2any.min.js" type="text/javascript"></script>
  <script src="<?php echo $app_path; ?>assets/admin/libs/jquery/jquery-sortable-lists/jquery-sortable-lists.min.js" type="text/javascript"></script>
  
  <?php if(isset($plp_scripts) && count($plp_scripts)){ ?>
  <?php for($i=0; $i < count($plp_scripts); $i++){ ?>
  <script src="<?php echo $plp_scripts[$i]; ?>" type="text/javascript"></script>
  <?php } ?>      
  <?php } ?>

  <script src="<?php echo $app_path; ?>assets/admin/js/admin-function.js?vkref=<?php echo time(); ?>" type="text/javascript"></script>

  <?php if(isset($scripts) && count($scripts)){ ?>
  <?php for($i=0; $i < count($scripts); $i++){ ?>
  <script src="<?php echo $scripts[$i].'?vkref='.time(); ?>" type="text/javascript"></script>
  <?php } ?>      
  <?php } ?>
    
  </body>
</html>