<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Index extends AbstractPageType
{
    public function setLabel()
    {
        return __('Index Page','Pagemanager');
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_INDEX;
    }

    public function setCaseIdSource()
    {
        return array(
            0 => 'default'
        );
    }
}