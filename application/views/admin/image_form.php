<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-n font-thin h3 pull-left"><?php echo ($image_id == 0) ? 'Add New Checkout Image' : 'Edit Checkout Image' ?></h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="<?php echo site_url($this->admin_folder.'images'); ?>" class="btn btn-sm btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
      </div>
    </div>
  </div>
</div>
<div class="wrapper-md">
  <?php if ($this->success_message) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i><strong>Success!</strong> <span><?php echo $this->success_message; ?> </span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->error_message) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><strong>Error!</strong> <span><?php echo $this->error_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->info_message) { ?>
  <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i><strong>Info!</strong> <span><?php echo $this->info_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->warning_message) { ?>
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i><strong>Warning!</strong> <span><?php echo $this->warning_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <div class="panel panel-default">
    <form name="image_form" class="image-form form-horizontal" id="image-form">
      <div class="panel-heading"><?php echo ($image_id == 0) ? 'Add New Checkout Image' : 'Edit Checkout Image' ?></div>
      <div class="panel-body">
        <div class="alert alert-danger hide">            
          <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
        </div>
        <div class="alert alert-success hide">
          <strong>Success!</strong> <span>Your form validation is successful!</span>
        </div>
        <input type="hidden" name="image_id" value="<?php echo $image_id; ?>">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group required">
              <label for="input-image-name" class="col-sm-3 control-label">Image Name</label>
              <div class="col-sm-6 col-xs-12">
                <input type="text" name="name" class="form-control" id="input-image-name" value="<?php echo $name; ?>" required>
              </div>
            </div><!-- 
            <div class="form-group required hide">
              <label for="input-image-description" class="col-sm-3 control-label">Message</label>
              <div class="col-sm-9">
                <textarea type="text" name="description" class="form-control" id="input-image-description" required><?php echo $description; ?></textarea>
              </div>
            </div>
            <div class="form-group required hide">
              <label for="input-image-start-date" class="col-sm-3 control-label">Start Date</label>            
              <div class="col-sm-2 col-xs-12" id="input-image-start-date">
                <div class="input-group date">
                  <input type="text" name="start_date" class="form-control" value="<?php echo $start_date; ?>" readonly="readonly" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
              </div>
            </div>
            <div class="form-group required hide">
              <label for="input-image-end-date" class="col-sm-3 control-label">End Date</label>            
              <div class="col-sm-2 col-xs-12" id="input-image-end-date">
                <div class="input-group date">
                  <input type="text" name="end_date" class="form-control" value="<?php echo $end_date; ?>" readonly="readonly" required><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
              </div>
            </div>            
            <div class="row hide">
              <div class="col-sm-9 col-sm-offset-3"><h3>OR</h3></div>
            </div>  -->           
            <div class="form-group">
              <label class="col-sm-3 control-label">Image Image</label>
              <div class="col-sm-4 col-xs-12">
                <input ui-jq="filestyle" id="input-image-image" name="image" ui-options="{icon: false, buttonName: 'btn-info', buttonText: 'Choose Image'}" type="file">
              </div>
            </div>
            <?php if (!empty($image)) { ?>
            <div class="row m-b">
              <div class="col-sm-9 col-sm-offset-3">                
                <img id="preview-image-image" src="<?php echo $image; ?>" alt="..." class="img-thumbnail">
                <?php if ($ext_image) { ?>
                <a data-confirm="Are you sure to remove this image?" data-href="<?php echo site_url($this->admin_folder.'image/remove/image/'.$image_id); ?>" data-image-type="image" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remove Image." class="btn btn-danger btn-sm m-t confirm-remove-image"><i class="fa fa-trash"></i> Remove Image</a>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <div class="form-group required">
              <label for="input-image-status" class="col-sm-3 control-label">Status</label>
              <div class="col-sm-2 col-xs-12">
                <select name="is_active" class="form-control" id="input-image-status" required>
                  <option value="1"<?php echo ($is_active == '1') ? ' selected' : ''; ?>>Active</option>
                  <option value="0"<?php echo ($is_active == '0') ? ' selected' : ''; ?>>Inactive</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-9 col-sm-offset-3">
                <a href="<?php echo site_url($this->admin_folder.'images'); ?>" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info" id="button-image-save">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>