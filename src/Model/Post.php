<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 01.09.15
 * Time: 11:40
 */

namespace Pagemanager\Model;

class Post
{
    const TABLE_POSTS = 'wp_posts';
    const TABLE_USERS = 'wp_users';
    const PARAMETER_LIMIT_MAX = 20;

    /**
     * @var array
     */
    protected $requestParameter = array();

    /**
     * @var array
     */
    public $result = array();

    /**
     * @var array
     */
    protected $queryWhere = array();

    /**
     * @var int
     */
    protected $queryLimit = 10;

    /**
     * offset
     * @var int
     */
    protected $queryOffset = 0;

    /**
     * post types
     * @var array
     */
    protected $queryPostType = array(
        'post'
    );

    /**
     * @var string
     */
    protected $orderBy = " post_date DESC ";


    /**
     * post ID
     * @var
     */
    protected $ID;

    /**
     * smallest post ID
     * @var
     */
    protected $queryMinId;

    /**
     * largest post ID
     * @var
     */
    protected $queryMaxId;

    /**
     * @var
     */
    protected $queryMinDate;

    /**
     * @var array
     */
    protected $categoryList = array();

    /**
     * @param \WP_Post $post
     * @return string
     */
    protected function formatPostDateToISO8601(\WP_Post $post)
    {
        $date = new \DateTime($post->post_date);
        return $date->format('c');
    }

    /**
     * @param \WP_Post $post
     * @return string
     */
    protected function formatPostDateGmtToISO8601(\WP_Post $post)
    {
        $date = new \DateTime($post->post_date_gmt);
        return $date->format('c');
    }

    /**
     * Constructor
     * @param array $requestParameter
     */
    public function __construct(Array $args)
    {
        $this->requestParameter = $args;
        $this->getPostData();
        return $this;
    }

