<?php

declare(strict_types=1);

namespace App\Ebcms\CmsWeb\Http;

use App\Ebcms\Web\Http\Common;
use PsrPHP\Template\Template;

class Search extends Common
{

    public function get(
        Template $template
    ) {
        return $template->renderFromFile('search@ebcms/cms-web');
    }
}
