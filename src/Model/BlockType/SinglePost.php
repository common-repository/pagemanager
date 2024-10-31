<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;

class SinglePost extends AbstractBlockType
{

    public function setName()
    {
        return 'content';
    }

    public function setLabel()
    {
        return 'Partial';
    }
    public function setIcon()
    {
        return '<i class="fa fa-lg fa-file-text"></i>';
    }

    public function setAttributes()
    {
        return array(
            array(
                'name' => 'post',
                'label' => 'Artikel',
                'input-type' => 'post,partial',
                'default' => false,
                'help' => 'ID oder Titelwort eingeben, dann per Klick auswählen'
            ),
            array(
                'name' => 'layout',
                'label' => 'Layout',
                'help' => 'Bitte wählen',
                'input-type' => 'select',
                'default' => '',
                'options' => array(
                    array(
                        'value' => '',
                        'icon' => '<i class="fa fa-align-center"></i>',
                        'label' => 'standard container',
                    ),
                    array(
                        'value' => 'header',
                        'icon' => '<i class="fa fa-certificate"></i>',
                        'label' => 'category page header with menue',
                    ),
                    array(
                        'value' => 'left',
                        'icon' => '<i class="fa fa-align-left"></i>',
                        'label' => 'Artikel links, Bild rechts',
                    ),
                    array(
                        'value' => 'right',
                        'icon' => '<i class="fa fa-align-right"></i>',
                        'label' => 'Bild links, Artikel rechts',
                    ),
                    array(
                        'value' => 'big',
                        'icon' => '<i class="fa fa-align-justify"></i>',
                        'label' => 'Überschrift, Artikel links, Bild rechts',
                    )
                )
            )
        );
    }
}
