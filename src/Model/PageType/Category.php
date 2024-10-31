<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Category extends AbstractPageType
{
    public function setLabel()
    {
        return __('Category Page','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_CATEGORY;
    }

    public function setCaseIdSource()
    {
        return 'category';
    }
}
