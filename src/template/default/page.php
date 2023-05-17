<?php
$site = [
    'title' => $category['title'] . ' - ' . $config->get('site.name@ebcms.web'),
    'keywords' => $category['keywords'],
    'description' => $category['description'],
];
?>
{include common/header@ebcms/cms-web}
<div class="container-xxl">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb d-print-none">
            <li>当前位置：</li>
            <li class="breadcrumb-item"><a href="{echo $router->build('/')}">主页</a></li>
            <li class="breadcrumb-item active">{$category.title}</li>
        </ol>
    </nav>
    <div class="py-1 my-4 text-center">
        <h1 class="display-4 mb-3">{$category.title}</h1>
    </div>
    <div class="my-3 body">
        {echo $category['content']}
    </div>
</div>
{include common/footer@ebcms/cms-web}