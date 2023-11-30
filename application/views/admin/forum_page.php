<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left"><?php echo $forum['title']; ?></h1>
    <div class="pull-right">
      <a href="<?php echo site_url($this->admin_folder.'forums'); ?>" class="btn btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
      <a href="<?php echo site_url($this->admin_folder.'forum/edit/'.$forum['forum_id']); ?>" class="btn btn-info"><i class="fa fa-pencil"></i> Edit Forum</a>
      <a href="<?php echo site_url($this->admin_folder.'topic/add/'.$forum['forum_id']); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Create Topic</a>
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

  <?php if ($images) { ?>
  <div class="m-b-lg">
    <img src="<?php echo site_url('assets/images/'.$images[0]['image']); ?>" alt="..." class="img-responsive center-block">
  </div>
  <?php } ?>

  <div class="row">
    <div class="col-sm-9">
      <div class="blog-post">                   
        <div class="panel">
          <div class="wrapper-lg">
            <h2 class="m-t-none"><?php echo $forum['title']; ?></h2>
            <p><?php echo nl2br($forum['short_description']); ?></p>
            <div>
              <?php echo $forum['description']; ?>
            </div>

            <?php if ($videos) { ?>
            <div class="row m-t-lg row-forum-video">
              <div class="col-sm-6 col-video">
                <?php if ($videos[0]['type'] == 'mp4' && !empty($videos[0]['mp4_video'])) { ?>
                <video id="video-<?php echo $videos[0]['video_id'] ?>" class="trackable-video" style="width: 100%; height: 100%;" controls controlsList="nodownload">
                  <source src="<?php echo site_url('assets/videos/'.$videos[0]['mp4_video']); ?>" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
                <?php } elseif(!empty($videos[0]['youtube_video'])) { ?>
                <iframe id="video-<?php echo $videos[0]['video_id'] ?>" class="trackable-iframe-video" width="1280" height="720" src="<?php echo $videos[0]['youtube_video'] ?>?enablejsapi=1" title="<?php echo $videos[0]['name'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <?php } ?>
              </div>
              <?php if (isset($videos[1])) { ?>
              <div class="col-sm-6 col-video">
                <?php if ($videos[1]['type'] == 'mp4' && !empty($videos[1]['mp4_video'])) { ?>
                <video id="video-<?php echo $videos[1]['video_id'] ?>" class="trackable-video" style="width: 100%; height: 100%;" controls controlsList="nodownload">
                  <source src="<?php echo site_url('assets/videos/'.$videos[1]['mp4_video']); ?>" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
                <?php } elseif(!empty($videos[1]['youtube_video'])) { ?>
                <iframe id="video-<?php echo $videos[1]['video_id'] ?>" class="trackable-iframe-video" width="1280" height="720" src="<?php echo $videos[1]['youtube_video'] ?>?enablejsapi=1" title="<?php echo $videos[1]['name'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <?php } ?>
              </div>
              <?php } ?>
            </div>
            <?php } ?>

            <div class="line line-lg b-b b-light"></div>
            <div class="text-muted">
              <i class="fa fa-user text-muted"></i> by <a href class="m-r-sm"><?php echo $forum['user_name']; ?></a>
              <i class="fa fa-clock-o text-muted"></i> <?php echo format_date($forum['created']); ?>
              <a href class="m-l-sm"><i class="fa fa-comment-o text-muted"></i> <?php echo $count_topics.' topics'; ?></a>
            </div>
          </div>
        </div>        
      </div>
      <?php if ($count_topics > 0) { ?>
      <h4 class="m-t-lg m-b">Topics</h4>
      <ul class="list-group m-b-none">
        <?php foreach ($topics as $topic) { ?>
        <li class="list-group-item">
          <a href="<?php echo site_url($this->admin_folder.'topic/'.$topic['slug']); ?>" class="h4 text-primary m-b-sm m-t-sm block"><?php echo $topic['title']; ?></a>
          <p class="text-muted">
            <i class="fa fa-user text-muted"></i> by <a href class="m-r-sm"><?php echo $topic['user_name']; ?></a>
            <i class="fa fa-clock-o text-muted"></i> <?php echo format_date($topic['created']); ?>
            <a href class="m-l-sm"><i class="fa fa-comment-o text-muted"></i> <?php echo $this->Topic_model->countTopicConversations($topic['topic_id']).' replies'; ?></a>
          </p>
        </li>
        <?php } ?>
      </ul>
      <div class="text-center">
        <?php echo $this->pagination->create_links(); ?>
      </div>
      <?php } ?>
    </div>
    <div class="col-sm-3">
      <ul class="list-group">
        <li class="list-group-item">          
          <strong>Category:</strong>
          <br>
          <span class="label label-success"><?php echo $forum['category_name']; ?></span>
        </li>
        <li class="list-group-item">          
          <strong>Status:</strong>
          <br>
          <?php if ($forum['is_approved'] == 0) { ?>
          <span class="label label-warning">Wating for Approvel</span>
          <?php } elseif ($forum['is_approved'] == 1 && $forum['is_active'] == 0) { ?>
          <span class="label label-danger">Inactive</span>
          <?php } elseif ($forum['is_active'] == 1) { ?>
          <span class="label label-success">Active</span>
          <?php } ?>
        </li>        
      </ul>
      <?php if ($forum['is_approved'] == 0) { ?>
      <a href="<?php echo site_url($this->admin_folder.'forum/approved/'.$forum['slug']); ?>" class="btn btn-success btn-block btn-rounded"><i class="fa fa-check-circle"></i> Approved</a>
      <?php } ?>

    </div>
  </div>
</div>