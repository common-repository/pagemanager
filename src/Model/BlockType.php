<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 21.06.17
 * Time: 15:56
 */

namespace Pagemanager\Model;


class BlockType
{
    /**
     * @var string
     * name of the block type should be unique
     */
    public $name = '';

    /**
     * @var string
     */
    public $label = '';

    /**
     * @var string
     */
    public $icon = '';

    /**
     * @var string
     */
    public $style = '';

    /**
     * @var string
     */
    public $attributes = array();

    /**
     * @var string
     */
    private $controller = '';

    /**
     * @var string
     */
    private $templateName = '';

    /**
     * @var array
     */
    private $layout = array();

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->iconClass;
    }

    /**
     * @return mixed
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }


    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $templateName
     */
    public function getTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * BlockType constructor.
     * @param $name
     * @param $label
     * @param $icon
     * @param $style
     * @param $attributes
     * @param null $controller
     * @param null $templateName
     * @param null $layout
     */
    public function __construct(
        $name,
        $label,
        $icon,
        $style,
        $attributes,
        $controller = null,
        $templateName = null,
        $layout = null
    )
    {
        $this->name = $name;
        $this->label = $label;
        $this->icon = $icon;
        $this->style = $style;
        $this->attributes = $attributes;
        if ($controller) {
            $this->controller = $controller;
        } else {
            $this->controller = ucfirst($name);
        }
        if ($layout) {
            $this->layout = $layout;
        }
        if ($templateName) {
            $this->templateName = $templateName;
        } else {
            $templateName = strtolower($name);
        }

        return $this;
    }

    public function addLayout(BlockTypeLayout $layoutSettings)
    {
        array_push($this->layout, $layoutSettings);
    }

    public function removeLayout($layoutName) {
        foreach($this->layout as $layoutKey => $layoutObject) {
            if ($layoutObject->getName() == $layoutName) {
                unset($this->layout[$layoutKey]);
            }
        }
    }

    public function getSettingsArray()
    {
        $arrayMapping = array(
            'name' => 'name',
            'label' => 'label',
            'icon' => 'iconClass',
            'attributes' => 'attributes'
        );

        $settingsArray = [];
        foreach($arrayMapping as $key => $attribute) {
            $settingsArray['$key'] = $this->$attribute;
        }

        return $settingsArray;
    }

    /**
     * @return array
     */
    private function getLayoutArray()
    {
        $layoutArray = [];
        foreach ($this->layout as $layoutObject) {
            if ($layoutObject instanceof BlockTypeLayout) {
                array_push($layoutArray, $layoutObject->getLayoutArray());
            }
        }
        if (count($layoutArray) < 1) {
            $layoutArray[] = [
                'name' => '',
                'label' => 'keine Layouts angelegt - no layouts for this block type available',
                'icon' => 'fa fa-warning'
            ];
        }
        return $layoutArray;
    }

    public function activate()
    {
        if (count($this->layout) > 0) {
            foreach ($this->attributes as $attrKey => $attribute) {
                if (is_string($attribute) && $attribute == 'layout') {
                    $this->attributes[$attrKey] = array(
                        'name' => 'layout',
                        'label' => 'Layout',
                        'help' => 'Bitte wählen',
                        'input-type' => 'select',
                        'default' => '',
                        'options' => $this->getLayoutArray()
                    );
                }
            }
        }
        $blockTypes = BlockTypes::getInstance();
        $blockTypes->addBlockType($this);
    }
}
