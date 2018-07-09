<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="row" id="maincontents">
        <div class="col-md-2">
            <nav class="nav nav-pills flex-column">
            <?php if (isset($menues['link_item'])) : ?>
                <?php foreach ($menues['link_item'] as $item) : ?>
                    <a class="nav-link <?php if ($item['active'] == "active") : ?>active<?php endif; ?>" href="<?php echo $item['href']; ?>" <?php if (isset($item['target'])) : echo 'target="'.$item['target'].'"'; endif; ?>>
                        <?php echo $item['text']; ?>
                        <?php if ($item['active'] == "active") : ?><span class="sr-only">(current)</span><?php endif; ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
            </nav>
        </div>
        <div class="col-md-6">
            <h2>低評価ページ</h2>
        </div>
        <div class="col-md-4">
            <h2>更新履歴</h2>
            <div class="list-group">
                <div href="#" class="list-group-item flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small class="text-muted">3 days ago</small>
                    </div>
                    <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                    <small>Donec id elit non mi porta.</small>
                </div>
                <div href="#" class="list-group-item flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small class="text-muted">3 days ago</small>
                    </div>
                    <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                    <small class="text-muted">Donec id elit non mi porta.</small>
                </div>
                <div href="#" class="list-group-item flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">List group item heading</h5>
                        <small class="text-muted">3 days ago</small>
                    </div>
                    <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p>
                    <small class="text-muted">Donec id elit non mi porta.</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="footer">
    </div>
