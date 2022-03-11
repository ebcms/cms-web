<?php

declare(strict_types=1);

namespace App\Ebcms\CmsWeb;

use App\Ebcms\CmsAdmin\Model\Category as ModelCategory;
use App\Ebcms\CmsWeb\Http\Category;
use App\Ebcms\CmsWeb\Http\Content;
use DigPHP\Framework\AppInterface;
use DigPHP\Framework\Framework;
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
        if (!$categorys = $cache->get('ebcms.cms.categorys')) {
            $categorys = $categoryModel->getAll();
            $cache->set('ebcms.cms.categorys', $categorys, 3600);
        }
        Framework::get('/_search', Search::class);
        foreach ($categorys as $category) {
            $category['_path'] = $category['_path'] ?: ('category-' . $category['id']);
            if (in_array($category['type'], ['page', 'channel', 'list'])) {
                Framework::get('/' . $category['_path'], Category::class, [], [
                    'id' => $category['id'],
                ]);
            }
            if (in_array($category['type'], ['list'])) {
                Framework::get('/' . $category['_path'] . '/{id}', Content::class, [], [
                    'category_id' => $category['id'],
                ]);
            }
        }
    }
}
