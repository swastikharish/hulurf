<?php
  $video_refer = $this->session->userdata('video_refer');
?>

<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-n font-thin h3 pull-left"><?php echo ($video_id == 0) ? 'Add New Video' : 'Edit Video' ?></h1>
      </div>
      <div class="col-sm-6 text-right">
        <?php if ($video_refer && ($video_refer['method'] == 'library')) { ?>
        <a href="<?php echo site_url($this->admin_folder.'videos/library?page='.$video_refer['page'].'&q='.$video_refer['q'].'&c='.$video_refer['c']); ?>" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back</a>
        <?php } elseif ($video_refer && ($video_refer['method'] == 'directory')) { ?>
        <a href="<?php echo site_url($this->admin_folder.'videos?page='.$video_refer['page'].'&q='.$video_refer['q']); ?>" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back</a>
        <?php } else { ?>
        <a href="<?php echo site_url($this->admin_folder.'videos'); ?>" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back</a>
        <?php } ?>
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
    <form name="video_form" class="video-form form-horizontal" id="video-form">
      <div class="panel-heading"><?php echo ($video_id == 0) ? 'Add New Video' : 'Edit Video' ?></div>
      <div class="panel-body">
        <div class="alert alert-danger hide">            
          <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
        </div>
        <div class="alert alert-success hide">
          <strong>Success!</strong> <span>Your form validation is successful!</span>
        </div>
        <input type="hidden" name="video_id" value="<?php echo $video_id; ?>">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group required">
              <label for="input-video-name" class="col-sm-3 control-label">Video Title</label>
              <div class="col-sm-6 col-xs-12">
                <input type="text" name="name" class="form-control" id="input-video-name" value="<?php echo $name; ?>" required>
              </div>
            </div>

            <div class="form-group required">
              <label for="input-video-description" class="col-sm-3 control-label">Description</label>
              <div class="col-sm-6 col-xs-12">
                <textarea name="description" class="form-control" id="input-video-description" rows="4" required><?php echo $description; ?></textarea>
              </div>
            </div>

            <?php if (($video_id == 0) || (($video_id > 0) && ($type == 'mp4'))) { ?>
            <div class="form-group m-b-none">
              <label class="col-sm-3 control-label">Mp4 Video</label>
              <div class="col-sm-4 col-xs-12">
                <input ui-jq="filestyle" id="input-video-mp4" name="file" ui-options="{icon: false, buttonName: 'btn-info', buttonText: 'Choose Mp4 Video'}" type="file">
              </div>
            </div>
            <?php if (!empty($mp4)) { ?>
            <div class="row">
              <div class="col-sm-4 col-sm-offset-3">                
                  <video width="320" height="240" controls>
                    <source src="<?php echo $mp4_url; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                  </video> 
              </div>
            </div>
            <?php } ?>
            <?php } ?>
            <?php if ($video_id == 0) { ?>
            <div class="row m-b m-t">
              <div class="col-sm-offset-3 col-sm-4 col-xs-12">OR</div>
            </div>
            <?php } ?>
            <?php if (($video_id == 0) || (($video_id > 0) && ($type == 'youtube'))) { ?>
            <div class="form-group">
              <label for="input-video-url" class="col-sm-3 control-label">YT Url</label>
              <div class="col-sm-6 col-xs-12">
                <input type="text" name="url" class="form-control" id="input-video-url" value="<?php echo $url; ?>">
              </div>
            </div>
            <?php } ?>

            <div class="form-group required">
              <label for="input-video-status" class="col-sm-3 control-label">Status</label>
              <div class="col-sm-2 col-xs-12">
                <select name="is_active" class="form-control" id="input-video-status" required>
                  <option value="1"<?php echo ($is_active == '1') ? ' selected' : ''; ?>>Active</option>
                  <option value="0"<?php echo ($is_active == '0') ? ' selected' : ''; ?>>Inactive</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">Video Image</label>
              <div class="col-sm-4 col-xs-12">
                <input ui-jq="filestyle" id="input-video-image" name="image" ui-options="{icon: false, buttonName: 'btn-info', buttonText: 'Choose Image'}" type="file">
              </div>
            </div>
            <?php if (!empty($image)) { ?>
            <div class="row m-b">
              <div class="col-sm-4 col-sm-offset-3">
                <img id="preview-video-image" src="<?php echo $image; ?>" alt="..." class="img-thumbnail">
                <br>
                <?php if ($ext_image) { ?>
                <a data-confirm="Are you sure to remove this image?" data-href="<?php echo site_url($this->admin_folder.'video/remove/image/'.$video_id); ?>" data-image-type="image" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remove Image." class="btn btn-danger btn-sm m-t confirm-remove-image"><i class="fa fa-trash"></i> Remove Image</a>
                <?php } ?>
              </div>
            </div>
            <?php } ?>            
            <div class="form-group">
              <label class="col-sm-3 control-label"></label>
              <div class="col-sm-4 col-xs-12">
                <div id="documents"></div>
                <button type="button" class="btn btn-info btn-more-document btn-sm m-t">+ Add More</button>
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
                <?php if ($video_refer && ($video_refer['method'] == 'library')) { ?>
                <a href="<?php echo site_url($this->admin_folder.'videos/library?page='.$video_refer['page'].'&q='.$video_refer['q'].'&c='.$video_refer['c']); ?>" class="btn btn-default">Cancel</a>
                <?php } elseif ($video_refer && ($video_refer['method'] == 'directory')) { ?>
                <a href="<?php echo site_url($this->admin_folder.'videos?page='.$video_refer['page'].'&q='.$video_refer['q']); ?>" class="btn btn-default">Cancel</a>
                <?php } else { ?>
                <a href="<?php echo site_url($this->admin_folder.'videos'); ?>" class="btn btn-default">Cancel</a>
                <?php } ?>
                <button type="submit" class="btn btn-info" id="button-video-save">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>