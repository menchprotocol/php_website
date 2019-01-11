
<script src="/js/custom/actionplan-miner-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<div id="load_w_frame" class="fixed-box hidden">
    <h5 class="badge badge-h badge-h-max" id="w_title"></h5>
    <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
        <a href="javascript:void(0)" onclick="$('#load_w_frame').addClass('hidden');$('#loaded-ws').html('');"><i
                    class="fas fa-times-circle"></i></a>
    </div>
    <div class="grey-box grey-box-w" style="padding-bottom: 10px;">
        <iframe class="ajax-frame hidden" src=""></iframe>
        <span class="frame-loader hidden"><i class="fas fa-spinner fa-spin"></i> Loading...</span></div>
</div>
