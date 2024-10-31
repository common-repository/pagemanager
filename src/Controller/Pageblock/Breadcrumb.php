<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 11.09.17
 * Time: 14:22
 */

namespace Pagemanager\Controller\Pageblock;


class Breadcrumb extends AbstractPageBlock
{
    /**
     * @var array
     */
    public $breadcrumb = [];

    protected function addCategoryToBreadCrumb(\WP_Term $termObject){
        //TODO: implement custom taxonomy
        if ($termObject->parent > 0) {
            $term = get_term($termObject->parent);
            $this->addCategoryToBreadCrumb($term);
        }
        $this->addToBreadcrumb(get_term_link($termObject), $termObject->name);
        return $this;
    }

    /**
     * @param \WP_Post $page
     * @return $this
     */
    protected function addPageToBreadCrumb(\WP_Post $page)
    {
        if ($page->post_parent > 0) {
            $parentPage = get_post($page->post_parent);
            $this->addPageToBreadCrumb($parentPage);
        }
        $this->addToBreadcrumb(get_permalink($page), $page->post_title);
        return $this;
    }

    /**
     * @param string $link
     * @param string $label
     * @return $this
     */
    protected function addToBreadcrumb($link = '', $label = '')
    {
        $element = [];
        if ($link != '') {
            $element['link'] = $link;
        }
        if ($label != '') {
            $element['label'] = $label;
        }
        array_push(
            $this->breadcrumb,
            $element
        );
        return $this;
    }

    /**
     * @return $this|mixed
     */
    public function getContent()
    {
        $labelHome = isset($this->blockSettings['homelabel']) && $this->blockSettings['homelabel'] != ''
            ? $this->blockSettings['homelabel']
            : 'Home';


        if (!is_home()) {
            $this->addToBreadcrumb(get_option('home'), $labelHome);

            switch (true) {
                case is_category():
                    if (isset($this->blockSettings['categorylabel']) && $this->blockSettings['categorylabel']) {
                        $this->addToBreadcrumb('', $this->blockSettings['categorylabel']);
                    }
                    $term = get_queried_object();
                    $this->addCategoryToBreadCrumb($term);
                    break;
                case is_single():
                    $post = get_queried_object();
                    $terms = get_the_category($post->ID);
                    if (count($terms)>0 && $terms[0] instanceof \WP_Term) {
                        $this->addCategoryToBreadCrumb($terms[0]);
                    }
                    $this->addToBreadcrumb(get_permalink($post), $post->post_title);
                    break;
                case is_page():
                    $page = get_queried_object();
                    $this->addPageToBreadCrumb($page);
                    break;
                case is_search():
                    $labelSearch = isset($this->blockSettings['searchlabel']) && $this->blockSettings['searchlabel'] != ''
                        ? $this->blockSettings['searchlabel']
                        : 'Suche';
                    $this->addToBreadcrumb('', $labelSearch);
                    break;
            }
        }
        return $this;
    }
}
