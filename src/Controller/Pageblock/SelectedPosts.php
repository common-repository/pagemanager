<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 01.09.15
 * Time: 18:42
 */

namespace Pagemanager\Controller\Pageblock;

class SelectedPosts extends AbstractPageBlock
{
    protected $template = 'posts';

    protected $featuredPosts = array();
    protected $cachedCats = array();


    protected function init(){

        if (isset($this->blockSettings['postType'])) {
            $this->postTypes = $this->blockSettings['postType'];
        }
    }
}
