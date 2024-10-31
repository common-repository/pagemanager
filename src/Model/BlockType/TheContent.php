<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;

class TheContent extends AbstractBlockType
{

    protected $template = 'thecontent';

    public function setName()
    {
        return 'thecontent';
    }

    public function setLabel()
    {
        return __('Current Page/Post Content', 'pagemanager');
    }
    public function setIcon()
    {
        return '<i class="fa fa-lg fa-code"></i>';
    }

    public function setAttributes()
    {
        return array(
            array(
                'name' => 'title',
                'label' => __('Title','pagemanager'),
                'input-type' => 'text',
                'default' => __('The content of the current page/post'),
                'help' => ''
            )
        );
    }
}
