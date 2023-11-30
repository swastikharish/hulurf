<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left">Video Directory</h1>

    <div class="pull-right">
      <a href="<?php echo site_url($this->admin_folder.'video/categories'); ?>" class="btn btn-default m-r-sm"> <i class="fa fa-arrow-left"></i> Back</a>

      <a href="<?php echo site_url($this->admin_folder.'video/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Add New Video</a>
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
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><strong>Error! </strong> <span> <?php echo $this->error_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->info_message) { ?>
  <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i><strong>Info! </strong> <span> <?php echo $this->info_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($this->warning_message) { ?>
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i><strong>Warning! </strong> <span> <?php echo $this->warning_message; ?></span>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <div class="well p-sm">
    <form name="video_search_form" class="video-search-form" id="video-search-form ">
      <div class="form-group row row-sm m-b-none">
        <label class="sr-only" for="video-search-q">Name</label>
        <div class="col-sm-10">
          <input type="text" name="q" class="form-control" id="video-search-q" placeholder="Search by Video Title" value="<?php echo $this->input->get('q'); ?>">
        </div>
        <div class="col-sm-2">
          <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
          <?php if ($page_method == 'library') { ?>
          <a href="<?php echo site_url($this->admin_folder.'videos/library'); ?>" class="btn btn-default">Reset</a>
          <?php } else { ?>
          <a href="<?php echo site_url($this->admin_folder.'videos'); ?>" class="btn btn-default">Reset</a>
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">Videos</div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Active</th>
            <th width="20%" class="text-right">&nbsp;</th>
          </tr>
        </thead>
        <tbody > 
          <?php if($videos) { ?>
          <?php foreach($videos AS $video) { ?>
          <tr>
            <td><?php echo $video['name']; ?></td>
            <td><?php echo ($video['category_name']!= '') ? $video['category_name'] : '-'; ?></td>
            <td><?php echo ($video['is_active'] == 1) ? '<span class="label label-success"><i class="fa fa-check" aria-hidden="true"></i></span>' : '<span class="label label-danger"><i class="fa fa-times" aria-hidden="true"></i></span>'; ?></td>
            <td class="text-right">
              <a href="<?php echo site_url($this->admin_folder.'video/edit/'.$video['video_id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Video."><i class="fa fa-pencil"></i> Edit</a>
              <?php if ($video['type'] == 'mp4') { ?>
              <a href="<?php echo site_url('assets/videos/'.$video['mp4_video']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Video." target="_blank"><i class="fa fa-video"></i> View</a>
              <?php } else { ?>
              <a href="<?php echo $video['youtube_video']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Video." target="_blank"><i class="fa fa-youtube-square"></i> View</a>
              <?php } ?>
              <a data-confirm="Are you sure to remove this video?" data-href="<?php echo site_url($this->admin_folder.'video/delete/'.$video['video_id']); ?>" class="btn btn-danger btn-xs bootbox-confirm-box" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Video."><i class="fa fa-trash"></i> Delete</a>
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
  </div>
</div>