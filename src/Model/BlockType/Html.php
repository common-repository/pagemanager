<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;

class Html extends AbstractBlockType
{

    public function setName()
    {
        return 'html';
    }

    public function setLabel()
    {
        return 'HTML';
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
                'default' => false,
                'help' => ''
            ),
            array(
                'name' => 'code',
                'label' => 'Code',
                'input-type' => 'textarea',
                'default' => false,
                'help' => __('valid HTML','pagemanager')
            ),
            array(
                'name' => 'allowShortcode',
                'label' => __('allow shortcodes','pagemanager'),
                'input-type' => 'text',
                'default' => '0',
                'help' =>  'O = No / 1 = Yes',
                'value' => 1,
            ),
        );
    }
}
