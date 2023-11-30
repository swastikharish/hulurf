<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left">Forums Directory</h1>

    <div class="pull-right">
      <a href="<?php echo site_url($this->admin_folder.'forum/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Create Forum</a>
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
    <form name="forum_search_form" class="forum-search-form" id="forum-search-form ">
      <div class="form-group row row-sm m-b-none">
        <label class="sr-only" for="forum-search-q">Forums</label>
        <div class="col-sm-10">
          <input type="text" name="q" class="form-control" id="forum-search-q" placeholder="Search Forums" value="<?php echo $this->input->get('q'); ?>">
        </div>
        <div class="col-sm-2">
          <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
          <a href="<?php echo site_url($this->admin_folder.'forums'); ?>" class="btn btn-default">Reset</a>
        </div>
      </div>
    </form>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Forums</div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Created By</th>
            <th>Status</th>
            <th>Topics</th>
            <th width="20%" class="text-right">&nbsp;</th>
          </tr>
        </thead>
        <tbody class="sortable-forums"> 
          <?php if($forums) { ?>
          <?php foreach($forums AS $forum) { ?>
          <tr>
            <td><?php echo $forum['title']; ?></td>
            <td><?php echo $forum['category_name']; ?></td>
            <td><?php echo $forum['user_name']; ?></td>
            <td>
              <?php if ($forum['is_approved'] == 0) { ?>
              <span class="label label-warning">Wating for Approvel</span>
              <?php } elseif ($forum['is_approved'] == 1 && $forum['is_active'] == 0) { ?>
              <span class="label label-danger">Inactive</span>
              <?php } elseif ($forum['is_active'] == 1) { ?>
              <span class="label label-success">Active</span>
              <?php } ?>
            </td>
            <td><?php echo $this->Forum_orm_model->countForumTopics($forum['forum_id']); ?></td>
            <td class="text-right">
              <a href="<?php echo site_url($this->admin_folder.'forum/edit/'.$forum['forum_id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Forums."><i class="fa fa-pencil"></i> Edit</a>
              <a href="<?php echo site_url($this->admin_folder.'forum/'.$forum['slug']); ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Forum."><i class="fa fa-search"></i> Go</a>
              <a data-confirm="Are you sure to remove this forum?" data-href="<?php echo site_url($this->admin_folder.'forum/delete/'.$forum['forum_id']); ?>" class="btn btn-danger btn-xs bootbox-confirm-box" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Forums."><i class="fa fa-trash"></i> Delete</a>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="6" class="text-center">Empty!</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php if($forums) { ?>
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