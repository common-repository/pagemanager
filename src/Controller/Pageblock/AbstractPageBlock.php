<?php

namespace Pagemanager\Controller\Pageblock;

use Pagemanager\Model\Post;
use Pagemanager\Controller\PageBlock\PageBlockInterface;
use Pagemanager\PageManager;

class AbstractPageBlock
{

    const TEMPLATE_DIRECTORY = '/pageblock/';
    const TEMPLATE_FILESUFFIX = '.php';

    /*
     * avoid displaying posts multiple times on a page
     */
    protected $showPostsMultiple = false;

    public $type;
    protected $template;
    protected $orderby = array(
        'orderby' => 'post_date',
        'order' => 'DESC'
    );
    protected $postTypes = array('post');
    public $blockSettings;
    public $blockContent;
    public $blockNavigation = array();
    public $posts = array();
    protected $templatePath;
    protected $pageController;
    public $themePath;


    protected function getPageType()
    {
        global $wp_query;
        if (isset($this->pageController->queryType)) {
            return $this->pageController->queryType;
        }
        $pageType = 'notfound';

        if ($wp_query->is_page) {
            $pageType = is_front_page() ? 'front' : 'page';
        } elseif ($wp_query->is_home) {
            $pageType = 'home';
        } elseif ($wp_query->is_single) {
            $pageType = ($wp_query->is_attachment) ? 'attachment' : 'single';
        } elseif ($wp_query->is_category) {
            $pageType = 'category';
        } elseif ($wp_query->is_tag) {
            $pageType = 'tag';
        } elseif ($wp_query->is_tax) {
            $pageType = 'tax';
        } elseif ($wp_query->is_archive) {
            if ($wp_query->is_day) {
                $pageType = 'day';
            } elseif ($wp_query->is_month) {
                $pageType = 'month';
            } elseif ($wp_query->is_year) {
                $pageType = 'year';
            } elseif ($wp_query->is_author) {
                $pageType = 'author';
            } else {
                $pageType = 'archive';
            }
        } elseif ($wp_query->is_search) {
            $pageType = 'search';
        } elseif ($wp_query->is_404) {
            $pageType = 'notfound';
        }
        if (isset($this->pageController) && $this->pageController instanceof PageManager) {
            $this->pageController->queryType = $pageType;
        }
        return $pageType;
    }

    protected function setThemePath()
    {
        $currentThemePath = get_stylesheet_directory();

        //TODO: Licencing Code
        if (is_dir($currentThemePath . '/pageblock')){
            $this->themePath = $currentThemePath;
            return $this;
        }
        $this->themePath = PAGEMANAGER_PLUGIN_PATH . 'src/Theme';
        return $this;
    }

    public function __construct($blockSetting, $pageController)
    {
        if (!isset($this->type)) {
            $this->type = $blockSetting['type'];
        }
        if (!isset($this->template)) {
            $this->template = $this->type;
        }
        if (isset($blockSetting['settings'])) {
            $this->blockSettings = $blockSetting['settings'];
        }

        if (isset($blockSetting['settings']['post-type']) && strlen($blockSetting['settings']['post-type']) > 0) {
            $this->postTypes = explode(",", $blockSetting['settings']['post-type']);
        }
        $this->pageController = $pageController;

        if (isset($this->pageController) && $this->pageController->page == 'category'
            && $this->pageController->case != 0
            && array_key_exists('terms', $this->blockSettings)
        ) {
            $terms = explode(",", $this->blockSettings['terms']);
            $case = explode(",", $this->pageController->case);

            $terms = strlen($this->blockSettings['terms']) > 0
                ? array_merge($terms, $case)
                : $case;
            $this->blockSettings['terms'] = implode(",", $terms);
        }
        $this->setThemePath();
        $this->templatePath = self::TEMPLATE_DIRECTORY
            . (isset($this->blockSettings['layout']) && strlen($this->blockSettings['layout']) > 0
                ? $this->template . '-' . strtolower($this->blockSettings['layout'])
                : $this->template)
            . self::TEMPLATE_FILESUFFIX;


        if (!file_exists($this->themePath . $this->templatePath))
        {
            $this->templatePath = self::TEMPLATE_DIRECTORY . $this->template . self::TEMPLATE_FILESUFFIX;
        }

        if(method_exists($this, 'init')) {
            $this->init();
        }
        return $this;
    }

