<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 31.08.15
 * Time: 14:08
 */

namespace Pagemanager\Controller\Pageblock;

use Pagemanager\Controller\Pageblock\AbstractPageBlock;

class Script
    extends AbstractPageBlock
{
    protected $template = 'script';
    protected $values;

    public function getContent()
    {
        $this->values =  json_decode(str_replace('\"','"', $this->blockSettings['variablevalues']));
        return $this;
    }
}
