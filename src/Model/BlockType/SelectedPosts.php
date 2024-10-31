<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;


class SelectedPosts extends AbstractBlockType
{

    public function setName()
    {
        return 'selectedposts';
    }

    public function setLabel()
    {
        return  __( 'Selected Posts',  'Pagemanager' );
    }
    public function setIcon()
    {
        return '<i class="fa fa-lg fa-star"></i>';
    }

    public function setAttributes()
    {
        $layoutOptions = $this->getPageblockTemplateSettings('selectedposts');
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
                'help' =>  __( 'please do not fill out if not needed',  'Pagemanager' )
            ),
            array(
                'name' => 'title-link',
                'label' =>  __( 'Title Link',  'Pagemanager' ),
                'input-type' => 'text',
                'default' => false,
                'help' => ''
            ),
            array(
                'name' => 'layout',
                'label' =>  __( 'Layout',  'Pagemanager' ),
                'help' =>  __( 'Please choose',  'Pagemanager' ),
                'input-type' => 'select',
                'default' => false,
                'options' =>  count($layoutOptions)>0
                    ? $layoutOptions
                    : array(
                    array(
                        'value' => 'lead-five',
                        'icon' => '<i class="layout layout-lead-five"></i>',
                        'label' => 'Lead +5',
                        'quantity' => 6
                    ),
                    array(
                        'value' => 'lead-six',
                        'icon' => '<i class="layout layout-lead-six"></i>',
                        'label' => 'Lead +6',
                        'quantity' => 7
                    ),
                    array(
                        'value' => 'big-six',
                        'icon' => '<i class="layout layout-big-six"></i>',
                        'label' => 'Big +6',
                        'quantity' => 7
                    ),
                    array(
                        'value' => 'big-nine',
                        'icon' => '<i class="layout layout-big-nine"></i>',
                        'label' => 'Big +9',
                        'quantity' => 10
                    ),
                    array(
                        'value' => 'big-twelve',
                        'icon' => '<i class="layout layout-big-twelve"></i>',
                        'label' => 'Big +12',
                        'quantity' => 13
                    ),
                    array(
                        'value' => 'bigger-six',
                        'icon' => '<i class="layout layout-bigger-six"></i>',
                        'label' => 'Bigger +6',
                        'quantity' => 7
                    ),
                    array(
                        'value' => 'bigger-nine',
                        'icon' => '<i class="layout layout-bigger-nine"></i>',
                        'label' => 'Bigger +9',
                        'quantity' => 10
                    ),
                    array(
                        'value' => 'bigger-twelve',
                        'icon' => '<i class="layout layout-bigger-twelve"></i>',
                        'label' => 'Bigger +12',
                        'quantity' => 13
                    ),
                    array(
                        'value' => 'big',
                        'icon' => '<i class="layout layout-big"></i>',
                        'label' => 'Big',
                        'quantity' => 1
                    ),
                    array(
                        'value' => 'bigger',
                        'icon' => '<i class="layout layout-bigger"></i>',
                        'label' => 'Bigger',
                        'quantity' => 1
                    ),
                    array(
                        'value' => 'three',
                        'icon' => '<i class="layout layout-three"></i>',
                        'label' => '3er Block',
                        'quantity' => 3
                    ),
                    array(
                        'value' => 'six',
                        'icon' => '<i class="layout layout-six"></i>',
                        'label' => '6er Block',
                        'quantity' => 6
                    ),
                    array(
                        'value' => 'nine',
                        'icon' => '<i class="layout layout-nine"></i>',
                        'label' => '9er Block',
                        'quantity' => 9
                    ),
                    array(
                        'value' => 'four-cards',
                        'icon' => '<i class="layout layout-four-cards"></i>',
                        'label' => 'Four Cards',
                        'quantity' => 4
                    ),
                    array(
                        'value' => 'four-titles',
                        'icon' => '<i class="layout layout-four-titles"></i>',
                        'label' => 'Four Titles',
                        'quantity' => 4
                    ),
                    array(
                        'value' => 'slider',
                        'icon' => '<i class="layout layout-slider"></i>',
                        'label' => 'Slider',
                        'quantity' => 7
                    )
                )
            ),
            array(
                'name' => 'quantity',
                'label' => __('Number of posts', 'pagemanager'),
                'input-type' => 'text',
                'input-attribute' => 'disabled',
                'default' => 0,
                'help' => ''
            ),
            array(
                'name' => 'posts',
                'label' =>  __( 'Posts',  'Pagemanager' ),
                'input-type' => 'post',
                'default' => false,
                'help' =>  __( 'enter ID or title word, then select by click',  'Pagemanager' )
            )
        );
    }
}
