<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$userstatus = array(
    0 => "停止",
    1 => "一般",
    7 => "スタッフ",
    9 => "管理者",
);
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
            <h2>ユーザ一覧</h2>
            <p>
                <a class="<?php if (uri_string() == 'user') : ?>active<?php endif; ?>" href="<?php echo base_url('user'); ?>">新しいユーザを追加</a>
            </p>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ユーザ名</th>
                    <th>グループ</th>
                    <th>権限</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user) : ?>
                <tr>
                    <td>
                        <a class="<?php if (uri_string() == 'user/edit/'.$user['id']) : ?>active<?php endif; ?>" href="<?php echo base_url('user/edit/'.$user['id']); ?>"><?php echo $user['username']; ?>
                            <span class="badge badge-primary badge-pill"></span>
                        </a>
                    </td>
                    <td>
                        <?php echo $user['groupname']; ?>
                    </td>
                    <td>
                        <?php echo $userstatus[$user['status']]; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <?php if (isset($userdata)) : ?>
                <h2>ユーザ変更／削除</h2>
                <p>ユーザ情報を更新できます。</p>
                <?php echo validation_errors('<div class="text-danger">','</div>'); ?>
                <?php echo form_open('user/update/'.$userdata['id'], 'class="form" role="form" autocomplete="off"'); ?>
                <div class="form-group">
                    <label for="username">ユーザ名（ログイン名）</label>
                    <input type="text" name="username" id="username" value="<?php echo $userdata['username']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" id="email" value="<?php echo $userdata['email']; ?>" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password" value="" class="form-control" autocomplete="new-password">
                    <small id="emailHelp" class="form-text text-muted">パスワード変更が必要な場合は入力してください。</small>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="radio" name="status" id="status_1" value="1" <?php if ($userdata['status'] == "1") : ?>checked<?php endif; ?>>
                        <label for="status_1">一般ユーザ</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="status" id="status_7" value="7" <?php if ($userdata['status'] == "7") : ?>checked<?php endif; ?>>
                        <label for="status_7">管理スタッフ</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="status" id="status_9" value="9" <?php if ($userdata['status'] == "9") : ?>checked<?php endif; ?>>
                        <label for="status_9">管理者</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="group_id">グループ</label>
                    <select name="group_id" id="group_id" class="form-control">
                        <option value="0">グループなし</option>
                        <?php foreach ($groups as $group) : ?>
                            <option value="<?php echo $group['id']; ?>" <?php if ($group['id'] == $userdata['group_id']) : ?>selected<?php endif; ?>><?php echo $group['name']; ?> <?php if ($group['id'] == $userdata['group_id']) : ?>*<?php endif; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="deletecheck" name="deletecheck" value="<?php echo $userdata['id']; ?>">
                    <label class="form-check-label" for="deletecheck">ユーザを削除</label>
                    <small id="emailHelp" class="form-text text-muted">チェックを入れるとユーザを削除します。</small>
                </div>
                <div class="form-group">
                    <button type="submit" name="user_id" value="<?php echo $userdata['id']; ?>" class="btn btn-warning">更新</button>
                </div>
                <?php echo form_close(''); ?>
            <?php else : ?>
                <h2>ユーザ追加</h2>
                <p>ユーザを追加します。</p>
                <?php echo validation_errors('<div class="text-danger">','</div>'); ?>
                <?php echo form_open('user/insert', 'class="form" role="form" autocomplete="off"'); ?>
                <div class="form-group">
                    <label for="username">ユーザ名（ログイン名）</label>
                    <input type="text" name="username" id="username" value="" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" id="email" value="" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password" value="" class="form-control" autocomplete="new-password" required>
                    <small id="emailHelp" class="form-text text-muted">パスワード変更が必要な場合は入力してください。</small>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="radio" name="status" id="status_1" value="1" checked>
                        <label for="status_1">一般ユーザ</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="status" id="status_7" value="7">
                        <label for="status_7">管理スタッフ</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="status" id="status_9" value="9">
                        <label for="status_9">管理者</label>
                    </div>
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
                <?php echo form_close(''); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