    /**
     * collects the result data
     * @return $this
     */
    public function getPostData()
    {
        $args = array();

        foreach ($this->requestParameter as $parameterName => $parameterValue) {
            switch ($parameterName) {
                case 'id':
                    $this->ID = (int)$parameterValue;
                    break;
                case 'limit':
                    $this->queryLimit = (int)$parameterValue;
                    break;
                case 'offset':
                    $this->queryOffset = (int)$parameterValue;
                    break;
                case 'author':
                    $args['author'] = (int)$parameterValue;
                    break;
                case 'max_id':
                    $this->queryMaxId = (int)$parameterValue;
                    break;
                case 'min_id':
                    $this->queryMinId = (int)$parameterValue;
                    break;
                case 'min_date':
                    $dateArray = explode('-', $parameterValue);
                    if (count($dateArray) == 3 && checkdate($dateArray[1], $dateArray[2], $dateArray[0])) {
                        $this->queryMinDate = (string)$parameterValue;
                    }
                    break;
                case 'post_type':
                    $this->queryPostType = $parameterValue;
                    break;
                case 'terms-exclude':
                    $termsArray = array();
                    foreach ($parameterValue as $value) {
                        if (strlen($value) > 0) {
                            $termId = (integer)$value;
                        } else {
                            continue;
                        }
                        $term = get_term($termId, 'category');
                        if (is_object($term)) {
                            $termsArray['category'][] = $termId;
                            $subCategories = get_categories(
                                array('child_of' => $termId)
                            );
                            foreach ($subCategories as $subCategory) {
                                $termsArray['category'][] = $subCategory->term_id;
                            }
                        } else {
                            $termsArray['post_tag'][] = $termId;
                        }
                    }
                    if (isset($termsArray['post_tag']) && count($termsArray['post_tag']) > 0) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'post_tag',
                            'field' => 'term_id',
                            'terms' => $termsArray['post_tag'],
                            'operator' => 'NOT IN'
                        );
                    }
                    if (isset($termsArray['category']) && count($termsArray['category']) > 0) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'category',
                            'field' => 'term_id',
                            'terms' => $termsArray['category'],
                            'operator' => 'NOT IN'
                        );
                    }
                    if (count($args['tax_query'])>1) {
                        $args['tax_query']['relation'] ='AND';
                    }
                    break;
                case 'terms':
                    $termsArray = array();
                    foreach ($parameterValue as $value) {
                        if (strlen($value) > 0) {
                            $termId = (integer)$value;
                        } else {
                            continue;
                        }
                        $term = get_term($termId, 'category');
                        if (is_object($term)) {
                            $termsArray['category'][] = $termId;
                            $subCategories = get_categories(
                                array('child_of' => $termId)
                            );
                            foreach ($subCategories as $subCategory) {
                                $termsArray['category'][] = $subCategory->term_id;
                            }
                        } else {
                            $termsArray['post_tag'][] = $termId;
                        }
                    }
                    if (isset($termsArray['post_tag']) && count($termsArray['post_tag']) > 0) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'post_tag',
                            'field' => 'term_id',
                            'terms' => $termsArray['post_tag'],
                            'operator' => 'IN'
                        );
                    }
                    if (isset($termsArray['category']) && count($termsArray['category']) > 0) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'category',
                            'field' => 'term_id',
                            'terms' => $termsArray['category'],
                            'operator' => 'IN'
                        );
                    }
                    if (count($args['tax_query'])>1) {
                        $args['tax_query']['relation'] ='AND';
                    }
                    break;
                case 'ids':
                    if (!is_array($parameterValue)) {
                        $postIds = explode(",", $parameterValue);
                    } else {
                        $postIds = $parameterValue;
                    }
                    foreach ($postIds as $postId) {
                        if ((int)$postId > 0) {
                            $args['post__in'][] = $postId;
                        }
                    }
                    break;
                case 'excludeIDs':
                case 'exclude_posts':
                    if (count($parameterValue) > 0) {
                        $args['post__not_in'] = $parameterValue;
                    }
                    break;
                case 'orderby':
                    if (is_array($parameterValue)) {
                        if (isset($parameterValue['orderby'])) {
                            $args['orderby'] = $parameterValue['orderby'];
                        }
                        if (isset($parameterValue['order'])) {
                            $args['order'] = $parameterValue['order'];
                        }
                        if (isset($parameterValue['meta_key'])) {
                            $args['meta_key'] = $parameterValue['meta_key'];
                        }
                    }
                    break;
            }
        }

        // ensure limits and offsets are valid
        if ($this->queryLimit < 0) {
            $this->queryLimit = 0;
        }
        // cap upper limit of limit
        if ($this->queryLimit > self::PARAMETER_LIMIT_MAX) {
            $this->queryLimit = self::PARAMETER_LIMIT_MAX;
        }
        if ($this->queryOffset < 0) {
            $this->queryOffset = 0;
        }

        $args['p'] = $this->ID;
        $args['limit'] = $this->queryLimit;
        $args['offset'] = $this->queryOffset;
        $args['post_type'] = $this->queryPostType;

        $args['post_status'] = 'publish';

        add_filter('posts_fields', array($this, 'filterPostsFields'));
        add_filter('posts_join', array($this, 'filterJoins'));
        add_filter('posts_where', array($this, 'filterWhere'));
        add_filter('posts_orderby', array($this, 'filterOrderby'));
        add_filter('post_limits', array($this, 'filterLimit'));

        $this->result = new \WP_Query($args);

        remove_filter('posts_fields', array($this, 'filterPostsFields'));
        remove_filter('posts_join', array($this, 'filterJoins'));
        remove_filter('posts_where', array($this, 'filterWhere'));
        remove_filter('posts_orderby', array($this, 'filterOrderby'));
        remove_filter('post_limits', array($this, 'filterLimit'));

        return $this;
    }

    /**
     * posts_fields filter hook
     * @param $sql
     * @return string
     */
    public function filterPostsFields($sql)
    {
        return $sql;
    }

    /**
     * posts_join filter hook
     * @param $sql
     * @return string
     */
    public function filterJoins($sql)
    {
        $joinSql = array();
        return $sql . ' ' . implode(' ', $joinSql);
    }

    /**
     * posts_where filter hook
     * @param $sql
     * @return string
     */
    public function filterWhere($sql)
    {
        $sql .= $this->filterMaxId();
        $sql .= $this->filterMinId();
        $sql .= $this->filterMinDate();
        return $sql;
    }

    /**
     * posts_orderby filter hook
     * @param $sql
     * @return string
     */
    public function filterOrderby($sql)
    {
        switch (true) {
            case (isset($this->requestParameter['ids']) && count($this->requestParameter['ids']) > 0):
                $sql = ' FIELD (`' . self::TABLE_POSTS . '`.`ID`, '
                    . implode(', ', $this->requestParameter['ids']) . ' )';
                break;
        }

        return $sql;
    }

    /**
     * @return string
     */
    public function filterMaxId()
    {
        if (isset($this->queryMaxId)) {
            return ' AND  < ' . $this->queryMaxId;
        }
        return '';
    }

    /**
     * @return string
     */
    public function filterMinId()
    {
        if (isset($this->queryMinId)) {
            return ' AND `' . self::TABLE_POSTS . '`.`ID` > ' . $this->queryMinId;
        }
        return '';
    }

    /**
     * @return string
     */
    public function filterMinDate()
    {
        if (isset($this->queryMinDate)) {
            return ' AND `' . self::TABLE_POSTS . '`.`post_date` >= \'' . $this->queryMinDate . "'";
        }
        return '';
    }

    /**
     * post_limits filter hook
     * @param $sql
     * @return string
     */
    public function filterLimit($sql)
    {
        if (!isset($this->requestParameter['ids']) || count($this->requestParameter['ids']) < 1) {
            return ' LIMIT ' . $this->queryOffset . ', ' . $this->queryLimit;
        } else {
            return ' LIMIT ' . $this->queryOffset . ', ' . count($this->requestParameter['ids']);
        }
    }
}
