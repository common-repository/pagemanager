<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Search extends AbstractPageType
{
    public function setLabel()
    {
        return __('Search Result Page','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_SEARCH;
    }

    public function setCaseIdSource()
    {
        return array(
            0 => 'default'
        );
    }
}
