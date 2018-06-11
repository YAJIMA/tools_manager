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
        <div class="col-md-5">
            <h2>設定</h2>
            <!-- 設定フォーム TODO: サイト別の仕組みを実装 -->
            <?php echo form_open('lp_setting/update', array('class' => 'form'), array('back' => 'lp_setting')); ?>
            <?php echo form_close(); ?>
        </div>
        <div class="col-md-5">
        </div>
    </div>
    <div class="row" id="footer">

    </div>
