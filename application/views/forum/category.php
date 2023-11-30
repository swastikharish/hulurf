
		<section class="forum-section">
			<div class="container">
				<?php foreach ($categories as $category) { ?>
				<?php if (count($category['forums']) > 0) { ?>
				<div class="forum-card">
					<div class="forum-card-header">
						<h3><?php echo $category['name']; ?></h3>
					</div>
					<div class="forum-card-body forum-card-body3">
						<?php foreach ($category['forums'] as $key => $forum) { ?>
						<div class="row<?php echo ($key > 0) ? ' mt-5' : ''; ?>">
							<div class="col-md-6 col-lg-7">
								<div class="forum2-left-content">
									<div class="forum2-left-circle activecolor"><i class="fa-regular fa-comment"></i>
									</div>
									<div class="forum2-right-content">
										<h4><span class="new">New</span><a href="<?php echo site_url('forum/'.$forum['slug']); ?>"><?php echo $forum['title']; ?></a> by <?php echo $forum['user_name']; ?></h4>
										<p><?php echo $forum['short_description'] ?></p>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-lg-2">
								<div class="post_like">
									<span><?php echo $this->Forum_orm_model->countForumTopics($forum['forum_id']); ?></span>
									posts
								</div>
							</div>
							<div class="col-md-4 col-lg-3">
								<?php
								$recent_topic = $this->Forum_orm_model->recentForumTopic($forum['forum_id']);
								if ($recent_topic) {
								?>
								<div class="service_job">
									<div class="service_job_left">
										<img src="<?php echo $assets_path; ?>/theme/images/service-job.jpg" class="img-fluid" alt="">
									</div>
									<div class="service_job_right">
										<h3><?php echo $recent_topic['title']; ?></h3>
										<p>By <b class="byname"><?php echo $recent_topic['user_name']; ?></b> <?php echo format_date($recent_topic['created']); ?></p>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<?php } ?>
			</div>
		</section>
