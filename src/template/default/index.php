<?php
$site = [
    'title' => $config->get('site.name@ebcms.web') . ' - ' . $config->get('site.title@ebcms.web'),
    'keywords' => $config->get('site.keywords@ebcms.web'),
    'description' => $config->get('site.description@ebcms.web'),
];
?>
{include common/header@ebcms/cms-web}
<div class="container-xxl">
    <div class="row mb-4">
        <div class="col-md-4">
            <?php
            $contents = $db->select('ebcms_cms_content', '*', [
                'state' => 1,
                'attrs[~]' => '%"首页焦点"%',
                'ORDER' => [
                    'id' => 'DESC',
                ],
            ]);
            ?>
            <div id="indexfocus" class="carousel slide mb-3" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    {foreach $contents as $key=>$vo}
                    <button type="button" data-bs-target="#indexfocus" data-bs-slide-to="{$key}" class="{if !$key}active{/if}" aria-current="true" aria-label="Slide {$key}"></button>
                    {/foreach}
                </div>
                <div class="carousel-inner">
                    {foreach $contents as $key=>$vo}
                    <div class="carousel-item {if !$key}active{/if}">
                        <a href="{echo $router->build('/ebcms/cms-web/content', ['category_id'=>$vo['category_id'], 'id'=>$vo['id']])}" target="_blank">
                            <img src="{$vo.cover}" class="d-block w-100" alt="{$vo.title}">
                            <div class="carousel-caption d-none d-md-block">
                                <h5 class="p-2 text-truncate" style="background:#00000085;">{$vo.title}</h5>
                            </div>
                        </a>
                    </div>
                    {/foreach}
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#indexfocus" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#indexfocus" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="col-md-8">
            <?php
            $content = $db->get('ebcms_cms_content', '*', [
                'state' => 1,
                'attrs[~]' => '%"首页头条"%',
                'ORDER' => [
                    'id' => 'DESC',
                ],
            ]);
            ?>
            {if $content}
            <div class="text-center position-relative mb-4 mt-3">
                <div class="display-5 mb-4 text-dark fw-bold text-nowrap text-truncate">{$content.title}</div>
                <div style="line-height: 1.8em;">{$content.description} <a href="{echo $router->build('/ebcms/cms-web/content', ['category_id'=>$content['category_id'], 'id'=>$content['id']])}" class="stretched-link">[详情]</a></div>
            </div>
            {/if}
            <hr>
            <?php
            $contents = $db->select('ebcms_cms_content', '*', [
                'state' => 1,
                'attrs[~]' => '%"首页推荐"%',
                'LIMIT' => 4,
                'ORDER' => [
                    'id' => 'DESC',
                ],
            ]);
            ?>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled mb-3">
                        {foreach $contents as $k => $vo}
                        {if $k%2 == 1}
                        <li class="py-2 text-nowrap text-truncate">
                            <div class="d-flex">
                                <div>▪</div>
                                <div class="ms-2">
                                    <div class="mb-2">
                                        <a href="{echo $router->build('/ebcms/cms-web/content', ['category_id'=>$vo['category_id'], 'id'=>$vo['id']])}" class="text-dark fw-light h5">{$vo.title}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {/if}
                        {/foreach}
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled mb-3">
                        {foreach $contents as $k => $vo}
                        {if $k%2 == 0}
                        <li class="py-2 text-nowrap text-truncate">
                            <div class="d-flex">
                                <div>▪</div>
                                <div class="ms-2">
                                    <div class="mb-2">
                                        <a href="{echo $router->build('/ebcms/cms-web/content', ['category_id'=>$vo['category_id'], 'id'=>$vo['id']])}" class="text-dark fw-light h5">{$vo.title}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        {/if}
                        {/foreach}
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <?php
                $categorys = $container->get(\App\Ebcms\CmsAdmin\Model\Category::class)->getAll();
                ?>
                {foreach $categorys as $vo}
                {if !$vo['pid'] && $vo['_cids']}
                <div class="col-md-6">
                    <div class="mb-4">
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