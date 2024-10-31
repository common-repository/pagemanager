<?php

/*
PageblockTemplateLabel: Nine posts
PageblockTemplateValue: nine
PageblockTemplateIcon: layout-nine
PageblockTemplateBlocktype: selectedposts,termposts
PageblockTemplateQuantity: 9
LayoutSort: 3
*/

require_once "partials/_helper_output.php";

?>

<div class="container pageblock pageblock-posts-<?php echo esc_attr($this->blockSettings["title"]); ?> bg-white border">
    <?php
if (isset($this->blockSettings["title"]) AND strlen($this->blockSettings["title"])>0) : ?>
    <div class="row pageblock-title<?php if ($this->blockSettings['layout']!='bigger') echo " mb-3"; ?>">
        <div class="col text-center">
            <h3 class=""><?php echo esc_textarea($this->blockSettings["title"]); ?></h3>
        </div>
    </div>
<?php endif; ?>
    <div class="row mt-3">
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[0], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[1], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[2], "post-md4.php") ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[3], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[4], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[5], "post-md4.php") ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[6], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[7], "post-md4.php") ?>
        </div>
        <div class="col-md-4" style="min-height: 350px;">
            <?php postOutput($this->posts[8], "post-md4.php") ?>
        </div>
    </div>
</div>
<br>