<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left">Template (Emails)</h1>

    <!-- <div class="pull-right">
      <a href="<?php echo site_url($this->admin_folder.'template/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Add New Template</a>
    </div> -->
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

  <div class="panel panel-default">
    <div class="panel-heading">Emails</div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Name</th>
            <th width="15%" class="text-right">&nbsp;</th>
          </tr>
        </thead>
        <tbody class="sortable-template-messages"> 
          <?php if($templates) { ?>
          <?php
            foreach($templates AS $template) {
          ?>
          <tr>
            <td><?php echo $template['name']; ?></td>
            <td class="text-right">
              <a href="<?php echo site_url($this->admin_folder.'template/edit/'.$template['template_id']); ?>" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Template."><i class="fa fa-pencil"></i> Edit</a>
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
    <?php if($templates) { ?>
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