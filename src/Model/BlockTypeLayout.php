<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 21.06.17
 * Time: 16:37
 */

namespace Pagemanager\Model;


class BlockTypeLayout
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $label = '';

    /**
     * @var string
     */
    private $iconClass = '';

    /**
     * @var int
     */
    private $postQuantity = 0;

    /**
     * @var
     */
    private $featuredPosition;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }

    /**
     * @return int
     */
    public function getPostQuantity()
    {
        return $this->postQuantity;
    }

    /**
     * @return mixed
     */
    public function getFeaturedPosition()
    {
        return $this->featuredPosition;
    }

    /**
     * @param mixed $featuredPosition
     */
    public function setFeaturedPosition($featuredPosition)
    {
        $this->featuredPosition = $featuredPosition;
    }

    /**
     * BlockTypeLayout constructor.
     * @param $name
     * @param $label
     * @param $iconClass
     * @param int $postQuantity
     */
    public function __construct(
        $name,
        $label,
        $iconClass,
        $postQuantity = 0
    )
    {
        $this->name = strtolower($name);
        $this->label = $label;
        $this->iconClass = $iconClass;
        $this->postQuantity = $postQuantity;

        return $this;
    }

    /**
     * @return array
     */
    public function getLayoutArray()
    {
        $layoutArray = [
            'name' => $this->getName(),
            'label' => $this->getLabel(),
            'icon' => $this->getIconClass(),
        ];
        if ($this->postQuantity > 0) {
            $layoutArray['quantity'] = $this->getPostQuantity();
        }
        if (isset($this->postQuantity)) {
            $layoutArray['featured-pos'] = $this->getFeaturedPosition();
        }

        return $layoutArray;
    }

    public function addToBlocktype($blockTypeName) {
        $blockTypes = BlockTypes::getInstance();
        $blockTypes->addBlockTypeLayout($blockTypeName, $this->getLayoutArray());
    }
}