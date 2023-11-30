<?php
  $daterangeDateTime = new DateTime();
  $daterangeDateTime->add(new DateInterval('P1D'));
?>
<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left"><?php echo $user['first_name'].' '.$user['last_name']; ?>'s Logs</h1>

    <div class="pull-right">      
      <form type="get" class="report-filter form-inline">
        <a href="<?php echo site_url($this->admin_folder.'users'); ?>" class="btn btn-sm btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
        <div class="form-group form-group-daterange">
          <input ui-jq="daterangepicker" name="daterange" value="<?php echo $daterange; ?>" ui-options="{
            ranges: {
              'Today': [moment(), moment()],
              'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days': [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month': [moment().startOf('month'), moment().endOf('month')],
              'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            maxDate: '<?php echo $daterangeDateTime->format('m/d/Y'); ?>',
          }, function(start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
          }" class="form-control pull-right w-md" readonly="readonly" />
        </div>
        <button type="submit" class="btn btn-info btn-filter-report">Go!</button>
      </form>
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
    <div class="panel-heading">Logs</div>
    <div class="table-responsive">
      <table class="table table-bordered m-b-none b-t b-light small">
        <thead>
          <tr>
            <th style="width: 20%">Date</th>
            <th>Activity</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($activities)) { ?>
          <?php foreach ($activities as $key => $activity) { ?>
          <tr>
            <td><?php echo $activity['created_date']; ?></td>
            <td><?php echo $activity['message']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="2" class="text-center">Empty list!</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php if($activities) { ?>
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