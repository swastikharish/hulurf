
		<section class="forum-header" style="background-image: url(<?php echo site_url('assets/theme/images/forum-bg.jpg'); ?>);">
			<div class="container">
				
				<?php if ($images) { ?>
				<div class="row">
					<div class="col-md-12 text-center pb-5">
						<img src="<?php echo site_url('assets/images/'.$images[0]['image']); ?>" class="img-fluid" alt="">
					</div>
				</div>
				<?php } ?>

				<div class="forum-video-wrapper ctm-con">
				    <div class="row ">
				        <div class="col-md-12 forum-video-left">
				            <div class="row ctn-spa">
				                <h3 class="title col-md-8"><?php echo $forum['title']; ?></h3><div class="pagination_right d-flex align-content-center col-md-4 ctm-df">
				                    
				                </div>
				            </div>
				            <p><?php echo nl2br($forum['short_description']); ?></p>
				        </div>
				    </div>
				    <div>
				        <div><?php echo $forum['description']; ?></div>
				    </div>
				    <?php if ($videos) { ?>
				    <div class="col-md-12 mg-tt">
			        <div class="row">
		            <div class="col-md-6 col-video">
		                <div class="video1">
	                    <div class="ratio ratio-16x9">
	                    	<?php if ($videos[0]['type'] == 'mp4') { ?>
	                      <video id="video-<?php echo $videos[0]['video_id'] ?>" class="trackable-video" style="width: 100%; height: 100%;" controls controlsList="nodownload">
	                        <source src="<?php echo site_url('assets/videos/'.$videos[0]['mp4_video']); ?>" type="video/mp4">
	                        Your browser does not support the video tag.
	                      </video>
	                      <?php } else { ?>
	                      <iframe id="video-<?php echo $videos[0]['video_id'] ?>" width="1280" height="720" src="<?php echo $videos[0]['youtube_video'] ?>?enablejsapi=1" title="<?php echo $videos[0]['name'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	                      <?php } ?>
	                    </div>
		                </div>
		            </div>
		            <?php if (isset($videos[1])) { ?>
		            <div class="col-md-6 col-video">
	                <div class="video2">
	                	<div class="ratio ratio-16x9">
	  									<?php if ($videos[1]['type'] == 'mp4') { ?>
	  	                <video id="video-<?php echo $videos[1]['video_id'] ?>" class="trackable-video" style="width: 100%; height: 100%;" controls controlsList="nodownload">
	  	                  <source src="<?php echo site_url('assets/videos/'.$videos[1]['mp4_video']); ?>" type="video/mp4">
	  	                  Your browser does not support the video tag.
	  	                </video>
	  	                <?php } else { ?>
	  	                <iframe id="video-<?php echo $videos[1]['video_id'] ?>" class="trackable-iframe-video" width="1280" height="720" src="<?php echo $videos[1]['youtube_video'] ?>?enablejsapi=1" title="<?php echo $videos[1]['name'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	  	                <?php } ?>
	  	              </div>
	                </div>
		            </div>
		          	<?php } ?>
			        </div>
				    </div>
				   	<?php } ?>
				</div>
			</div>
		</section>
		
		<section class="forum-section">
			<div class="container">
				<div class="row">
					<div class="col-6">
						<div class="table-heading">
							<h3>Topics (<?php echo $count_topics; ?>)</h3>
						</div>
					</div>
					<div class="col-6">
						<?php if ($category_access) { ?>
						<div class="search-forum from-group position-relative me-3">
							<form id="topic-search">
								<input type="text" placeholder="search" name="q" class="form-control" value="<?php echo $this->input->get('q'); ?>">
								<svg class="position-absolute" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
									<path
										d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
								</svg>
							</form>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="forum-card2 forum-list">
					<?php if ($category_access) { ?>
					<div class="pagination-section">
						<div class="pagination_left">
							<?php echo $this->pagination->create_links(); ?>
						</div>
						<div class="pagination_right d-flex align-content-center">
							
						</div>
					</div>
					<?php } ?>
					<div class="forum-card-body">
						<?php if ($category_access && $topics) { ?>
						<?php foreach ($topics as $key => $topic) { ?>
						<div class="row<?php echo ($key > 0) ? ' mt-4' : ''; ?>">
							<div class="col-md-6 col-lg-7">
								<div class="forum2-left-content">
									<div class="forum2-left-circle"></div>
									<div class="forum2-right-content">
										<h4><a href="<?php echo site_url('topic/'.$topic['slug']); ?>"><?php echo $topic['title']; ?></a><?php if (!empty($topic['pdf'])) { ?> <a class="ms-3 pdf-link" href="<?php echo site_url('assets/documents/'.$topic['pdf']); ?>" target="_blank"><i class="fa fa-file-pdf-o"></i></a><?php } ?></h4>
										<p>By <?php echo $topic['user_name']; ?>, <?php echo format_date($topic['created']); ?></p>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-lg-2">
								<div class="post_like">
									<span><?php echo $this->Topic_model->countTopicConversations($topic['topic_id']); ?></span>
									0 View
								</div>
							</div>
							<div class="col-md-4 col-lg-3">
								<?php
								$recent_conversation = $this->Topic_model->recentTopicConversation($topic['topic_id']);
								if ($recent_conversation) {
								?>
								<div class="service_job">
									<div class="service_job_left">
										<img src="<?php echo $assets_path; ?>/theme/images/service-job.jpg" class="img-fluid" alt="">
									</div>
									<div class="service_job_right">
										<h3><?php echo $recent_conversation['user_name']; ?></h3>
										<p><?php echo format_date($recent_conversation['created']); ?></p>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						<?php } else { ?>
						<?php if ($category_access) { ?>
						<p class="text-center">No topics!</p>
						<?php } else { ?>
						<h4 class="text-center text-danger">For paid users only!</h4>
						<p class="text-center">Price: <?php echo format_currency($forum['category_price']); ?></p>
						<?php if (!$this->user_session_data) { ?>
						<p class="text-center"><a href="<?php echo site_url('/login'); ?>">Login</a> required to see topics!</p>
						<?php } ?>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</section>
