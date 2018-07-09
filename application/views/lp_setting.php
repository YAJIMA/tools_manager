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
        <div class="col-md-5">
            <h2>サイトを選択</h2>
            <!-- 設定フォーム TODO: サイト別の仕組みを実装 -->
            <div class="list-group">
                <?php foreach ($sites as $site) : ?>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php if (uri_string() == 'lp_setting/site/'.$site['id']) : ?>active<?php endif; ?>" href="<?php echo base_url('lp_setting/site/'.$site['id']); ?>"><?php echo $site['name']; ?>
                        <span class="badge badge-primary badge-pill"><?php echo $site['group_name']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-5">
            <!-- TODO: サイト選択されてからフォーム表示 -->
            <?php if ( ! empty($site_id)) : ?>
            <h2><?php echo $site_data['name']; ?></h2>
            <?php echo form_open('lp_setting/update', array('class' => 'form'), array('back' => 'lp_setting/site/'.$site_data['id'], 'site_id' => $site_data['id'])); ?>
            <div id="anti_pattern">
                <label>除外パターン</label>
                <?php foreach ($patterns as $pattern) : ?>
                    <div class="form-group">
                        <input type="text" name="pattern[]" class="form-control" placeholder="除外パターン" value="<?php echo $pattern["value"]; ?>">
                    </div>
                <?php endforeach; ?>
                <div class="form-group">
                    <input type="text" name="pattern[]" class="form-control" placeholder="除外パターン">
                </div>
            </div>
            <div class="form-group">
                <a href="#" onclick="javascript:addpattern();">行を追加</a>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">設定を更新</button>
            </div>
            <?php echo form_close(); ?>
            <script type="text/javascript">
                // 除外パターンの入力欄を追加
                function addpattern() {
                    var d = document.getElementById('anti_pattern');
                    var addhtml = '<div class="form-group">';
                    addhtml += '<input type="text" name="pattern[]" class="form-control" placeholder="除外パターンを入力">';
                    addhtml += '</div>';
                    d.innerHTML += addhtml;
                }
            </script>
            <?php endif; ?>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
