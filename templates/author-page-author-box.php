<?php
/**
 * Display author box on top of profile page
 *
 * @template author-page-author-box.php
 */
?>
<div id="abp-about-author" class="groupfix">
    <div class="abp-author-img-social">
        <h2><?php echo abp_get_author_name(false); ?></h2>
        <?php echo abp_get_avatar(178); ?>
        <?php $social_links = abp_get_social_links(); if ($social_links) :?>
            <ul class="abp-author-social">
                <?php foreach ($social_links as $link) :?>
                    <li class="<?php echo $link['class']; ?>">
                        <a href="<?php echo $link['url'] ?>"><?php echo $link['title']; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php endif; ?>
    </div>
    <div class="abp-author-bio">
        <p><?php echo abp_get_author_bio(); ?></p>
    </div>
</div>
