<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 12:38
 */

namespace Pagemanager\Model\PageType;

class Author extends AbstractPageType
{
    public function setLabel()
    {
        return 'Autorenseite';
    }

    public function setType()
    {
        return PageTypeConstants::PAGETYPE_AUTHOR;
    }

    public function setCaseIdSource()
    {
        return array(
            0 => 'default'
        );
    }
}
