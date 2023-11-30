<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-n font-thin h3 pull-left"><?php echo ($page_id == 0) ? 'Add Page' : 'Edit Page' ?></h1>
      </div>
      <div class="col-sm-6 text-right">
        <a href="<?php echo site_url($this->admin_folder.'pages'); ?>" class="btn btn-sm btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
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
    <form name="page_form" class="page-form form-horizontal" id="page-form">
      <div class="panel-heading"><?php echo ($page_id == 0) ? 'Add Page' : 'Edit Page' ?></div>
      <div class="panel-body">
        <div class="alert alert-danger hide">            
          <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
        </div>
        <div class="alert alert-success hide">
          <strong>Success!</strong> <span>Your form validation is successful!</span>
        </div>
        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group required">
              <label for="input-page-title" class="col-sm-3 control-label">Title</label>
              <div class="col-sm-6">
                <input type="text" name="title" class="form-control" id="input-page-title" value="<?php echo $title; ?>" required>
              </div>
            </div>
            <div class="form-group required">
              <label for="input-page-description" class="col-sm-3 control-label">Description</label>
              <div class="col-sm-8">
                <div class="btn-toolbar m-b-sm btn-editor" data-role="editor-toolbar" data-target="#input-page-description">
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
                <div ui-jq="wysiwyg" id="input-page-description" class="form-control" style="overflow:scroll;height:200px;max-height:200px"><?php echo $description; ?></div>
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
                <a href="<?php echo site_url($this->admin_folder.'pages'); ?>" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info btn-page-save" id="button-page-save">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>