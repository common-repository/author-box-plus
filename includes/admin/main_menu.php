<?php
/**
 * Dashboard menu under (User > Authors)
 *
 * @since 1.0
 */

if (!defined("ABSPATH")) exit;

global $pagenow;
?>
<div class="wrap">
    <div id="icon-users" class="icon32"><br/></div>
    <h2>Authors Management</h2>

    <h2 class="nav-tab-wrapper">
        <?php
        $current = array_keys($this->tabs);
        $current = $current[0];
        if (isset($_GET['tab'])) {
            $current = $_GET['tab'];
        }
        foreach ($this->tabs as $tab => $name) {
            $class = ($tab === $current) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='" . admin_url('users.php?page=' . ABP_ADMIN_PAGE) . "&tab=$tab'>$name</a>";
        }
        ?>
    </h2>

    <?php
    if ($pagenow === 'users.php' && $_GET['page'] === ABP_ADMIN_PAGE) {
        switch ($current) {
            case 'author-settings':
                include_once ABP_ADMIN_TEMPLATES . 'settings.php';
                break;
            case 'author-list':
                $this->abp_author_list_table = new ABP_Author_List_Table();
                include_once ABP_ADMIN_TEMPLATES . 'authors_list.php';
                break;
            default:
                break;
        };
    }
    ?>

    <style type="text/css" scoped>
        .wrap {
            position: relative;
        }
        .abp-attribution {
            position: absolute;
            top: 13px;
            right: 0;
        }
    </style>
    <div class="abp-attribution">
        <i>Proudly created by</i> <a href="https://wooninjas.com" target="_blank">WooNinjas</a>
    </div>
</div>