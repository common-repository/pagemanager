<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 31.08.15
 * Time: 13:40
 */

namespace Pagemanager\Model\BlockType;

interface InterfaceBlockType
{
    public function setAttributes();
    public function setName();
    public function setLabel();
    public function setIcon();
    public function setStyle();
}
