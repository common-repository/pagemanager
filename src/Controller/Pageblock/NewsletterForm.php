<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 31.08.15
 * Time: 14:08
 */

namespace Pagemanager\Controller\Pageblock;

use Pagemanager\Controller\Pageblock\AbstractPageBlock;

class NewsletterForm
    extends AbstractPageBlock
{
    protected $groups;

    public function getContent()
    {
        $this->groups = json_decode(stripslashes($this->blockSettings['groups']));
        return $this;
    }
}
