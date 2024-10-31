<div class="wrap">
<script type="application/javascript">
    var $allSettings = <?php echo wp_json_encode($settings); ?>;
    var $version = '<?php echo esc_attr($hash); ?>';
    var $blocksConfig = <?php echo wp_json_encode($blocksConfig); ?>;
    var $blocksSettings = <?php echo wp_json_encode($blockSettings); ?>;
    var $settingsIdInfo = <?php echo wp_json_encode($settingsIdInfo); ?>;
    var $pageList = <?php echo wp_json_encode
    ($pageTypeList); ?>;
//    var $adCases = <?php //echo json_encode(\Pagemanager\Model\Settings::getAdCases()); ?>//;
</script>
<div style="background-color: #FFFFFF; padding-left: 10px;padding-top: 5px;">
    <span style="float:right;padding: 0px 5px;">v<?php echo PAGEMANAGER_PLUGIN_VERSION;?></span>
    <img src="<?php echo PAGEMANAGER_PLUGIN_URL.'images/pagemanager_logo_greenblue.png'; ?>" style="height: 70px;">
</div>
<h2 class="nav-tab-wrapper printful-tabs" style="background-color: #FFFFFF;">
    <a href="?page=pagemanager" class="nav-tab nav-tab-active">Pagemanager</a>
    <a href="?page=settings_pagemanager" class="nav-tab ">Settings</a>
</h2>
<h1>
<?php echo __('Page blocks for ', 'Pagemanager');?>
    <span class="page-target" target="<?php echo esc_attr($blocksPage) ?>"><?php echo esc_textarea($pageTypeTitle); ?></span> |
    <?php if (!$pageCaseTitle) : ?>
        <span class="page-target-case error">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
            <?php echo __('This ID is not valid', 'Pagemanager');?>
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        </span>
    <?php else : ?>
        <span class="page-target-case" case="<?php echo esc_attr($blocksPageCase); ?>"><?php echo esc_textarea($pageCaseTitle); ?></span>
    <?php endif; ?>
</h1>
<hr size="1">
<div class="btn-group-sm btn-group-add manage"></div>
<div id="sortable" class="manage">
    <?php if ($settingsInst->errorMessage != '') {
        echo '<div class="info-warning">' . esc_html($settingsInst->errorMessage) . '</div>';
    } ?>
</div>
<div id="pages">
    <div id="history" class="panel panel-pages collapsed">
        <div class="panel-heading" data-target="globalheader"><strong>History</strong></div>

        <?php if (pm_fs()->can_use_premium_code()) : ?>
        <p><?php foreach ($blockHistory as $historyEntry): ?>
                <?php if ($historyEntry->status != 1) { ?>
                    <a href="?page=pagemanager&target=<?php echo esc_attr($blocksPage); ?>&case=<?php echo esc_attr($blocksPageCase); ?>&restore=<?php echo urlencode(
                        base64_encode($historyEntry->ID)
                    ); ?>"><?php echo esc_html($historyEntry->created_at . ' - ' . $historyEntry->user_nicename); ?></a><br/>
                <?php } else { ?>
                    <b>aktuell:</b><br/><?php echo esc_html($historyEntry->created_at . ' - ' . $historyEntry->user_nicename); ?>
                    <br/><br/>
                <?php } ?>
            <?php endforeach; ?>
        </p>

        <?php else: ?>
            <p>This feature is only available for PageManager Premium Licenses. <a href="/wp-admin/admin.php?page=pagemanager-pricing">Please upgrade.</a></p>
        <?php endif; ?>
        
        <div class="pull-center"><i class="fa fa-angle-up"></i><i class="fa fa-angle-down"></i></div>
    </div>
</div>
</div>