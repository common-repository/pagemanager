<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 02.08.16
 * Time: 13:23
 */

namespace Pagemanager\Model\PageType;

class PageType
{
    public static function getInstance($className)
    {
        $class = 'Pagemanager\Model\PageType' . '\\' . $className;
        $object = new $class();
        if ($object instanceof AbstractPageType) {
            return $object;
        }
        return false;
    }
}
