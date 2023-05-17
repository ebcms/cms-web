<div class="d-print-none">
    <div class="container-xxl mt-4 border-top">
        <div class="pt-3 pb-4 px-3 text-muted text-center" style="font-size: .8em;">
            <div class="mb-2">{$config->get('site.description@ebcms.web')}</div>
            <div class="mb-2">Copyright ©1998-{:date('Y')}&nbsp;{$config->get('site.name@ebcms/web')} All Rights Reserved. 版权所有 侵权必究</div>
            <div class="mb-2"><a href="https://beian.miit.gov.cn/#/Integrated/index" target="_blank">{$config->get('site.beian@ebcms/web')}</a></div>
            <div class="mb-2"><img src="https://static.ebcms.com/love.gif"></div>
            <div class="mb-2">Powered By <a href="http://www.ebcms.com" target="_blank">EBCMS</a>. <a href="{echo $router->build('/ebcms/admin/index')}" class="text-muted ms-1">进入后台</a></div>
        </div>
    </div>
</div>
<script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
</body>

</html>