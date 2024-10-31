<?php
/**
 * Created by PhpStorm.
 * User: ckermer
 * Date: 31.08.15
 * Time: 13:34
 */

namespace Pagemanager;

use Pagemanager\Controller\Pageblock\AbstractPageBlock;
use Pagemanager\Controller\Pageblock\Breadcrumb;
use Pagemanager\Controller\Pageblock\Html;
use Pagemanager\Controller\Pageblock\Script;
use Pagemanager\Controller\Pageblock\SelectedPosts;
use Pagemanager\Controller\Pageblock\SinglePost;
use Pagemanager\Controller\Pageblock\TermPosts;
use Pagemanager\Controller\Pageblock\TheContent;
use Pagemanager\Model\BlockType;
use Pagemanager\Model\BlockTypes;

class PageBlockFactory
{
    protected static function factor($blockSetting, $pageManager)
    {
        $pageBlock = null;
        //TODO: find Generic Type by Blocktypes

        $blockType = BlockTypes::getBlockTypeByName($blockSetting['type']);

        if ($blockType != false && $blockType instanceof BlockType) {
            $controller = $blockType->getController();
            $pageBlock = new $controller($blockSetting, $pageManager);
            return $pageBlock;
        }

        switch ($blockSetting['type']) {
            case 'singlePost':
                $pageBlock = new SinglePost($blockSetting, $pageManager);
                break;
            case 'termposts':
                $pageBlock = new TermPosts($blockSetting, $pageManager);
                break;
            case 'selectedposts':
                $pageBlock = new SelectedPosts($blockSetting, $pageManager);
                break;
            case 'html':
                $pageBlock = new Html($blockSetting, $pageManager);
                break;
            case 'script':
                $pageBlock = new Script($blockSetting, $pageManager);
                break;
            case 'breadcrumb':
                $pageBlock = new Breadcrumb($blockSetting, $pageManager);
                break;
            case 'thecontent':
                $pageBlock = new TheContent($blockSetting, $pageManager);
                break;
            default:
                $pageBlock = 'BlockType ##' . $blockSetting['type'] . '## is missing! ';
                break;
        }
        return $pageBlock;
    }


    public static function loadContent($blockSetting, $pageManager = null)
    {
        $pageBlock = self::factor($blockSetting, $pageManager);
        if ($pageBlock instanceof AbstractPageBlock) {
            return $pageBlock->getContent();
        } else{
            echo esc_html($pageBlock);
        }
    }

    public static function render($blockSetting, $pageManager = null)
    {
        $pageBlock = self::factor($blockSetting, $pageManager);
        if ($pageBlock instanceof AbstractPageBlock) {
            return $pageBlock->getContent()->renderContent();
        } else {
            echo esc_html($pageBlock);
        }
    }
}
