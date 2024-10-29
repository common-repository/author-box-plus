<?php
/**
 * Authors list under (User > Authors > Authors list)
 *
 * @since 1.0
 */

if (!defined("ABSPATH")) exit;

$this->abp_author_list_table->prepare_items();
?>
<form id="abp-author-list" method="get">
    <?php $this->abp_author_list_table->display() ?>
</form>
