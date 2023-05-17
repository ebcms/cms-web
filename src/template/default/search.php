<?php
$site = [
    'title' => '搜索:' . $request->get('q') . ' - ' . $config->get('site.name@ebcms.web'),
    'keywords' => $request->get('q'),
    'description' => $request->get('q'),
];
?>
{include common/header@ebcms/cms-web}
<div class="container-xxl">
    <div class="row">
        <div class="col-md-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li>当前位置：</li>
                    <li class="breadcrumb-item"><a href="{echo $router->build('/')}">主页</a></li>
                    <li class="breadcrumb-item"><a href="{echo $router->build('/ebcms/cms-web/search')}">搜索</a></li>
                </ol>
            </nav>
            <div class="my-4">
                <form method="GET">
                    <div class="input-group mb-3">
                        <input type="search" name="q" value="{:$request->get('q')}" class="form-control" style="max-width: 250px;" placeholder="请输入关键词，最少2个字符！" aria-label="请输入关键词，最少2个字符！" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" id="button-addon2">搜索</button>
                        </div>
                    </div>
                </form>
            </div>

            {if $request->get('q')}
            <?php
            $where = [
                'state' => 1,
                'ORDER' => [
                    'id' => 'DESC',
                ],
                'OR' => [
                    'title[~]' => '%' . $request->get('q') . '%',
                    'keywords[~]' => '%' . $request->get('q') . '%',
                    'description[~]' => '%' . $request->get('q') . '%',
                    'tags[~]' => '%' . $request->get('q') . '%',
                    'body[~]' => '%' . $request->get('q') . '%',
                ]
            ];
            $total = $db->count('ebcms_cms_content', $where);

            $page = $request->get('page', 1, ['intval']) ?: 1;
            $page_num = 20;
            $where['LIMIT'] = [($page - 1) * $page_num, $page_num];

            $contents = $db->select('ebcms_cms_content', '*', $where);
            $pagination = $container->get(\PsrPHP\Pagination\Pagination::class)->render($page, $total, $page_num);
            ?>

            <div class="list-group">
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

            <nav class="my-3">
                <ul class="pagination">
                    {foreach $pagination as $v}
                    {if $v=='...'}
                    <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
                    {elseif isset($v['current'])}
                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
                    {else}
                    <li class="page-item"><a class="page-link" href="{echo $router->build('/ebcms/cms-web/search', array_merge($_GET, ['page'=>$v['page']]))}">{$v.page}</a></li>
                    {/if}
                    {/foreach}
                </ul>
            </nav>
            {/if}
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