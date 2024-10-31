<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Categorypage extends AbstractPageType
{
    public function setLabel()
    {
        return __('Category Page','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_CATEGORYPAGE;
    }

    public function setCaseIdSource()
    {
        return 'post';
    }
}
