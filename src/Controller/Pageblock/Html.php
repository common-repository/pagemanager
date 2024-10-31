<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 31.08.15
 * Time: 14:08
 */

namespace Pagemanager\Controller\Pageblock;

class Html extends AbstractPageBlock
{
    public function renderContent()
    {
        //this output adds custom html done by admin in PageManager
        echo (isset($this->blockSettings['allowShortcode']) && $this->blockSettings['allowShortcode'] == 1)
            ? wp_kses_post(stripslashes(do_shortcode($this->blockSettings['code'])))
            : wp_kses_post(stripslashes($this->blockSettings['code']));
    }
}
