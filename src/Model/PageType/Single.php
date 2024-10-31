<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Single extends AbstractPageType
{
    public function setLabel()
    {
        return __('Post','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_SINGLE;
    }

    public function setCaseIdSource()
    {
        return 'post';
    }
}
