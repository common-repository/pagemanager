<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 31.08.15
 * Time: 14:08
 */

namespace Pagemanager\Controller\Pageblock;

use Pagemanager\Controller\Pageblock\AbstractPageBlock;

class SinglePost
    extends AbstractPageBlock
{
    protected $post;

    public function getContent()
    {
        if (in_array($this->blockSettings['post'], array('', null))) {
            $this->post = get_queried_object();
        } else {
            $this->post = get_post($this->blockSettings['post']);
        }
        return $this;
    }
}
