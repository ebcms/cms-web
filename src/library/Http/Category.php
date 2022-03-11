<?php

declare(strict_types=1);

namespace App\Ebcms\CmsWeb\Http;

use App\Ebcms\CmsAdmin\Model\Category as ModelCategory;
use App\Ebcms\Web\Http\Common;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Category extends Common
{

    public function get(
        ModelCategory $categoryModel,
        Request $request,
        Template $template
    ) {
        if (!$category = $categoryModel->getOne($request->get('id'))) {
            return $this->error('页面不存在~')->withStatus(404);
        }
        if ($category['state'] != 1) {
            return $this->error('页面不存在~')->withStatus(404);
        }

        if ($category['type'] == 'group') {
            return $this->error('页面不存在~')->withStatus(404);
        }

        if ($category['redirect_uri']) {
            return $this->redirect($category['redirect_uri']);
        }

        return $template->renderFromFile(($category['tpl_category'] ?: $category['type']) . '@ebcms/cms-web', [
            'category' => $category,
        ]);
    }
}
