<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 01.09.15
 * Time: 18:42
 */

namespace Pagemanager\Controller\Pageblock;

use Pagemanager\Model\Post;

class TermPosts extends AbstractPageBlock
{
    protected $template = 'posts';

    protected $featuredPosts = array();
    protected $cachedCats = array();

    protected function init(){
        if (!isset($this->blockSettings['terms']) || $this->blockSettings['terms'] == "") {
            $object = get_queried_object();
            if ($object instanceof \WP_Term) {
                $this->blockSettings['terms'] = (string)$object->term_id;
            }
        }
        if (isset($this->blockSettings['postType'])) {
            $this->postTypes = $this->blockSettings['postType'];
        }
    }
}
