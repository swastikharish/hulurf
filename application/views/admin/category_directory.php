<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left">Categories Directory</h1>
    <div class="pull-right">
      <a href="<?php echo site_url($this->admin_folder.'setting'); ?>" class="btn btn-default m-r-sm"> <i class="fa fa-arrow-left"></i> Back</a>
      <a href="<?php echo site_url($this->admin_folder.'category/add'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Add New Category</a>
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

  <div class="panel panel-default">
    <div class="panel-heading">Categories</div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Name</th>
            <th>Price</th>
            <th width="20%" class="text-right">&nbsp;</th>
          </tr>
        </thead>
        <tbody class="sortable-categories"> 
          <?php if($categories) { ?>
          <?php foreach($categories AS $category) { ?>
          <tr>
            <td><?php echo $category['name']; ?></td>
            <td><?php echo ($category['price'] > 0) ? format_currency($category['price']) : 'Free'; ?></td>
            <td class="text-right">
              <a href="<?php echo site_url($this->admin_folder.'category/edit/'.$category['category_id'].'?'.$_SERVER['QUERY_STRING']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Category."><i class="fa fa-pencil"></i> Edit</a>
              <a data-confirm="Are you sure to remove this category?" data-href="<?php echo site_url($this->admin_folder.'category/delete/'.$category['category_id'].'?'.$_SERVER['QUERY_STRING']); ?>" class="btn btn-danger btn-xs bootbox-confirm-box" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Category."><i class="fa fa-trash"></i> Delete</a>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="2" class="text-center">Empty!</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php if($categories) { ?>
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