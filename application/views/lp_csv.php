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
        <div class="col-md-10">
            <!-- TODO: 送信フォーム -->
            <?php if (isset($resultdata)) : ?>
                <div class="alert alert-success" role="alert">データを登録しました。</div>
            <?php endif; ?>

            <?php if ( ! isset($rowdata) || ! isset($rowdatacache)) : ?>
                <h2>Screaming Frog SEO Spider</h2>
            <?php echo validation_errors(); ?>
                <?php echo form_open_multipart("lp_csv/upload", array("role" => "form", "class" => "form"), array("uri_string" => uri_string())); ?>
                <div class="form-group">
                    <label for="site_id">サイト</label>
                    <select name="site_id" id="site_id" class="form-control">
                        <?php foreach ($sites as $site) : ?>
                        <option value="<?php echo $site['id']; ?>"><?php echo $site['group_name']; ?> - <?php echo $site['name']; ?> ( <?php echo $site['url']; ?> )</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filename">ファイル</label>
                    <input type="file" name="filename" id="filename" value="" class="form-control" required onchange="filetypecheck();">
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filetype" id="filetype1" value="excel" checked>
                        <label class="form-check-label" for="filetype1">
                            Excelファイル
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filetype" id="filetype2" value="csv">
                        <label class="form-check-label" for="filetype2">
                            CSVファイル
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">アップロード</button>
                </div>
                <?php echo form_close(); ?>
                <script type="text/javascript">
                    function filetypecheck() {
                        var x = document.getElementById('filename');
                        var ck1 = document.getElementById('filetype1');
                        var ck2 = document.getElementById('filetype2');

                        if ('files' in x)
                        {
                            if (x.files.length > 0)
                            {
                                for (var i = 0; i < x.files.length; i++)
                                {
                                    var file = x.files[i];
                                    if ('name' in file) {
                                        var filename = file.name;
                                        var type = filename.split('.');
                                        switch (type[type.length - 1].toLowerCase())
                                        {
                                            case "csv":
                                                ck1.removeAttribute('checked');
                                                ck2.setAttribute('checked','checked');
                                                break;
                                            case "xlsx":
                                                ck1.setAttribute('checked','checked');
                                                ck2.removeAttribute('checked');
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                </script>
                <h2>Google キャッシュ</h2>
                <?php echo form_open_multipart("lp_csv/uploadcache", array("role" => "form", "class" => "form"), array("uri_string" => uri_string(), "filetype" => "csv")); ?>
                <div class="form-group">
                    <label for="filename2">ファイル</label>
                    <input type="file" name="filename" id="filename2" value="" class="form-control" required onchange="filetypecheck();">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">アップロード</button>
                </div>
                <?php echo form_close(); ?>
            <?php endif; ?>

            <?php if (isset($rowdata)) : ?>
                <p>登録しないデータ行は、チェックを外して下さい。</p>
                <p>以下のパターンに一致した行は省かれています。</p>
                <p class="alert alert-primary">
                    <?php foreach ($patterns as $pattern) : ?>
                        <?php echo $pattern['value']; ?> |
                    <?php endforeach; ?>
                </p>
                <?php echo form_open("lp_csv/submit", array("role" => "form", "class" => "form"), array("uri_string" => uri_string())); ?>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>*</th>
                            <th>Address</th>
                            <th>Content</th>
                            <th>Status Code</th>
                            <th>Status</th>
                            <th>Title 1</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rowdata as $key => $row) : ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="addresses[]" id="reject<?php echo $key; ?>" value="<?php echo $row['Address']; ?>" checked>
                                    </div>
                                </td>
                                <td><?php echo $row['Address']; ?></td>
                                <td><?php echo $row['Content']; ?></td>
                                <td><?php echo $row['Status Code']; ?></td>
                                <td><?php echo $row['Status']; ?></td>
                                <td><?php echo $row['Title 1']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">登録</button>
                    </div>
            </div>
            <?php echo form_close(); ?>
            <?php endif; ?>

            <?php if (isset($rowdatacache)) : ?>
                <p>登録しないデータ行は、チェックを外して下さい。</p>
                <p>以下のパターンに一致した行は省かれています。</p>
                <p class="alert alert-primary">
                    <?php foreach ($patterns as $pattern) : ?>
                        <?php echo $pattern['value']; ?> |
                    <?php endforeach; ?>
                </p>
                <?php echo form_open("lp_csv/submitcache", array("role" => "form", "class" => "form"), array("uri_string" => uri_string())); ?>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>*</th>
                            <th>url</th>
                            <th>Cache Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rowdatacache as $key => $row) : ?>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="addresses[]" id="reject<?php echo $key; ?>" value="<?php echo $row[0]; ?>" checked>
                                    </div>
                                </td>
                                <td><?php echo $row[0]; ?></td>
                                <td><?php echo $row[1]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">登録</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
