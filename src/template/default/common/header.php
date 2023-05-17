<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{$site['title']??''} | Powered by EBCMS</title>
    <meta name="keywords" content="{$site['keywords']??''}" />
    <meta name="description" content="{$site['description']??''}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        a {
            text-decoration: none;
        }

        .breadcrumb {
            color: #6c757d;
        }

        .breadcrumb a {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-xxl d-print-none mb-4 mt-4">
        <img src="{$config->get('site.logo@ebcms.web')}" alt="{$config->get('site.name@ebcms.web')}" style="max-height:80px;">
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3 d-print-none">
        <div class="container-xxl">
            <a class="navbar-brand" href="{echo $router->build('/')}">首页</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <?php
                    $categorys = $container->get(\App\Ebcms\CmsAdmin\Model\Category::class)->getAll();
                    ?>
                    {foreach $categorys as $vo}
                    {if $vo['state']==1 && $vo['nav']==1 && $vo['pid']==0}
                    {if $vo['type']=='group'}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown{$vo.id}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {$vo.title}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown{$vo.id}">
                            {foreach $categorys as $_sub}
                            {if $_sub['state']==1 && $_sub['nav']==1 && $_sub['pid']==$vo['id']}
                            <li><a class="dropdown-item" href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$_sub['id']])}">{$_sub.title}</a></li>
                            {/if}
                            {/foreach}
                        </ul>
                    </li>
                    {else}
                    <li class="nav-item">
                        <a class="nav-link" href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$vo['id']])}">{$vo.title}</a>
                    </li>
                    {/if}
                    {/if}
                    {/foreach}
                </ul>
                <form class="d-flex ms-auto" action="{echo $router->build('/ebcms/cms-web/search')}">
                    <input class="form-control me-2" type="search" name="q" placeholder="搜索" aria-label="Search">
                    <button class="btn btn-primary text-nowrap" type="submit">搜索</button>
                </form>
            </div>
        </div>
    </nav>