<?php

namespace Pagemanager\Model\PageType;

abstract class AbstractPageType
{
    public $type;

    public $label;

    public $caseIdSource;

    public function __construct()
    {
        $this->type = $this->setType();
        $this->label = $this->setLabel();
        $this->caseIdSource = $this->setCaseIdSource();
        $this->init();
    }

    /**
     * @return AbstractPageType
     */
    protected function init()
    {
        return $this;
    }

    /**
     * @return string
     */
    abstract public function setType();

    /**
     * @return string
     */
    abstract public function setLabel();

    /**
     * @return string|array
     */
    abstract public function setCaseIdSource();

    /**
     * @param $typeName
     * @return bool
     */
    public function isType($typeName)
    {
        return $typeName == $this->type;
    }

    public function getCaseIdInfo($id)
    {
        if (is_array($this->caseIdSource)) {
            return $this->caseIdSource[$id];
        }

        if ($id == 0) {
            return 'default';
        }

        switch ($this->caseIdSource) {
            case 'page':
            case 'post':
                $post = get_post($id);
                if ($post instanceof \WP_Post && $post->post_type == $this->caseIdSource) {
                    return $post->post_title;
                }
                return false;
                break;
            case 'tag':
            case 'category':
                $term = get_term($id, $this->caseIdSource);
                if ($term instanceof \WP_Term) {
                    return $term->name;
                }
                return false;
                break;
            default:
                $allPostTypes = array_keys(get_post_types());
                if (in_array($this->caseIdSource, $allPostTypes)) {
                    $post = get_post($id);
                    if ($post instanceof \WP_Post && $post->post_type == $this->caseIdSource) {
                        return $post->post_title;
                    }
                    break;
                }
                $taxonomies = array_keys(get_taxonomies());
                if (in_array($this->caseIdSource, $taxonomies)) {
                    $term = get_term($id, $this->caseIdSource);
                    if ($term instanceof \WP_Term) {
                        return $term->name;
                    }
                    return false;
                    break;
                }
        }
    }
}
