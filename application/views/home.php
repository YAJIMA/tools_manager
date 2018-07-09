<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="row" id="maincontents">
        <div class="col-md-2">
            <nav class="nav nav-pills flex-column">
            <?php if (isset($menues['link_item'])) : ?>
                <?php foreach ($menues['link_item'] as $item) : ?>
                    <a class="nav-link <?php if ($item['active'] == "active") : ?>active<?php endif; ?>" href="<?php echo $item['href']; ?>">
                        <?php echo $item['text']; ?>
                        <?php if ($item['active'] == "active") : ?><span class="sr-only">(current)</span><?php endif; ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
            </nav>
        </div>
        <div class="col-md-6">
            <h2>ダッシュボード</h2>
            <p>管理トップページです。利用したいツールを以下のリンクから選んで下さい。</p>
            <ul>
                <li><a href="<?php echo base_url('lowpages'); ?>">低品質ページツール</a></li>
            </ul>
        </div>
        <div class="col-md-4">
            <h2>ヒント</h2>
            <div class="list-group">
                <div href="#" class="list-group-item flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">グループ管理</h5>
                        <small class="text-muted">2018/07/05</small>
                    </div>
                    <p class="mb-1">グループ内に、ユーザー、およびサイトを所属させます。グループをクライアント、ユーザーを担当者、サイトをサイトで管理することを基準に設計されています。</p>
                    <small class="text-muted">&nbsp;</small>
                </div>
                <div href="#" class="list-group-item flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">低品質ページツール</h5>
                        <small class="text-muted">2018/07/05</small>
                    </div>
                    <p class="mb-1">低品質ページツールは、Google キャッシュ、および So-net インデックス数を元にページの品質を評価します。</p>
                    <small class="text-muted">&nbsp;</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
