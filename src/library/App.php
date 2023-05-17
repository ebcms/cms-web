<?php

declare(strict_types=1);

namespace App\Ebcms\CmsWeb;

use App\Ebcms\CmsAdmin\Model\Category as ModelCategory;
use App\Ebcms\CmsWeb\Http\Category;
use App\Ebcms\CmsWeb\Http\Content;
use App\Ebcms\CmsWeb\Http\Index;
use App\Ebcms\CmsWeb\Http\Search;
use PsrPHP\Framework\AppInterface;
use PsrPHP\Router\Router;
use Psr\SimpleCache\CacheInterface;

class App implements AppInterface
{

    public static function onInit(
        ModelCategory $categoryModel,
        CacheInterface $cache,
        Router $router
    ) {
        if (defined('EBCMS_CMS_WEB_ROUTE')) {
            return;
        }
        $router->addGroup($router->getSiteRoot(), function (Router $router) use ($cache, $categoryModel) {
            $router->addRoute(['GET'], '[/]', Index::class, [], [], '/ebcms/cms-web/index');
            $router->addRoute(['GET'], '/_search', Search::class, [], [], '/ebcms/cms-web/search');
            if (!$categorys = $cache->get('ebcms.cms.categorys')) {
                $categorys = $categoryModel->getAll();
                $cache->set('ebcms.cms.categorys', $categorys, 3600);
            }
            foreach ($categorys as $category) {
                $category['_path'] = $category['_path'] ?: ('category-' . $category['id']);
                if (in_array($category['type'], ['page', 'channel', 'list'])) {
                    $router->addRoute(['GET'], '/' . $category['_path'], Category::class, [], [
                        'id' => $category['id'],
                    ], '/ebcms/cms-web/category');
                }
                if (in_array($category['type'], ['list'])) {
                    $router->addRoute(['GET'], '/' . $category['_path'] . '/{id}', Content::class, [], [
                        'category_id' => $category['id'],
                    ], '/ebcms/cms-web/content');
                }
            }
        });
    }
}
