<?php
/*
PageblockTemplateLabel: Four cards with titles
PageblockTemplateValue: four-titles
PageblockTemplateIcon: layout-four-titles
PageblockTemplateBlocktype: selectedposts,termposts
PageblockTemplateQuantity: 4
LayoutSort: 17
*/
require_once "partials/_helper_output.php";
?>
<div class="container pageblock pageblock-posts-<?php echo esc_attr(strtolower($this->blockSettings["layout"])); ?>">
    <div class="row" style="max-height: 350px;">
        <div class="col-md-3">
            <?php postOutput($this->posts[0], "post-title.php");?>
        </div>
        <div class="col-md-3">
            <?php postOutput($this->posts[1], "post-title.php");?>
        </div>
        <div class="col-md-3">
            <?php postOutput($this->posts[2], "post-title.php");?>
        </div>
        <div class="col-md-3">
            <?php postOutput($this->posts[3], "post-title.php");?>
        </div>
    </div>
</div>
<br>