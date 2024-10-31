<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 19.11.15
 * Time: 19:38
 */

namespace Pagemanager\Model\BlockType;


class NewsletterForm extends AbstractBlockType
{

    public function setName()
    {
        return 'newsletterform';
    }

    public function setLabel()
    {
        return 'Newsletter Formular';
    }
    public function setIcon()
    {
        return '<i class="fa fa-lg fa-envelope-open-o"></i>';
    }

    public function setAttributes()
    {
        return array(
            array(
                'name' => 'title',
                'label' => 'Titel',
                'input-type' => 'text',
                'default' => false,
                'help' => 'nicht ausfüllen, wenn Titel unerwünscht'
            ),
            array(
                'name' => 'formtitle',
                'label' => 'Form Titel',
                'input-type' => 'text',
                'default' => false,
                'help' => 'nicht ausfüllen, wenn Titel unerwünscht'
            ),
            array(
                'name' => 'button',
                'label' => 'Button Text',
                'input-type' => 'text',
                'default' => false,
                'help' => ''
            ),
            array(
                'name' => 'imgurl',
                'label' => 'Bild URL',
                'input-type' => 'text',
                'default' => false,
                'help' => ''
            ),
            array(
                'name' => 'info',
                'label' => 'Zusätzliche Informationen',
                'input-type' => 'text',
                'default' => false,
                'help' => ''
            ),
            array(
                'name' => 'groups',
                'label' => 'Gruppen',
                'input-type' => 'text',
                'default' => "[['group[10025][4294967296]','mce-group[10025]-10025-23'],['group[10021][1]','mce-group[10021]-10021-0']]",

                'help' => 'Komma separiert'
            )
        );
    }
}
