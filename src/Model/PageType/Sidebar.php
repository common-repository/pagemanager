<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Sidebar extends AbstractPageType
{
    public function setLabel()
    {
        return __('Sidebar','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_SIDEBAR;
    }

    public function setCaseIdSource()
    {
        return array(
            0 => 'default',
//            1 => 'index',
//            2 => 'category',
//            3 => 'tag',
            4 => 'single',
            5 => 'page',
            6 => 'search'
        );
    }
}
