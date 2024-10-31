<?php
/*
PageblockTemplateLabel: One bigger and six additional posts
PageblockTemplateValue: bigger-six
PageblockTemplateIcon: layout-bigger-six
PageblockTemplateBlocktype: selectedposts,termposts
PageblockTemplateQuantity: 7
LayoutSort: 9
*/

require_once "partials/_helper_output.php";

?>

<div class="container pageblock pageblock-posts-<?php echo esc_attr($this->blockSettings["title"]); ?> bg-white border">
    <?php
    if (isset($this->blockSettings["title"]) AND strlen($this->blockSettings["title"])>0) : ?>
    <div class="row pageblock-title">
        <div class="col text-center">
            <h3 class=""><?php echo esc_textarea($this->blockSettings["title"]); ?></h3>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12 mb-3 mt-3" style="min-height: 500px;">
            <?php postOutput($this->posts[0], "post-md12.php") ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[1], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[2], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[3], "post-md4.php") ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[4], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[5], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[6], "post-md4.php") ?>
        </div>
    </div>
</div>
<br>