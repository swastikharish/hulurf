
		<section class="forum-header">
			<div class="container">
				<div class="forum-video-wrapper p-0">
					<div class="row">
						<div class="col-md-12">
							<h2 class="title title-small mb-4"><a href="<?php echo site_url('/forum/'.$topic['forum_slug']); ?>"><?php echo $topic['forum_title']; ?></a> <i class="fa fa-arrow-right"></i> <?php echo $topic['title']; ?></h2>
							<p><?php echo $topic['description']; ?></p>							
						</div>
					</div>
					<?php if ($images) { ?>
					<div class="row">
						<div class="col-md-6">
							<img src="<?php echo site_url('assets/images/'.$images[0]['image']); ?>" class="img-fluid">
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</section>

		<section class="forum-section pt-0">				
			<div class="container">	
				<div class="comment-forum-form">	
					<h4 class="mb-3">Leave a comment</h4>
					<?php if ($this->user_session_data) { ?>
					<form name="conversation_form" class="conversation-form" id="conversation-form">
					  <div class="alert alert-danger d-none">            
					    <strong>Error!</strong> <span>You have some form errors. Please check below.</span>
					  </div>
					  <div class="alert alert-success d-none">
					    <strong>Success!</strong> <span>Your form validation is successful!</span>
					  </div>
					  <input type="hidden" name="forum_id" value="<?php echo $topic['forum_id']; ?>">
					  <input type="hidden" name="topic_id" value="<?php echo $topic['topic_id']; ?>">
					  <div class="mb-3">
					    <textarea class="form-control" rows="5" id="input-conversation-comment" name="comment" placeholder="Type your comment" required></textarea>
					  </div>
					  <button type="submit" class="btn btn-success" id="conversation-form-save-button">Submit</button>
					</form>
					<?php } else { ?>
					<p>You need to <a href="<?php echo site_url('/login'); ?>">login</a> in order to reply to topics within this forum.</p>
					<?php } ?>
				</div>
				<div class="row mt-5">
					<div class="col-12">
						<div class="table-heading">
							<h3><span class="count-conversation"><?php echo $count_conversations; ?></span> Replies</h3>
						</div>
					</div>
				</div>
				<div class="forum-card2 forum-list">
					<div class="pagination-section">
						<?php echo $this->pagination->create_links(); ?>
					</div>
					<div class="forum-card-body">
						<div id="topic-conversation">
							<?php if ($count_conversations > 0) { ?>
						  <?php foreach($conversations as $key => $conversation) { ?>
						  <div class="row<?php echo ($key > 0) ? ' mt-4' : ''; ?>">
						  	<div class="col-md-12">
						  		<div class="forum2-left-content">
						  			<div class="forum2-left-circle"></div>
						  			<div class="forum2-right-content">
						  				<h4><?php echo $conversation['user_name']; ?> <span class="badge bg-info ms-2"><?php echo $conversation['user_group_name']; ?></span> <?php echo format_date($conversation['created']); ?></h4>
						  				<p><?php echo nl2br($conversation['content']); ?></p>
						  			</div>
						  		</div>
						  	</div>
						  </div>
						  <?php } ?>
						  <?php } else { ?>
						  <p class="text-center">Be the First to Comment!</p>
						  <?php } ?>
						</div>
						<div id="topic-conversation-more">
						</div>						
					</div>
				</div>
			</div>
		</section>
