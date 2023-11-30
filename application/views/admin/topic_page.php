<div class="bg-light lter b-b wrapper-md">
  <div class="clearfix">
    <h1 class="m-n font-thin h3 pull-left"><?php echo $topic['forum_title']; ?> - <?php echo $topic['title']; ?></h1>
    <div class="pull-right">
      <a href="<?php echo site_url($this->admin_folder.'topics'); ?>" class="btn btn-default"> <i class="fa fa-arrow-left"></i> Back</a>
      <a href="<?php echo site_url($this->admin_folder.'topic/edit/'.$topic['forum_id'].'/'.$topic['topic_id']); ?>" class="btn btn-info"><i class="fa fa-pencil"></i> Edit Topic</a>
    </div>
  </div>
</div>
<div class="wrapper-md">
  <div class="row">
    <div class="col-sm-9">
      <div class="blog-post">                   
        <div class="panel">
          <?php if ($images) { ?>
          <div>            
            <img src="<?php echo site_url('assets/images/'.$images[0]['image']); ?>" class="img-full">
          </div>
          <?php } ?>
          <div class="wrapper-lg">
            <h2 class="m-t-none"><?php echo $topic['title']; ?></h2>
            <div>
              <?php echo $topic['description']; ?>
            </div>
            <div class="line line-lg b-b b-light"></div>
            <div class="text-muted">
              <i class="fa fa-user text-muted"></i> by <a href class="m-r-sm"><?php echo $topic['user_name']; ?></a>
              <i class="fa fa-clock-o text-muted"></i> <?php echo format_date($topic['created']); ?>
              <a href class="m-l-sm"><i class="fa fa-comment-o text-muted"></i> <span class="count-conversation"><?php echo $count_conversations; ?></span> replies</a>
            </div>
          </div>
        </div>
      </div>
      <h4 class="m-t-lg m-b">Leave a comment</h4>
      <form name="conversation_form" class="conversation-form" id="conversation-form">
        <div class="alert alert-danger hide">            
          <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
        </div>
        <div class="alert alert-success hide">
          <strong>Success!</strong> <span>Your form validation is successful!</span>
        </div>
        <input type="hidden" name="forum_id" value="<?php echo $topic['forum_id']; ?>">
        <input type="hidden" name="topic_id" value="<?php echo $topic['topic_id']; ?>">
        <div class="form-group required">
          <label for="input-conversation-comment">Comment</label>
          <textarea class="form-control" rows="5" id="input-conversation-comment" name="comment" placeholder="Type your comment" required></textarea>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-success" id="btn-conversation-save">Submit</button>
        </div>
      </form>
      <h4 class="m-t-lg m-b"><span class="count-conversation"><?php echo $count_conversations; ?></span> Replies</h4>
      <div id="topic-conversation">
        <?php foreach($conversations as $conversation) { ?>
        <div>
          <span class="thumb-sm avatar avatar-text pull-left">
            <?php echo strtoupper($conversation['user_name'][0]); ?>              
          </span>
          <div class="m-l-xxl m-b">
            <div>
              <a href><strong><?php echo $conversation['user_name']; ?></strong></a>
              <label class="label bg-info m-l-xs"><?php echo $conversation['user_group_name']; ?></label> 
              <span class="text-muted text-xs block m-t-xs">
                <?php echo format_date($conversation['created']); ?>
              </span>
            </div>
            <div class="m-t-sm"><?php echo nl2br($conversation['content']); ?></div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div id="topic-conversation-more">
      </div>     
    </div>
    <div class="col-sm-3">
      <ul class="list-group">
        <li class="list-group-item">
          <strong>Forum:</strong>
          <br>
          <a href="<?php echo site_url($this->admin_folder.'forum/'.$topic['forum_slug']); ?>"><span class="label label-success"><?php echo $topic['forum_title']; ?></span></a>
        </li>
        <li class="list-group-item">          
          <strong>Status:</strong>
          <br>
          <?php if ($topic['is_approved'] == 0) { ?>
          <span class="label label-warning">Wating for Approvel</span>
          <?php } elseif ($topic['is_approved'] == 1 && $topic['is_active'] == 0) { ?>
          <span class="label label-danger">Inactive</span>
          <?php } elseif ($topic['is_active'] == 1) { ?>
          <span class="label label-success">Active</span>
          <?php } ?>
        </li>        
      </ul>
      <?php if ($topic['is_approved'] == 0) { ?>
      <a href="<?php echo site_url($this->admin_folder.'topic/approved/'.$topic['slug']); ?>" class="btn btn-success btn-block btn-rounded"><i class="fa fa-check-circle"></i> Approved</a>
      <?php } ?>
    </div>
  </div>
</div>