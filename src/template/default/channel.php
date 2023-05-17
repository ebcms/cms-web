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
        <ol class="breadcrumb">
            <li>当前位置：</li>
            <li class="breadcrumb-item"><a href="{echo $router->build('/')}">主页</a></li>
            {foreach $category['_pitems'] as $vo}
            {if $vo['type']!='group'}<li class="breadcrumb-item"><a href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$vo['id']])}">{$vo.title}</a></li>{/if}
            {/foreach}
            <li class="breadcrumb-item active">{$category.title}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <?php
                $categorys = $container->get(\App\Ebcms\CmsAdmin\Model\Category::class)->getAll();
                ?>
                {foreach $categorys as $vo}
                {if $vo['pid']==$category['id'] && $vo['state']==1}
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="mb-4 border-bottom border-2 border-dark pb-2 d-flex justify-content-between align-items-end">
                            <div>
                                {if $vo['type']!='group'}
                                <span class="fs-3 fw-normal"><a href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$vo['id']])}" class="text-dark">{$vo['title']}</a></span>
                                {else}
                                <span class="fs-3 fw-normal text-dark">{$vo['title']}</span>
                                {/if}
                            </div>
                            <div class="text-nowrap text-truncate">
                                {if $vo['type']=='group' || $vo['type']=='channel'}
                                {php $_c=0}
                                {foreach $categorys as $_sub}{if $_sub['pid']==$vo['id'] && $_sub['state']==1}{if $_c}<span class="text-muted px-1">۰</span>{else}{php $_c=1}{/if}<a href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$_sub['id']])}" class="fs-6 text-dark">{$_sub['title']}</a>{/if}{/foreach}
                                {else}
                                <a href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$vo['id']])}" class="fs-6 text-dark">更多</a>
                                {/if}
                            </div>
                        </div>
                        <?php
                        $contents = $db->select('ebcms_cms_content', '*', [
                            'category_id' => $vo['_cids'],
                            'state' => 1,
                            'LIMIT' => 5,
                            'ORDER' => [
                                'id' => 'DESC'
                            ]
                        ]);
                        ?>
                        {foreach $contents as $vo}
                        <div class="mb-3 pb-3 d-flex">
                            <div>▪</div>
                            <div class="ms-2">
                                <div class="mb-2">
                                    <a href="{echo $router->build('/ebcms/cms-web/content', ['category_id'=>$vo['category_id'], 'id'=>$vo['id']])}" class="text-dark fw-light h5">{$vo.title}</a>
                                </div>
                                <div class="text-muted" style="font-size:.8em;">
                                    {:date('Y-m-d H:i:s', $vo['create_time'])} 浏览 {$vo.click} 次
                                </div>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                </div>
                {/if}
                {/foreach}
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3 bg-light p-3">
                <?php
                $contents = $db->select('ebcms_cms_content', '*', [
                    'category_id' => $category['_cids'],
                    'state' => 1,
                    'LIMIT' => 5,
                    'ORDER' => [
                        'id' => 'DESC'
                    ]
                ]);
                ?>
                <div class="fs-3 mb-4 border-bottom border-2 border-dark pb-2 text-dark">最新发布</div>
                {foreach $contents as $vo}
                <div class="mb-3 pb-3 d-flex">
                    <div>▪</div>
                    <div class="ms-2">
                        <div class="mb-2">
                            <a href="{echo $router->build('/ebcms/cms-web/content', ['category_id'=>$vo['category_id'], 'id'=>$vo['id']])}" class="text-dark fw-light h5">{$vo.title}</a>
                        </div>
                        <div class="text-muted" style="font-size:.8em;">
                            {:date('Y-m-d H:i:s', $vo['create_time'])} 浏览 {$vo.click} 次
                        </div>
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>
{include common/footer@ebcms/cms-web}