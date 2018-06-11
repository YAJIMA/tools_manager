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
            <h2>サイト一覧</h2>
            <div class="list-group">
                <a class="list-group-item list-group-item-action list-group-item-success d-flex justify-content-between align-items-center <?php if (uri_string() == 'site') : ?>active<?php endif; ?>" href="<?php echo base_url('site'); ?>">サイト追加
                    <span class="badge badge-primary badge-pill">+</span>
                </a>
                <?php foreach ($sites as $site) : ?>
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php if (uri_string() == 'site/edit/'.$site['id']) : ?>active<?php endif; ?>" href="<?php echo base_url('site/edit/'.$site['id']); ?>"><?php echo $site['name']; ?>
                    <span class="badge badge-primary badge-pill"><?php echo $site['group_name']; ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-5">
            <?php if (isset($sitedata)) : ?>
                <h2>変更／削除</h2>
                <p>サイトを更新できます。</p>
                <?php echo form_open('site/update/'.$sitedata['id'], 'class="form" role="form"'); ?>
                <div class="form-group">
                    <label for="name">サイト名</label>
                    <input type="text" name="name" id="name" value="<?php echo $sitedata['name']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" name="url" id="url" value="<?php echo $sitedata['url']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="group_id">グループ</label>
                    <select name="group_id" id="group_id" class="form-control">
                        <option value="0">グループなし</option>
                        <?php foreach ($groups as $group) : ?>
                            <option value="<?php echo $group['id']; ?>" <?php if ($sitedata['group_id'] == $group['id']) : ?>selected<?php endif; ?>>
                                <?php echo $group['name']; ?>
                                <?php if ($sitedata['group_id'] == $group['id']) : ?>*<?php endif; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="deletecheck" name="deletecheck" value="<?php echo $sitedata['id']; ?>">
                    <label class="form-check-label" for="deletecheck">サイトを削除</label>
                    <small id="emailHelp" class="form-text text-muted">チェックを入れるとサイトを削除します。</small>
                </div>
                <div class="form-group">
                    <button type="submit" name="site_id" value="<?php echo $sitedata['id']; ?>" class="btn btn-warning">更新</button>
                </div>
                <?php echo form_close(''); ?>
            <?php else : ?>
                <h2>サイト追加</h2>
                <?php echo form_open('site/insert', 'class="form" role="form"'); ?>
                <div class="form-group">
                    <label for="name">サイト名</label>
                    <input type="text" name="name" id="name" value="" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="url" name="url" id="url" value="" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="group_id">グループ</label>
                    <select name="group_id" id="group_id" class="form-control">
                        <option value="0">グループなし</option>
                        <?php foreach ($groups as $group) : ?>
                            <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">追加</button>
                </div>
                <?php echo form_close(); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
