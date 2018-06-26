<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="panel status panel-success">
            <div class="panel-heading">
                <h1 class="panel-title text-center"><?= $post_count ?></h1>
            </div>
            <div class="panel-body text-center">
                <strong>Posting yang Diterbitkan</strong>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="panel status panel-success">
            <div class="panel-heading">
                <h1 class="panel-title text-center"><?= $active_comments_count ?></h1>
            </div>
            <div class="panel-body text-center">
                <strong>Komentar Aktif</strong>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="panel status panel-danger">
            <div class="panel-heading">
                <h1 class="panel-title text-center"><?= $modded_comments_count ?></h1>
            </div>
            <div class="panel-body text-center">
                <strong>Komentar Menunggu Moderasi</strong>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="panel status panel-warning">
            <div class="panel-heading">
                <h1 class="panel-title text-center"><?= $notification_count ?></h1>
            </div>
            <div class="panel-body text-center">
                <strong>Langganan ke Konten Baru</strong>
            </div>
        </div>
    </div>
</div>
