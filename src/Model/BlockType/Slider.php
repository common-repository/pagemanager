<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;

class Slider extends AbstractBlockType
{

    public function setName()
    {
        return 'sliderposts';
    }

    public function setLabel()
    {
        return 'Slider mit ausgewählten Artikel';
    }
    public function setIcon()
    {
        return '<i class="fa fa-lg fa-toggle-right"></i>';
    }

    public function setAttributes()
    {
        return array(
            array(
                'name' => 'post-type',
                'input-type' => 'hidden',
                'default' => 'post,partial',
            ),
            array(
                'name' => 'title',
                'label' => 'Titel',
                'input-type' => 'text',
                'default' => false,
                'help' => 'nicht ausfüllen, wenn Titel unerwünscht'
            ),
            array(
                'name' => 'title-link',
                'label' => 'Titel Link',
                'input-type' => 'text',
                'default' => false,
                'help' => 'URL - nicht ausfüllen, wenn Verlinkung unerwünscht'
            ),
            array(
                'name' => 'posts',
                'label' => 'Artikel',
                'input-type' => 'post',
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
                        'icon' => '<i class="fa fa-2x fa-toggle-right"></i>',
                    ),
                    array(
                        'value' => 'shelf',
                        'icon' => '<i class="fa fa-2x fa-book"></i>',
                    )
                )
            ),
            array(
                'name' => 'quantity',
                'label' => 'Anzahl der Artikel',
                'input-type' => 'text',
                'default' => 0,
                'help' => ''
            )
        );
    }
}
