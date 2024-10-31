<?php
/**
 * new PageType Model for setting Up Pagetypes entirely by Theme/pagemanager.php
 */

namespace Pagemanager\Model;


class PageType
{
    /**
     * @var
     */
    private $type = '';

    /**
     * @var
     */
    private $label = '';

    /**
     * @var
     */
    private $caseIdSource;

    /**
     * PageType constructor.
     * @param $name
     * @param $label
     * @param array $caseIdSource
     */
    public function __construct(
        $type,
        $label,
        $caseIdSource = array(0 => 'default'),
        $register = true
    )
    {
        $this->type = $type;
        $this->label = $label;
        $this->caseIdSource = $caseIdSource;

        if ($register == true) {
            $pageTypes = PageTypes::getInstance();
            $pageTypes->addPageType($this);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getCaseIdSource()
    {
        return $this->caseIdSource;
    }

    /**
     * @param $typeName
     * @return bool
     */
    public function isType($typeName)
    {
        return $typeName == $this->type;
    }

    /**
     * @param $caseId
     * @return bool|mixed|string
     */
    public function getCaseIdInfo($caseId)
    {
        if (is_array($this->caseIdSource)) {
            return $this->caseIdSource[$caseId];
        }

        if ($caseId == 0) {
            return 'default';
        }

        switch ($this->caseIdSource) {
            case 'page':
            case 'post':
                $post = get_post($caseId);
                if ($post instanceof \WP_Post) {
                    return $post->post_title;
                }
                return false;
                break;
            case ('tag' OR 'category' OR 'term'):
                $term = get_term($caseId);
                if ($term instanceof \WP_Term) {
                    return $term->name;
                }
                return false;
                break;
            default:
                $allPostTypes = array_keys(get_post_types());
                if (in_array($this->caseIdSource, $allPostTypes)) {
                    $post = get_post($caseId);
                    if ($post instanceof \WP_Post && $post->post_type == $this->caseIdSource) {
                        return $post->post_title;
                    }
                    break;
                }
                $taxonomies = array_keys(get_taxonomies());
                if (in_array($this->caseIdSource, $taxonomies)) {
                    $term = get_term($caseId, $this->caseIdSource);
                    if ($term instanceof \WP_Term) {
                        return $term->name;
                    }
                    return false;
                    break;
                }
        }
    }
}