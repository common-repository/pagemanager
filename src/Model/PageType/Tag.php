<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Tag extends AbstractPageType
{
    public function setLabel()
    {
        return __('Tag Page','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_TAG;
    }

    public function setCaseIdSource()
    {
        return 'post_tag';
    }
}
