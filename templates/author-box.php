<?php
/**
 * Displays users recent posts
 *
 * @template author-box.php
 */
?>
<div id="abp-author-box">
    <div id="abp-author-box-gravatar">
        <?php echo abp_get_avatar(); ?>
    </div>
    <div id="abp-author-box-detail">
        <h3><?php echo abp_get_author_name(); ?></h3>

        <?php $social_links = abp_get_social_links(); if ($social_links) : ?>
            <ul class="abp-author-social">
                <?php foreach ($social_links as $link) :?>
                    <li class="<?php echo $link['class']; ?>">
                        <a href="<?php echo $link['url'] ?>"><?php echo $link['title']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p><?php echo abp_get_author_bio(); ?></p>
    </div>
</div>
