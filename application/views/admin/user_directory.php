<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left">Users</h1>

    <div class="pull-right">
      <a href="<?php echo site_url($this->admin_folder.'user/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Add Users</a>
    </div>
  </div>
</div>
<div class="wrapper-md">
  <?php if ($this->success_message) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i><strong>Success! </strong> <span> <?php echo $this->success_message; ?> </span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->error_message) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><strong>Error! </strong> <span>  <?php echo $this->error_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->info_message) { ?>
  <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i><strong>Info! </strong> <span>  <?php echo $this->info_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->warning_message) { ?>
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i><strong>Warning! </strong> <span>  <?php echo $this->warning_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  
  <div class="well p-sm">
    <form name="user_search_form" class="user-search-form" id="user-search-form ">
      <div class="form-group row row-sm m-b-none">
        <label class="sr-only" for="user-search-q">User</label>
        <div class="col-sm-10">
          <input type="text" name="q" class="form-control" id="user-search-q" placeholder="Search Users" value="<?php echo $this->input->get('q'); ?>">
        </div>
        <div class="col-sm-2">
          <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
          <a href="<?php echo site_url($this->admin_folder.'users'); ?>" class="btn btn-default">Reset</a>
        </div>
      </div>
    </form>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Users</div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Last Login Date</th>
            <th>Active</th>
            <th width="20%" class="text-right">&nbsp;</th>
          </tr>
        </thead>
        <tbody class="sortable-users"> 
          <?php if($users) { ?>
          <?php foreach($users AS $user) { ?>
          <tr>
            <td><?php echo $user['first_name'].' '.$user['last_name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['logdate_date']; ?></td>
            <td>
              <label class="i-switch m-t-xs m-r">
                <input type="checkbox" name="status" class="c-update-status-check" data-table-name="user" data-primary-name="user_id" data-primary-value="<?php echo $user['user_id']; ?>" data-field-name="is_active" data-confirm="Are you sure to change user status?" <?php echo ($user['is_active'] == '1') ? 'checked' : ''?>>
                <i></i>
              </label>
            </td>
            <td class="text-right">
              <a href="<?php echo site_url($this->admin_folder.'user/logs/'.$user['user_id']); ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="User Logs."><i class="fa fa-serach"></i> Logs</a>
              <a href="<?php echo site_url($this->admin_folder.'user/edit/'.$user['user_id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit User."><i class="fa fa-pencil"></i> Edit</a>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="4" class="text-center">Empty!</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php if($users) { ?>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-6 text-left">
          <small class="text-muted inline m-t-sm m-b-sm"><?php echo $pagination_string; ?></small>
        </div>
        <div class="col-sm-6 text-right text-center-xs">
          <?php echo $this->pagination->create_links(); ?> 
        </div>
      </div>
    </footer>
    <?php } ?>
  </div>
</div>