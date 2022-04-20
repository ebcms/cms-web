<?php

declare(strict_types=1);

namespace App\Ebcms\CmsWeb;

use App\Ebcms\CmsAdmin\Model\Category as ModelCategory;
use App\Ebcms\CmsWeb\Http\Category;
use App\Ebcms\CmsWeb\Http\Content;
use App\Ebcms\CmsWeb\Http\Index;
use App\Ebcms\CmsWeb\Http\Search;
use Ebcms\Framework\AppInterface;
use Ebcms\Framework\Framework;
use Psr\SimpleCache\CacheInterface;

class App implements AppInterface
{

    public static function onDispatch(
        ModelCategory $categoryModel,
        CacheInterface $cache
    ) {
        if (defined('EBCMS_CMS_WEB_ROUTE')) {
            return;
        }
        Framework::get('[/]', Index::class, [], [], '/ebcms/cms-web/index');
        Framework::get('/_search', Search::class, [], [], '/ebcms/cms-web/search');
        if (!$categorys = $cache->get('ebcms.cms.categorys')) {
            $categorys = $categoryModel->getAll();
            $cache->set('ebcms.cms.categorys', $categorys, 3600);
        }
        foreach ($categorys as $category) {
            $category['_path'] = $category['_path'] ?: ('category-' . $category['id']);
            if (in_array($category['type'], ['page', 'channel', 'list'])) {
                Framework::get('/' . $category['_path'], Category::class, [], [
                    'id' => $category['id'],
                ], '/ebcms/cms-web/category');
            }
            if (in_array($category['type'], ['list'])) {
                Framework::get('/' . $category['_path'] . '/{id}', Content::class, [], [
                    'category_id' => $category['id'],
                ], '/ebcms/cms-web/content');
            }
        }
    }
}
