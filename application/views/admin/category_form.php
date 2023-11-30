<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-n font-thin h3 pull-left"><?php echo ($category_id == 0) ? 'Add New Category' : 'Edit Category' ?></h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="<?php echo site_url($this->admin_folder.'categories'); ?>" class="btn btn-sm btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
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
    <form name="category_form" class="category-form form-horizontal" id="category-form">
      <div class="panel-heading"><?php echo ($category_id == 0) ? 'Add New Category' : 'Edit Category' ?></div>
      <div class="panel-body">
        <div class="alert alert-danger hide">            
          <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
        </div>
        <div class="alert alert-success hide">
          <strong>Success!</strong> <span>Your form validation is successful!</span>
        </div>
        <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group required">
              <label for="input-category-name" class="col-sm-3 control-label">Name</label>
              <div class="col-sm-6 col-xs-12">
                <input type="text" name="name" class="form-control" id="input-category-name" value="<?php echo $name; ?>" required>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-category-price" class="col-sm-3 control-label">Price</label>
              <div class="col-sm-4 col-xs-12">
                <input type="text" name="price" class="form-control mask-decimal" id="input-category-price" value="<?php echo $price; ?>" required>
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
                <a href="<?php echo site_url($this->admin_folder.'categories'); ?>" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info" id="button-category-save">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>