    /**
     * @param $termId
     * @return array
     */
    protected function getSubCategoryIdsByTermId($termId)
    {
        $termsArray = array();
        if (is_numeric($termId) && strlen($termId) > 0) {
            $termId = (integer)$termId;
        } else {
            return $termsArray;
        }
        $term = get_term($termId, 'category');
        if (is_object($term)) {
            $termsArray[] = $termId;
            $subCategories = get_categories(
                array('child_of' => $termId)
            );
            foreach ($subCategories as $subCategory) {
                $termsArray[] = (int)$subCategory->term_id;
            }
        } else {
            $termsArray[] = $termId;
        }
        return $termsArray;
    }

    /**
     * @return $this
     */
    protected function getNavigationLinks()
    {
        if (isset($this->blockSettings['navi-terms']) && strlen($this->blockSettings['navi-terms']) > 0) {
            foreach (explode(",", $this->blockSettings['navi-terms']) as $termId) {
                $term = get_term_by('id', $termId, 'category');
                if (!is_object($term)) {
                    $term = get_term_by('id', $termId, 'post_tag');
                }
                $this->blockNavigation[] = array(
                    'permalink' => get_tag_link($term),
                    'name' => $term->name
                );
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function getPostsByTerms()
    {
        if (isset($this->blockSettings['terms'])) {
            if (isset($this->blockSettings['terms']) && strlen($this->blockSettings['terms']) > 0) {
                $args = array(
                    'terms' => explode(",", $this->blockSettings['terms'])
                );

            }
            if (isset($this->blockSettings['terms-exclude']) && strlen($this->blockSettings['terms-exclude']) > 0) {
                $args['terms-exclude'] = explode(",", $this->blockSettings['terms-exclude']);
            }
            if (isset($this->blockSettings['quantity']) && (int)$this->blockSettings['quantity'] > 0) {
                $args['limit'] = (int)$this->blockSettings['quantity'];
            }

            if ($this->pageController != null && !$this->showPostsMultiple) {
                $args['exclude_posts'] = $this->pageController->usedPosts;
            }

            if (isset($this->blockSettings['exclude-posts']) && count($this->blockSettings['exclude-posts']) > 1) {
                $args['exclude_posts'] = isset($args['exclude-posts'])
                    ? array_merge($args['exclude_posts'], $this->blockSettings['exclude-posts'])
                    : $this->blockSettings['exclude-posts'];
            }

            if (isset($this->blockSettings['pagination']) && $this->blockSettings['pagination'] == 1) {
                $args['pagination'] = true;
                $this->currentPage = (get_query_var('page')) ? get_query_var('page') : 1;
                $args['offset'] = ($this->currentPage - 1) * (int)$this->blockSettings['quantity'];
            }

            if (isset($this->blockSettings['offset'])) {
                $args['offset'] = $this->blockSettings['offset'];
            }

            $args['orderby'] = $this->orderby;

            $args['post_type'] = $this->postTypes;

            if (isset($this->blockSettings['offset']) && (int)$this->blockSettings['offset'] > 0) {
                $args['offset'] = (int)$this->blockSettings['offset'];
            }

            $pageManagerPost = new Post($args);
            $this->posts = array_merge($this->posts, $pageManagerPost->result->posts);
        }
        return $this;

    }

    /**
     * @return $this
     */
    protected function getPostsByIds()
    {
        if (isset($this->blockSettings['posts']) && strlen($this->blockSettings['posts']) > 0) {
            $args = array(
                'ids' => explode(",", $this->blockSettings['posts']),
                'post_type' => (isset($this->blockSettings['post-type'])
                    ? explode(",", $this->blockSettings['post-type'])
                    : 'post'),
            );
            $pageManagerPost = new Post($args);
            $this->posts = array_merge($this->posts, $pageManagerPost->result->posts);
        }
        return $this;
    }

    /**
     * @return $this|mixed
     */
    public function getContent()
    {
        $this->getNavigationLinks();
        $this->getPostsByTerms();
        $this->getPostsByIds();
        foreach ($this->posts as $usedPost) {
            $this->pageController->usedPosts[] = $usedPost->ID;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function renderContent()
    {

        switch(true) {
            case file_exists($this->themePath . $this->templatePath):
                include $this->themePath . $this->templatePath;
                break;
            default:
                echo esc_html("ERROR: <b>" . $this->themePath . $this->templatePath . "</b> is missing <br>");
                break;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getClassAttributes()
    {
        return get_object_vars($this);
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
}
