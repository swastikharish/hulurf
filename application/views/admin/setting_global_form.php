<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left">Shop Information</h1>
    <a href="<?php echo site_url($this->admin_folder.'setting'); ?>" class="btn btn-sm btn-default pull-right"> <i class="fa fa-arrow-left"></i> Back</a>
  </div>
</div>
<div class="wrapper-md">
  <div class="panel panel-default">
    <form name="global_form" class="global-form" id="global-form">
      <div class="panel-heading">Global Settings</div>
      <div class="panel-body">
        <div class="form-body">
          <div class="alert alert-danger hide">            
            <strong>Error! </strong> <span>You have some form errors. Please check below.</span>
          </div>
          <div class="alert alert-success hide">            
            <strong>Success!</strong> <span>Your form validation is successful!</span>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <legend class="m-b-xs"><h4>Basic Information</h4></legend>
              <div class="form-group required">
                <label for="input-app-name" class="control-label">Site Name</label>
                <input type="text" name="app_name" class="form-control" id="input-app-name" value="<?php echo $app_name; ?>" placeholder="Site Name" required>
              </div>
            </div>
            <div class="col-sm-6">
              <legend class="m-b-xs"><h4>Transaction Information</h4></legend>
              <div class="form-group">
                <label for="input-currency-symbol" class="control-label">Currency</label>
                <div class="input-group m-b">
                  <input type="text" name="currency_symbol" class="form-control" id="input-currency-symbol" value="<?php echo $currency_symbol; ?>" placeholder="Currency">
                  <span class="input-group-addon"><?php echo $currency_symbol; ?></span>
                </div>
              </div>
            </div>
          </div>
          <legend class="m-b-xs"><h4>Contact Information</h4></legend>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group required">
                <label for="input-phone" class="control-label">Phone</label>
                <input type="text" name="phone" class="form-control mask-phone" id="input-phone" value="<?php echo $phone; ?>" placeholder="Phone" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label for="input-email" class="control-label">Email</label>
                <input type="text" name="email" class="form-control" id="input-email" value="<?php echo $email; ?>" placeholder="Email" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
              <div class="form-group required">
                <label for="input-address" class="control-label">Address</label>
                <input type="text" name="address" class="form-control" id="input-address" value="<?php echo $address; ?>" placeholder="Address" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group required">
                <label for="input-city" class="control-label">City</label>
                <input type="text" name="city" class="form-control" id="input-city" value="<?php echo $city; ?>" placeholder="City" required>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label for="input-state-id" class="control-label" id="input-state-id">State</label>
                <select name="state_id" class="form-control m-b" required>
                  <option value="">Please select</option>
                  <?php if(count($states)) { ?>
                  <?php foreach($states AS $st_id => $state_name) { ?>
                    <option value="<?php echo $st_id ?>" <?php echo ($st_id == $state_id) ? 'selected' : ''; ?>><?php echo $state_name ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group required">
                <label for="input-zip" class="control-label">Zip</label>
                <input type="text" name="zip" class="form-control mask-number" id="input-zip" minlength="5" maxlength="6" value="<?php echo $zip; ?>" placeholder="Zip" required>
              </div>
            </div>
          </div>
          <legend class="m-b-xs"><h4>SEO Information</h4></legend>
          <div class="form-group">
            <label for="input-meta-title" class="control-label">Meta Title</label>
            <input type="text" name="meta_title" class="form-control" id="input-meta-title" value="<?php echo $meta_title; ?>" placeholder="Meta Title">
          </div>
          <div class="form-group">
            <label for="input-meta-description" class="control-label">Meta Tag Description</label>
            <textarea name="meta_description" class="form-control" id="input-meta-description" placeholder="Meta Tag Description"><?php echo $meta_description; ?></textarea>
          </div>
          <div class="form-group">
            <label for="input-meta-keyword" class="control-label">Meta Tag Keywords</label>
            <textarea name="meta_keyword" class="form-control" id="input-meta-keyword" placeholder="Meta Tag Keywords"><?php echo $meta_keyword; ?></textarea>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-sm-12 text-right">
            <button type="submit" id="button-global-save" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Loading..." class="btn btn-sm btn-info ">Save</button>
            <a href="<?php echo site_url($this->admin_folder.'setting'); ?>" class="btn btn-sm btn-default">Cancel</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
