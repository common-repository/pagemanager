<?php
/**
 * Created by PhpStorm.
 * User: Carsten Kermer
 * Date: 17.05.16
 * Time: 16:48
 */

namespace Pagemanager;

use Pagemanager\api\Blocktypes;
use Pagemanager\api\DisplayPosts;
use Pagemanager\api\DisplayTerms;
use Pagemanager\api\GetForm;
use Pagemanager\api\LoadMore;
use Pagemanager\api\SaveSettings;
use Pagemanager\api\User;

class Api
{
    /**
     * @var FrontentUser
     */
    protected static $instance;

    const API_SLUG = "pagemanager-api";

    private function __construct()
    {
    }

    /**
     * @return FrontentUser
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function addEndpoints()
    {
        //add "action as a rewrite tag
        add_rewrite_tag('%action%', '^[a-z0-9_\-]+$');

        //add the endpoint
        //be sure to change "slug" to your unique slug"
        add_rewrite_rule(self::API_SLUG . '/^[a-z0-9_\-]+$/?', 'index.php?action=$matches[1]', 'top');
    }

    public static function doApi()
    {
        global $wp_query;

        //get action, and if set, possibly act
        $action = $wp_query->get('action');


        //be sure to update "slug" to your project's slug
        if ($action && strpos($_SERVER['REQUEST_URI'], self::API_SLUG)) {
            //get class to process with and proceed or return 501 if its invalid
            $action_class = self::actionClass($action);

            $auth_error = $response = false;
            if ($action_class instanceof ApiInterface) {
                if (false == $action_class->check()) {
                    $auth_error = true;
                } else {
                    $params = self::getArgs($action_class->args(), $action_class->method());
                    if (is_array($params)) {
                        $response = $action_class->act($params);
                    } else {
                        $auth_error = true;
                    }
                }
            } else {
                $auth_error = true;
            }

            if (!$auth_error) {
                return self::respond($response, 200);
            } else {
                return self::respond(false, 401);
            }

        }
    }

    public static function actionClass($action)
    {
        switch ($action) {
            case 'display-terms':
                return new DisplayTerms();
            case 'display-posts':
                return new DisplayPosts();
            case 'save-settings':
                return new SaveSettings();
            case 'block-types':
                return new Blocktypes();
            case 'load-more':
                return new LoadMore();
            default:
                return false;
        }
    }

    protected static function getArgs($accept_args, $method = 'GET')
    {
        $method = strtoupper($method);
        switch ($method) {
            case "GET":
                // inputs will get sanitized in api actionClasses
                $input = self::sanitize($_GET, $accept_args);
                break;
            case "POST":
                // inputs will get sanitized in api actionClasses
                $input = self::sanitize($_POST, $accept_args);
                break;
            default:
                return false;
        }
        return $input;
    }

    /**
     * @param $input
     * @param $accept_args
     * @return array|bool
     *
     * this is just a rough sanitizing regarding the accepted arguments of the API endpoint
     * more in depth sanitizing will happen in actionClass of type Blocktype
     *
     */
    protected static function sanitize($input, $accept_args)
    {
        $output = false;
        foreach ($input as $key => $val) {
            if (in_array($key, $accept_args)) {
                //return if its a number
                if (is_int($val) || is_float($val) || is_string($val) || is_array($val) || empty($val)) {
                    $output[$key] = $val;
                }
            }
        }
        if (count($accept_args) == 0) {
            return [];
        }

        return $output;
    }

    protected static function respond($response, $status_code = null)
    {
        if (empty($response)) {
            $status_code = 204;
        }

        if (is_int($response) && $response > 1) {
            $response = false;
            $status_code = $response;
        }

        if (!is_null($status_code)) {
            $status_code = 200;
        }

        status_header($status_code);
        if (is_array($response)) {
            wp_send_json_success($response);
            die();
        } else {
            echo wp_send_json_error($response);
            die();
        }
    }
}
