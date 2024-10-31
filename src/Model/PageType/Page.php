<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Page extends AbstractPageType
{
    public function setLabel()
    {
        return __('Page','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_PAGE;
    }

    public function setCaseIdSource()
    {
        return 'page';
    }
}
