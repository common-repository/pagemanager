<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;

class TermPosts extends AbstractBlockType
{

    public function setName()
    {
        return 'termposts';
    }

    public function setLabel()
    {
        return __( 'Current Posts',  'Pagemanager' );
    }
    public function setIcon()
    {
        return '<i class="fa fa-lg fa-clock-o"></i>';
    }

    public function setAttributes()
    {
        $layoutOptions = $this->getPageblockTemplateSettings('termposts');
        return array(
            array(
                'name' => 'post-type',
                'label' => 'Erlaubte Post-Types',
                'input-type' => 'hidden',
                'default' => 'post',
                'help' => ''
            ),
            array(
                'name' => 'title',
                'label' =>  __( 'Title',  'Pagemanager' ),
                'input-type' => 'text',
                'default' => false,
                'help' => ''
            ),
            array(
                'name' => 'title-link',
                'label' =>  __( 'Title Link',  'Pagemanager' ),
                'input-type' => 'text',
                'default' => false,
                'help' =>  __( 'absolute or relative URL',  'Pagemanager' )
            ),
            array(
                'name' => 'terms',
                'label' =>  __( 'Categories / Tags',  'Pagemanager' ),
                'input-type' => 'term',
                'default' => false,
                'help' =>  __( 'enter ID or title word, then select by click',  'Pagemanager' )
            ),
            array(
                'name' => 'terms-exclude',
                'label' =>  __( 'Excluded categories / tags',  'Pagemanager' ),
                'input-type' => 'term',
                'default' => false,
                'help' =>  __( 'enter ID or title word, then select by click',  'Pagemanager' )
            ),
//            array(
//                'name' => 'featured-posts',
//                'label' => 'Ausgewählte Posts',
//                'input-type' => 'post',
//                'default' => false,
//                'help' => 'ID oder Titelwort eingeben, dann per Klick auswählen, Anzahl siehe Layout (rote Positionen)'
//            ),
            array(
                'name' => 'layout',
                'label' =>  __( 'Layout',  'Pagemanager' ),
                'help' =>  __( 'Please choose',  'Pagemanager' ),
                'input-type' => 'select',
                'default' => false,
                'options' => count($layoutOptions)>0
                    ? $layoutOptions
                    :array(
                    array(
                        'value' => 'lead-five',
                        'icon' => '<i class="layout layout-lead-five"></i>',
                        'label' => 'Lead +5',
                        'quantity' => 6,
                        'featured-pos' => '1,2,5'
                    ),
                    array(
                        'value' => 'lead-six',
                        'icon' => '<i class="layout layout-lead-six"></i>',
                        'label' => 'Lead +6',
                        'quantity' => 7,
                        'featured-pos' => '1,2,3,4'
                    ),
                    array(
                        'value' => 'big-six',
                        'icon' => '<i class="layout layout-big-six"></i>',
                        'label' => 'Big +6',
                        'quantity' => 7,
                        'featured-pos' => '3,6'
                    ),
                    array(
                        'value' => 'big-nine',
                        'icon' => '<i class="layout layout-big-nine"></i>',
                        'label' => 'Big +9',
                        'quantity' => 10,
                        'featured-pos' => '3,6,9'
                    ),
                    array(
                        'value' => 'big-twelve',
                        'icon' => '<i class="layout layout-big-twelve"></i>',
                        'label' => 'Big +12',
                        'quantity' => 13,
                        'featured-pos' => '3,6,9,12'
                    ),
                    array(
                        'value' => 'bigger-six',
                        'icon' => '<i class="layout layout-bigger-six"></i>',
                        'label' => 'Bigger +6',
                        'quantity' => 7,
                        'featured-pos' => '3,6'
                    ),
                    array(
                        'value' => 'bigger-nine',
                        'icon' => '<i class="layout layout-bigger-nine"></i>',
                        'label' => 'Bigger +9',
                        'quantity' => 10,
                        'featured-pos' => '3,6,9'
                    ),
                    array(
                        'value' => 'bigger-twelve',
                        'icon' => '<i class="layout layout-bigger-twelve"></i>',
                        'label' => 'Bigger +12',
                        'quantity' => 13,
                        'featured-pos' => '3,6,9,12'
                    ),
                    array(
                        'value' => 'big',
                        'icon' => '<i class="layout layout-big"></i>',
                        'label' => 'Big',
                        'quantity' => 1,
                        'featured-pos' => ''
                    ),
                    array(
                        'value' => 'bigger',
                        'icon' => '<i class="layout layout-bigger"></i>',
                        'label' => 'Bigger',
                        'quantity' => 1,
                        'featured-pos' => ''
                    ),
                    array(
                        'value' => 'list',
                        'icon' => '<i class="layout layout-list"></i>',
                        'label' => 'List',
                        'quantity' => 7,
                        'featured-pos' => ''
                    ),
                    array(
                        'value' => 'four-cards',
                        'icon' => '<i class="layout layout-four-cards"></i>',
                        'label' => 'Four cards',
                        'quantity' => 4,
                        'featured-pos' => ''
                    ),
                    array(
                        'value' => 'four-titles',
                        'icon' => '<i class="layout layout-four-titles"></i>',
                        'label' => 'Four titles',
                        'quantity' => 4,
                        'featured-pos' => ''
                    ),
                    array(
                        'value' => 'slider',
                        'icon' => '<i class="layout layout-slider"></i>',
                        'label' => 'Slider',
                        'quantity' => 7,
                        'featured-pos' => ''
                    ),
                    array(
                        'value' => 'three',
                        'icon' => '<i class="layout layout-three"></i>',
                        'label' => '3er Block',
                        'quantity' => 3,
                        'featured-pos' => '2'
                    ),
                    array(
                        'value' => 'six',
                        'icon' => '<i class="layout layout-six"></i>',
                        'label' => '6er Block',
                        'quantity' => 6,
                        'featured-pos' => '2,5'
                    ),
                    array(
                        'value' => 'nine',
                        'icon' => '<i class="layout layout-nine"></i>',
                        'label' => '9er Block',
                        'quantity' => 9,
                        'featured-pos' => '2,5,7'
                    )
                )
            ),
            array(
                'name' => 'quantity',
                'label' => __('Number of posts', 'pageanager'),
                'input-type' => 'text',
                'input-attribute' => 'disabled',
                'default' => 0,
                'help' => ''
            ),
            array(
                'name' => 'featured-pos',
                'label' => 'Position der ausgewählten Inhalte',
                'input-type' => 'hidden',
                'input-attribute' => 'disabled',
                'default' => 0,
                'help' => 'beginnt mit 0, Komma-getrennt'
            ),
            array(
                'name' => 'pagination',
                'label' => __('Activate Pagination', 'Pagemanager'),
                'input-type' => 'checkbox',
                'value' => '1',
                'default' => 0,
            )
//        ,
//            array(
//                'name' => 'adspace',
//                'label' => 'Pageblock soll Werbung enthalten',
//                'input-type' => 'checkbox',
//                'default' => 0,
//                'value' => 1,
//                'help' => ''
//            ),
//            array(
//                'name' => 'postList',
//                'label' => 'weitere Beiträge',
//                'input-type' => 'checkbox',
//                'default' => 0,
//                'value' => 1,
//                'help' => '(Liste funktioniert nur ohne Werbung)'
//            )
        );
    }
}
