<?php
$site = [
    'title' => $content['title'] . ' - ' . $config->get('site.name@ebcms.web'),
    'keywords' => $content['keywords'],
    'description' => $content['description'] ?: mb_substr(str_replace(["\r", "\n", "\t"], '', trim(strip_tags($content['body']))), 0, 250),
];
?>
{include common/header@ebcms/cms-web}
<div class="container-xxl">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3 d-print-none">
            <li>当前位置：</li>
            <li class="breadcrumb-item"><a href="{echo $router->build('/')}">主页</a></li>
            {foreach $category['_pitems'] as $vo}
            {if $vo['type']!='group'}<li class="breadcrumb-item"><a href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$vo['id']])}">{$vo.title}</a></li>{/if}
            {/foreach}
            <li class="breadcrumb-item"><a href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$category['id']])}">{$category.title}</a></li>
            <li class="breadcrumb-item active">{$content.title}</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-9 mb-4">
            <div class="py-1 mb-4 mt-4">
                <h1 class="display-5 mb-2">{$content.title}</h1>
                <div class="text-muted text-monospace">
                    <span class="me-2">更新时间：{:date('Y-m-d H:i', $content['update_time'])}</span>
                    <span>浏览：{$content.click}</span>
                    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.4.4/build/qrcode.min.js"></script>
                    <span class="d-none d-md-inline-block ms-2" tabindex="0" id="content-qrcode" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Disabled popover" title="手机扫码浏览" data-bs-html="true">
                        手机扫码浏览 <img src="data:image/svg+xml;base64,PHN2ZyB0PSIxNjM0MDg5Njg3NjAwIiBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjI0MTYiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHBhdGggZD0iTTg1LjMxMiA4NS4zMTJWMzg0SDM4NFY4NS4zMTJIODUuMzEyek0wIDBoNDY5LjI0OHY0NjkuMjQ4SDBWMHogbTE3MC42MjQgMTcwLjYyNGgxMjh2MTI4aC0xMjh2LTEyOHpNMCA1NTQuNjI0aDQ2OS4yNDh2NDY5LjI0OEgwVjU1NC42MjR6IG04NS4zMTIgODUuMzEydjI5OC42MjRIMzg0VjYzOS45MzZIODUuMzEyeiBtODUuMzEyIDg1LjMxMmgxMjh2MTI4aC0xMjh2LTEyOHpNNTU0LjYyNCAwaDQ2OS4yNDh2NDY5LjI0OEg1NTQuNjI0VjB6IG04NS4zMTIgODUuMzEyVjM4NGgyOTguNjI0Vjg1LjMxMkg2MzkuOTM2eiBtMzgzLjkzNiA2ODIuNTZIMTAyNHY4NS4zNzZoLTI5OC43NTJWNjM5LjkzNkg2MzkuOTM2VjEwMjMuODcySDU1NC42MjRWNTU0LjYyNGgyNTUuOTM2djIxMy4yNDhoMTI4VjU1NC42MjRoODUuMzEydjIxMy4yNDh6IG0tMjk4LjYyNC01OTcuMjQ4aDEyOHYxMjhoLTEyOHYtMTI4eiBtMjk4LjYyNCA4NTMuMjQ4aC04NS4zMTJ2LTg1LjMxMmg4NS4zMTJ2ODUuMzEyeiBtLTIxMy4zMTIgMGgtODUuMzEydi04NS4zMTJoODUuMzEydjg1LjMxMnoiIGZpbGw9IiMyNjI2MjYiIHAtaWQ9IjI0MTciPjwvcGF0aD48L3N2Zz4=" alt="" height="15">
                    </span>
                </div>
                <script>
                    QRCode.toDataURL(location.href, {}, function(err, url) {
                        if (err) throw err
                        var obj = document.getElementById('content-qrcode')
                        obj.setAttribute("data-bs-content", "<img src=\"" + url + "\">");
                    });
                </script>
            </div>
            <div class="p-4 m-5 bg-light border border-dark mb-3 position-relative" style="line-height:1.8em;letter-spacing:1px;">
                <div class="fs-3 p-2 fw-bold position-absolute top-0 start-0 translate-middle bg-white border border-dark">导读</div>
                <div class="font-monospace" style="text-indent: 2em;">{$content['description']?:mb_substr(strip_tags($content['body']),0,200)}</div>
            </div>
            <div class="mb-5 body">
                {echo $content['body']}
                <div class="text-muted fs-5">（正文完）</div>
            </div>
            <div class="text-center d-print-none mb-3">
                <a class="btn btn-outline-primary" href="{echo $router->build('/')}" role="button">返回首页</a>
                <a class="btn btn-outline-primary" href="{echo $router->build('/ebcms/cms-web/category', ['id'=>$category['id']])}" role="button">更多内容</a>
                <button type="button" class="btn btn-outline-primary" onclick="alert('请按 ctrl+p 打印');">打印</button>
            </div>
        </div>
        <div class="col-md-3 d-print-none">
            <?php
            if ($tags = json_decode($content['tags'], true)) {
                $relations = $db->select('ebcms_cms_content', '*', [
                    'state' => 1,
                    'LIMIT' => 10,
                    'tags[~]' => $tags,
                    'ORDER' => [
                        'id' => 'DESC',
                    ],
                ]);
            }
            ?>
            <div class="mb-3 bg-light p-3">
                <div class="fs-3 mb-4 border-bottom border-2 border-dark pb-2 text-dark">相关内容</div>
                {foreach $relations??[] as $vo}
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