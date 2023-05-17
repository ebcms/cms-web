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
            <div class="mb-4">
                <div class="fs-3 mb-2 border-bottom border-2 border-dark pb-2 text-dark">{$category.title}</div>
                <div class="text-muted fw-light">{echo $category['content']}</div>
            </div>

            {if $category['filters']}
            <div class="p-3 border border-dark mb-3">
                {foreach array_filter(explode(PHP_EOL, $category['filters'])) as $_filt}
                <?php list($_label, $_name, $_items) = explode(',', trim($_filt) . ',,,,'); ?>
                <dl>
                    <dt class="mb-2">{$_label}</dt>
                    <dd>
                        <a href="{echo $router->build('/ebcms/cms-web/category', array_merge($request->get(), ['id'=>$category['id'], $_name => null, 'page'=>null]))}" class="badge rounded-pill {if $request->get($_name)==''}bg-dark text-white{else}bg-light text-dark{/if}">不限</a>
                        {foreach explode('|', $_items) as $_it}
                        <a href="{echo $router->build('/ebcms/cms-web/category', array_merge($request->get(), ['id'=>$category['id'], $_name => $_it, 'page'=>null]))}" class="badge rounded-pill {if $request->get($_name)==$_it}bg-dark text-white{else}bg-light text-dark{/if}">{$_it}</a>
                        {/foreach}
                    </dd>
                </dl>
                {/foreach}
            </div>
            {/if}

            {if $request->get('page', 1)==1}
            <?php
            $top_contents = $db->select('ebcms_cms_content', '*', [
                'category_id' => $category['id'],
                'state' => 1,
                'attrs[~]' => '%"栏目置顶"%',
                'ORDER' => [
                    'id' => 'DESC',
                ],
            ]);
            ?>
            {foreach $top_contents as $vo}
            <div class="mb-3 pb-3 d-flex">
                <div>▪</div>
                <div class="ms-2">
                    <div class="mb-2">
                        <a href="{echo $router->build('/ebcms/cms-web/content', ['category_id'=>$vo['category_id'], 'id'=>$vo['id']])}" class="text-dark fw-light h5">{$vo.title}<span class="text-danger">[顶]</span></a>
                    </div>
                    <div class="text-muted" style="font-size:.8em;">
                        {:date('Y-m-d H:i:s', $vo['create_time'])} 浏览 {$vo.click} 次
                    </div>
                </div>
            </div>
            {/foreach}
            {/if}

            <?php
            $where = [
                'category_id' => $category['id'],
                'state' => 1,
            ];
            if ($category['content_priority'] == 1) {
                $where['ORDER'] = [
                    'priority' => 'DESC',
                    'id' => 'DESC',
                ];
            } else {
                $where['ORDER'] = [
                    'id' => 'DESC',
                ];
            }
            for ($i = 0; $i <= 5; $i++) {
                if ($request->get('filter' . $i)) {
                    $where['filter' . $i . '[~]'] = '%"' . $request->get('filter' . $i) . '"%';
                }
            }
            $total = $db->count('ebcms_cms_content', $where);

            $page = $request->get('page', 1, ['intval']) ?: 1;
            $page_num = 20;
            $where['LIMIT'] = [($page - 1) * $page_num, $page_num];

            $contents = $db->select('ebcms_cms_content', '*', $where);
            $pagination = $container->get(\PsrPHP\Pagination\Pagination::class)->render($page, $total, $page_num);
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

            <nav class="my-3">
                <ul class="pagination">
                    {foreach $pagination as $v}
                    {if $v=='...'}
                    <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
                    {elseif isset($v['current'])}
                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
                    {else}
                    <li class="page-item"><a class="page-link" href="{echo $router->build('/ebcms/cms-web/category', array_merge($request->get(), ['id'=>$category['id'], 'page'=>$v['page']]))}">{$v.page}</a></li>
                    {/if}
                    {/foreach}
                </ul>
            </nav>
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