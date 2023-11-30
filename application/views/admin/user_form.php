<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-n font-thin h3 pull-left"><?php echo ($user_id == 0) ? 'Add New User' : 'Edit User' ?></h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="<?php echo site_url($this->admin_folder.'users'); ?>" class="btn btn-sm btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
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
    <form name="user_form" class="user-form form-horizontal" id="user-form">
      <div class="panel-heading"><?php echo ($user_id == 0) ? 'Add New User' : 'Edit User' ?></div>
      <div class="panel-body">
        <div class="alert alert-danger hide">            
          <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
        </div>
        <div class="alert alert-success hide">
          <strong>Success!</strong> <span>Your form validation is successful!</span>
        </div>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input id="username" style="display:none" type="text" name="fakeusernameremembered">
        <input id="password" style="display:none" type="password" name="fakepasswordremembered">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group required">
              <label for="input-user-type" class="col-sm-3 control-label">User Type</label>
              <div class="col-sm-3 col-xs-12">
                <select name="access_code" class="form-control" id="input-user-type" required>
                  <?php foreach ($groups as $type) { ?>
                  <option value="<?php echo $type['code']; ?>"<?php echo ($access_code == $type['code']) ? ' selected' : ''; ?>><?php echo $type['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group required">
              <label for="input-user-first-name" class="col-sm-3 control-label">First Name</label>
              <div class="col-sm-3 col-xs-12">
                <input type="text" name="first_name" class="form-control escape-special-character" id="input-user-first-name" value="<?php echo $first_name; ?>" required>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-user-last-name" class="col-sm-3 control-label">Last Name</label>
              <div class="col-sm-3 col-xs-12">
                <input type="text" name="last_name" class="form-control escape-special-character" id="input-user-last-name" value="<?php echo $last_name; ?>" required>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-user-email" class="col-sm-3 control-label">Email</label>
              <div class="col-sm-3 col-xs-12">
                <input type="email" name="email" class="form-control" id="input-user-email" value="<?php echo $email; ?>" required>
              </div>
            </div>

            <?php if ($user_id == 0) { ?>
            <div class="form-group required">
              <label for="input-user-password" class="col-sm-3 control-label">Password</label>
              <div class="col-sm-3 col-xs-12">
                <input type="password" name="password" class="form-control" id="input-user-password" value="<?php echo $password; ?>" required>
              </div>
            </div>
            <?php } ?>

            <div class="form-group required">
              <label for="input-user-phone" class="col-sm-3 control-label">Phone</label>
              <div class="col-sm-3 col-xs-12">
                <input type="text" name="phone" class="form-control mask-phone" id="input-user-phone" value="<?php echo $phone; ?>" required>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-user-status" class="col-sm-3 control-label">Status</label>
              <div class="col-sm-3 col-xs-12">
                <select name="is_active" class="form-control" id="input-user-status" required>
                  <option value="1"<?php echo ($is_active == '1') ? ' selected' : ''; ?>>Active</option>
                  <option value="0"<?php echo ($is_active == '0') ? ' selected' : ''; ?>>Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-sm-6">

          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-9 col-sm-offset-3">
                <a href="<?php echo site_url($this->admin_folder.'users'); ?>" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info" id="button-user-save">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>