<?php

declare(strict_types=1);

namespace App\Ebcms\CmsWeb\Http;

use App\Ebcms\CmsAdmin\Model\Category;
use App\Ebcms\Web\Http\Common;
use PsrPHP\Database\Db;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Content extends Common
{

    public function get(
        Db $db,
        Category $categoryModel,
        Request $request,
        Template $template
    ) {
        if (!$content = $db->get('ebcms_cms_content', '*', [
            'state' => 1,
            'OR' => [
                'id' => $request->get('id'),
                'alias' => $request->get('id'),
            ],
        ])) {
            return $this->error('页面不存在！')->withStatus(404);
        }

        if (!$category = $categoryModel->getOne($content['category_id'])) {
            return $this->error('无访问权限~')->withStatus(403);
        }
        if ($category['state'] != 1) {
            return $this->error('无访问权限~')->withStatus(403);
        }

        $db->update('ebcms_cms_content', [
            'click[+]' => 1,
        ], [
            'id' => $content['id'],
        ]);

        if ($content['redirect_uri']) {
            return $this->redirect($content['redirect_uri']);
        }

        $content['extra'] = unserialize($content['extra']);

        return $template->renderFromFile(($content['tpl'] ?: ($category['tpl_content'] ?: 'content')) . '@ebcms/cms-web', [
            'category' => $category,
            'content' => $content,
        ]);
    }
}
