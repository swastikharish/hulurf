        <div>
          <span class="thumb-sm avatar avatar-text pull-left">
            <?php echo strtoupper($user_name[0]); ?>              
          </span>
          <div class="m-l-xxl m-b">
            <div>
              <a href><strong><?php echo $user_name; ?></strong></a>
              <label class="label bg-info m-l-xs"><?php echo $user_group_name; ?></label> 
              <span class="text-muted text-xs block m-t-xs">
                <?php echo format_date($created); ?>
              </span>
            </div>
            <div class="m-t-sm"><?php echo nl2br($content); ?></div>
          </div>
        </div>