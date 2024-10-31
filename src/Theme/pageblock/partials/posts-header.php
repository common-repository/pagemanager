
<div class="container pageblock pageblock-posts-<?php echo esc_textarea($this->blockSettings["title"]); ?> bg-white border">
    <?php
    if (isset($this->blockSettings["title"]) AND strlen($this->blockSettings["title"])>0) : ?>
    <div class="row pageblock-title<?php if ($this->blockSettings['layout']!='bigger') echo " mb-3"; ?>">
        <div class="col text-center">
            <h3 class=""><?php echo esc_textarea($this->blockSettings["title"]); ?></h3>
        </div>
    </div>
    <?php endif; ?>
