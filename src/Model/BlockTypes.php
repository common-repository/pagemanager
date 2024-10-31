<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 17.05.14
 * Time: 13:01
 */

namespace Pagemanager\Model;

use Pagemanager\Model\BlockType\Html;
use Pagemanager\Model\BlockType\Script;
use Pagemanager\Model\BlockType\SelectedPosts;
use Pagemanager\Model\BlockType\TermPosts;
use Pagemanager\Model\BlockType\TheContent;

class BlockTypes
{
    /**
     * @var
     */
    protected static $instance;

    /**
     * @var
     */
    protected $blocktypes;

    /**
     *
     */
    protected function __construct()
    {
        $this->init();
    }

    /**
     * @return AbstractSingleton
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    protected function init()
    {
        $this->blocktypes = array(
            //Base Blocks
            new TermPosts(),
            new SelectedPosts(),
            new Html()
        );
    }


    public function addBlockType(BlockType $blockType)
    {
        array_push($this->blocktypes, $blockType);
        return $this;
    }

    public function removeBlockType($blockTypeName)
    {
        foreach ($this->blocktypes as $blocktypeKey => $blocktype) {
            if ($blocktype->getName() == $blockTypeName) {
                unset($this->blocktypes[$blocktypeKey]);
            }
        }
        return $this;
    }

    /**
     * @param $blockTypeName
     * @param array $layoutSettings
     * @return $this
     */
    public function addBlockTypeLayout($blockTypeName, $layoutSettings = array())
    {
        foreach ($this->blocktypes as $blocktypeKey => $blocktype) {
            if ($blocktype->name == $blockTypeName) {
                foreach($blocktype->attributes as $attrKey => $attribute) {
                    if ($attribute['name'] == 'layout') {
                        array_push($blocktype->attributes[$attrKey]['options'], $layoutSettings);
                    }
                }
            }
        }
        return $this;
    }

    public function getAllTypes()
    {
        return (array)$this->blocktypes;
    }

    public static function getBlockTypeByName($name) {
        $instance = self::getInstance();
        foreach ($instance->getAllTypes() as $blocktype) {
            if ($blocktype->name == $name) {
                return $blocktype;
            }
        }
        return false;
    }


}
