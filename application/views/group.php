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
        <div class="col-md-3">
            <h2>グループ一覧</h2>
            <div class="list-group">
                <a class="list-group-item list-group-item-action list-group-item-success d-flex justify-content-between align-items-center <?php if (uri_string() == 'group') : ?>active<?php endif; ?>" href="<?php echo base_url('group'); ?>">グループ追加
                    <span class="badge badge-primary badge-pill">+</span>
                </a>
                <?php foreach ($groups as $group) : ?>
                <a class="list-group-item list-group-item-action <?php if (uri_string() == 'group/edit/'.$group['id']) : ?>active<?php endif; ?>" href="<?php echo base_url('group/edit/'.$group['id']); ?>"><?php echo $group['name']; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php if (isset($groupdata)) : ?>
            <h2>名称変更／削除</h2>
            <p>グループ名称を更新できます。</p>
            <?php echo form_open('group/update/'.$groupdata[0]['id'], 'class="form" role="form"'); ?>
            <div class="form-group">
                <label for="name">グループ名称</label>
                <input type="text" name="name" id="name" value="<?php echo $groupdata[0]['name']; ?>" class="form-control" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="deletecheck" name="deletecheck" value="<?php echo $groupdata[0]['id']; ?>">
                <label class="form-check-label" for="deletecheck">グループを削除</label>
                <small id="emailHelp" class="form-text text-muted">チェックを入れるとグループを削除します。所属しているサイトは「グループなし」になります。</small>
            </div>
            <div class="form-group">
                <button type="submit" name="group_id" value="<?php echo $groupdata[0]['id']; ?>" class="btn btn-warning">更新</button>
            </div>
            <?php echo form_close(''); ?>
            <?php else : ?>
            <h2>グループ追加</h2>
            <?php echo form_open('group/insert', 'class="form" role="form"'); ?>
            <div class="form-group">
                <label for="name">グループ名称</label>
                <input type="text" name="name" id="name" value="" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">追加</button>
            </div>
            <?php echo form_close(); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-3">
            <?php if (isset($groupsites) && count($groupsites) > 0) : ?>
                <h2>所属サイト</h2>
                <div class="list-group">
                    <?php foreach ($groupsites as $groupsite) : ?>
                        <a href="<?php echo base_url('site/edit/'.$groupsite['id']); ?>" class="list-group-item"><?php echo $groupsite['name']; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                &nbsp;
            <?php endif; ?>
            <?php if (isset($groupusers) && count($groupusers) > 0) : ?>
                <h2>所属ユーザ</h2>
                <div class="list-group">
                    <?php foreach ($groupusers as $groupuser) : ?>
                        <a href="<?php echo base_url('user/edit/'.$groupuser['id']); ?>" class="list-group-item"><?php echo $groupuser['username']; ?></a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                &nbsp;
            <?php endif; ?>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
