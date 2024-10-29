<?php
/**
 * Display users recent posts
 *
 * @template author-page-recent-posts.php
 */
?>
<div id="abp-author-recent-posts" class="groupfix">
    <div id="abp-author-recent-posts-lists">
        <h3>Recent Posts</h3>
        <?php while(self::$recent_posts->have_posts()) : self::$recent_posts->the_post(); ?>
            <div class="abp-author-post-block">
                <div class="abp-author-post-thumnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(); ?>
                    </a>
                </div>
                <div class="abp-author-post-content">
                    <h2 class="abp-author-post-title">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <p><?php echo substr(get_the_excerpt(), 0,200); ?></p>
                    <a class="abp-author-rm-btn" href="<?php the_permalink(); ?>">Read More</a>
                </div>
             </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</div>
