<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;


class Script extends AbstractBlockType
{

    public function setName()
    {
        return 'script';
    }

    public function setLabel()
    {
        return 'Script';
    }
    public function setIcon()
    {
        return '<i class="fa fa-lg fa-th"></i>';
    }

    public function setAttributes()
    {
        $layoutOptions = $this->getPageblockTemplateSettings('script');
        return array(
            array(
                'name' => 'title',
                'label' =>  __( 'Title',  'Pagemanager' ),
                'input-type' => 'text',
                'default' => false,
                'help' => ""
            ),
            array(
                'name' => 'variablevalues',
                'label' => 'Variable & Values',
                'input-type' => 'textarea',
                'default' => false,
                'help' => 'JSON'
            ),
            array(
                'name' => 'layout',
                'label' =>  __( 'Layout',  'Pagemanager' ),
                'help' =>  __( 'Please choose',  'Pagemanager' ),
                'input-type' => 'select',
                'default' => false,
                'options' => count($layoutOptions)>0
                    ? $layoutOptions
                    :array()
            )
        );
    }
}
