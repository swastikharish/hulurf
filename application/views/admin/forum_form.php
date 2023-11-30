<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-n font-thin h3 pull-left"><?php echo ($forum_id == 0) ? 'Create Forum' : 'Edit Forum' ?></h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="<?php echo site_url($this->admin_folder.'forums'); ?>" class="btn btn-sm btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
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
    <form name="forum_form" class="forum-form form-horizontal" id="forum-form">
      <div class="panel-heading"><?php echo ($forum_id == 0) ? 'Create Forum' : 'Edit Forum' ?></div>
      <div class="panel-body">
        <div class="alert alert-danger hide">            
          <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
        </div>
        <div class="alert alert-success hide">
          <strong>Success!</strong> <span>Your form validation is successful!</span>
        </div>
        <input type="hidden" name="forum_id" value="<?php echo $forum_id; ?>">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group required">
              <label for="input-forum-category" class="col-sm-3 control-label">Category</label>
              <div class="col-sm-3 col-xs-12">
                <select name="category_id" class="form-control" id="input-forum-category" required>
                  <option value=""<?php echo ($category_id == '') ? ' selected="selected"' : ''; ?>>--Select--</option>
                  <?php foreach ($categories as $key => $category) { ?>                  
                  <option value="<?php echo $category['category_id']; ?>"<?php echo ($category_id == $category['category_id']) ? ' selected="selected"' : ''; ?>><?php echo $category['name']; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-forum-title" class="col-sm-3 control-label">Title</label>
              <div class="col-sm-6">
                <input type="text" name="title" class="form-control" id="input-forum-title" value="<?php echo $title; ?>" required>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-forum-short-description" class="col-sm-3 control-label">Short Description</label>
              <div class="col-sm-6">
                <textarea name="short_description" class="form-control" id="input-forum-short-description" rows="5" required><?php echo $short_description; ?></textarea>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-forum-description" class="col-sm-3 control-label">Description</label>
              <div class="col-sm-8">
                <div class="btn-toolbar m-b-sm btn-editor" data-role="editor-toolbar" data-target="#input-forum-description">
                  <div class="btn-group">
                    <a class="btn btn-default" data-edit="formatBlock p" data-original-title="Paragraph" title="Paragraph">P</a>
                    <a class="btn btn-default" data-edit="formatBlock h1" data-original-title="Header" title="Header">H1</a>
                    <a class="btn btn-default" data-edit="formatBlock h2" data-original-title="2nd-level header" title="2nd-level header">H2</a>
                    <a class="btn btn-default" data-edit="formatBlock h3" data-original-title="3rd-level header" title="3rd-level header">H3</a>
                    <a class="btn btn-default" data-edit="formatBlock h4" data-original-title="4rd-level header" title="4rd-level header">H4</a>
                  </div>
                  <div class="btn-group dropdown" dropdown>
                    <a class="btn btn-default" dropdown-toggle data-toggle="dropdown" tooltip="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
                      <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
                      <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
                    </ul>
                  </div>
                  <div class="btn-group">
                    <a class="btn btn-default" data-edit="bold" tooltip="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                    <a class="btn btn-default" data-edit="italic" tooltip="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                    <a class="btn btn-default" data-edit="strikethrough" tooltip="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                    <a class="btn btn-default" data-edit="underline" tooltip="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                  </div>
                  <div class="btn-group">
                    <a class="btn btn-default" data-edit="insertunorderedlist" tooltip="Bullet list"><i class="fa fa-list-ul"></i></a>
                    <a class="btn btn-default" data-edit="insertorderedlist" tooltip="Number list"><i class="fa fa-list-ol"></i></a>
                    <a class="btn btn-default" data-edit="outdent" tooltip="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                    <a class="btn btn-default" data-edit="indent" tooltip="Indent (Tab)"><i class="fa fa-indent"></i></a>
                  </div>
                  <div class="btn-group">
                    <a class="btn btn-default" data-edit="justifyleft" tooltip="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                    <a class="btn btn-default" data-edit="justifycenter" tooltip="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                    <a class="btn btn-default" data-edit="justifyright" tooltip="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                    <a class="btn btn-default" data-edit="justifyfull" tooltip="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                  </div>
                  <div class="btn-group dropdown" dropdown>
                    <a class="btn btn-default" dropdown-toggle data-toggle="dropdown" tooltip="Hyperlink"><i class="fa fa-link"></i></a>
                    <div class="dropdown-menu">
                      <div class="input-group m-l-xs m-r-xs">
                        <input class="form-control input-sm" id="LinkInput" placeholder="URL" type="text" data-edit="createLink"/>
                        <div class="input-group-btn">
                          <button class="btn btn-sm btn-default" type="button">Add</button>
                        </div>
                      </div>
                    </div>
                    <a class="btn btn-default" data-edit="unlink" tooltip="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                  </div>
                  
                  <div class="btn-group">
                    <a class="btn btn-default" tooltip="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
                    <input type="file" data-edit="insertImage" style="position:absolute; opacity:0; width:41px; height:34px" />
                  </div>
                  <div class="btn-group">
                    <a class="btn btn-default" data-edit="undo" tooltip="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                    <a class="btn btn-default" data-edit="redo" tooltip="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                  </div>
                </div>
                <div ui-jq="wysiwyg" id="input-forum-description" class="form-control" style="overflow:scroll;height:200px;max-height:200px"><?php echo $description; ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-heading"><?php echo ($forum_id == 0) ? 'Add Image' : 'Edit Image' ?></div>
      <div class="panel-body">
        <div class="form-group">
          <label class="col-sm-3 control-label">Image Image</label>
          <div class="col-sm-4 col-xs-12">
            <input ui-jq="filestyle" id="input-forum-image" name="image" ui-options="{icon: false, buttonName: 'btn-info', buttonText: 'Choose Photo'}" type="file">
          </div>
        </div>
        <?php if ($forum_id > 0 && $images) { ?>
        <input type="hidden" name="image_id" value="<?php echo $images[0]['image_id']; ?>">
        <div class="row">
          <div class="col-sm-9 col-sm-offset-3">
            <img id="preview-forum-image" src="<?php echo site_url('assets/images/'.$images[0]['image']); ?>" alt="..." class="img-thumbnail">
            <a data-confirm="Are you sure to remove this image?" data-href="<?php echo site_url($this->admin_folder.'forum/remove/image/'.$forum_id.'/'.$images[0]['image_id']); ?>" data-image-type="image" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remove Photo." class="btn btn-danger btn-sm m-t confirm-remove-image"><i class="fa fa-trash"></i> Remove Image</a>
          </div>
        </div>
        <?php } else { ?>
        <input type="hidden" name="image_id" value="0">
        <div class="row">
          <div class="col-sm-9 col-sm-offset-3">  
            <img id="preview-forum-image" src="<?php echo $image; ?>" alt="..." class="img-thumbnail">
          </div>
        </div>
        <?php } ?>
        <div class="form-group m-t">
          <label for="input-forum-image-url" class="col-sm-3 control-label">Image Url</label>
          <div class="col-sm-6 col-xs-12">
            <input type="text" name="image_url" class="form-control" id="input-forum-image-url" value="<?php echo (($forum_id > 0) && isset($images[0])) ? $images[0]['url'] : ''; ?>"> 
          </div>
        </div>
      </div>
      <div class="panel-heading"><?php echo ($forum_id == 0) ? 'Add Video' : 'Edit Video' ?></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-6">
            <div class="panel m-b-none">
              <div class="panel-heading text-center"><strong>Video 1</strong></div>
              <div class="panel-body">
                <div class="form-group m-b-none">
                  <label class="col-sm-3 control-label">Mp4 Video</label>
                  <div class="col-sm-9 col-xs-12">
                    <input ui-jq="filestyle" id="input-forum-mp4-video-1" name="mp4_video_1" ui-options="{icon: false, buttonName: 'btn-info', buttonText: 'Choose Mp4 Video'}" type="file">
                  </div>
                </div>
                <?php if (($forum_id > 0) && isset($videos[0]) && ($videos[0]['type'] == 'mp4')) { ?>
                <div class="row" id="video-index-1">
                  <div class="col-sm-9 col-sm-offset-3">
                    <input type="hidden" name="mp4_video_id_1" value="<?php echo $videos[0]['video_id']; ?>">                                    
                    <video width="320" height="240" controls>
                      <source src="<?php echo site_url('assets/videos/'.$videos[0]['mp4_video']); ?>" type="video/mp4">
                      Your browser does not support the video tag.
                    </video>

                    <a data-confirm="Are you sure to remove this video?" data-href="<?php echo site_url($this->admin_folder.'forum/remove/video/'.$forum_id.'/'.$videos[0]['video_id']); ?>" data-video-index="1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remove Video." class="btn btn-danger btn-sm m-t confirm-remove-video"><i class="fa fa-trash"></i> Remove Video</a>
                  </div>
                </div>
                <?php } else { ?>
                <input type="hidden" name="mp4_video_id_1" value="0">
                <?php } ?>
                <div class="row m-b m-t">
                  <div class="col-sm-offset-3 col-sm-4 col-xs-12">OR</div>
                </div>
                <div class="form-group">
                  <label for="input-forum-youtube-url-1" class="col-sm-3 control-label">YT Url</label>
                  <div class="col-sm-9 col-xs-12">
                    <input type="text" name="youtube_url_1" class="form-control" id="input-forum-youtube-url-1" value="<?php echo (($forum_id > 0) && isset($videos[0]) && ($videos[0]['type'] == 'youtube')) ? $videos[0]['youtube_video'] : ''; ?>">
                    <input type="hidden" name="youtube_url_id_1" value="<?php echo (($forum_id > 0) && isset($videos[0]) && ($videos[0]['type'] == 'youtube')) ? $videos[0]['video_id'] : 0; ?>">
                    <input type="hidden" name="youtube_url_old_1" value="<?php echo (($forum_id > 0) && isset($videos[0]) && ($videos[0]['type'] == 'youtube')) ? $videos[0]['youtube_video'] : ''; ?>"> 
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="panel m-b-none">
              <div class="panel-heading text-center"><strong>Video 2</strong></div>
              <div class="panel-body">
                <div class="form-group m-b-none">
                  <label class="col-sm-3 control-label">Mp4 Video</label>
                  <div class="col-sm-9 col-xs-12">
                    <input ui-jq="filestyle" id="input-forum-mp4-video-2" name="mp4_video_2" ui-options="{icon: false, buttonName: 'btn-info', buttonText: 'Choose Mp4 Video'}" type="file">
                  </div>
                </div>
                <?php if (($forum_id > 0) && isset($videos[1]) && ($videos[1]['type'] == 'mp4')) { ?>
                <div class="row" id="video-index-2">
                  <div class="col-sm-9 col-sm-offset-3">
                    <input type="hidden" name="mp4_video_id_2" value="<?php echo $videos[1]['video_id']; ?>">                                    
                    <video width="320" height="240" controls>
                      <source src="<?php echo site_url('assets/videos/'.$videos[1]['mp4_video']); ?>" type="video/mp4">
                      Your browser does not support the video tag.
                    </video>

                    <a data-confirm="Are you sure to remove this video?" data-href="<?php echo site_url($this->admin_folder.'forum/remove/video/'.$forum_id.'/'.$videos[1]['video_id']); ?>" data-video-index="2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remove Video." class="btn btn-danger btn-sm m-t confirm-remove-video"><i class="fa fa-trash"></i> Remove Video</a>
                  </div>
                </div>
                <?php } else { ?>
                <input type="hidden" name="mp4_video_id_2" value="0">
                <?php } ?>
                <div class="row m-b m-t">
                  <div class="col-sm-offset-3 col-sm-4 col-xs-12">OR</div>
                </div>
                <div class="form-group">
                  <label for="input-forum-youtube-url-2" class="col-sm-3 control-label">YT Url</label>
                  <div class="col-sm-9 col-xs-12">
                    <input type="text" name="youtube_url_2" class="form-control" id="input-forum-youtube-url-2" value="<?php echo (($forum_id > 0) && isset($videos[1]) && ($videos[1]['type'] == 'youtube')) ? $videos[1]['youtube_video'] : ''; ?>">
                    <input type="hidden" name="youtube_url_id_2" value="<?php echo (($forum_id > 0) && isset($videos[1]) && ($videos[1]['type'] == 'youtube')) ? $videos[1]['video_id'] : 0; ?>">
                    <input type="hidden" name="youtube_url_old_2" value="<?php echo (($forum_id > 0) && isset($videos[1]) && ($videos[1]['type'] == 'youtube')) ? $videos[1]['youtube_video'] : ''; ?>"> 
                  </div>
                </div>
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
                <a href="<?php echo site_url($this->admin_folder.'forums'); ?>" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info btn-forum-save" id="button-forum-save">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>