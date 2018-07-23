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
            <!-- 送信完了メッセージ -->
            <?php if (isset($resultdata)) : ?>
                <div class="alert alert-success" role="alert">データを登録しました。</div>
            <?php endif; ?>
            <!-- //送信完了メッセージ -->

            <!-- 送信フォーム -->
            <?php if (isset($disp) && $disp == "form") : ?>
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
            <!-- //送信フォーム -->

            <!-- 登録カラム設定フォーム -->
            <?php if (isset($headline)) : ?>
            <h2>パンくずリスト列の選択</h2>
                <p>パンくずリストとして登録するカラムを設定してください。</p>
                <?php echo form_open('lp_csv/preview', array("role" => "form", "class" => "form")); ?>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-condensed">
                        <tbody>
                        <?php foreach ($headline as $key => $hl) : ?>
                        <tr>
                            <th style="width:20%;">
                                <?php echo $hl; ?>
                            </th>
                            <td style="width:20%;">
                                <select name="col_<?php echo $key; ?>" class="form-control">
                                    <option value="-none-">未設定</option>
                                    <option value="breadcrumb1--<?php echo $hl; ?>">パンくずリスト 1</option>
                                    <option value="breadcrumb2--<?php echo $hl; ?>">パンくずリスト 2</option>
                                    <option value="breadcrumb3--<?php echo $hl; ?>">パンくずリスト 3</option>
                                    <option value="breadcrumb4--<?php echo $hl; ?>">パンくずリスト 4</option>
                                    <option value="breadcrumb5--<?php echo $hl; ?>">パンくずリスト 5</option>
                                </select>
                            </td>
                            <?php for ($i = 0; $i < 3; $i++) : ?>
                            <td class="text-muted" style="overflow: hidden; width: 20%;">
                                <?php echo $previewdata[$i][$hl]; ?>
                            </td>
                            <?php endfor; ?>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">登録</button>
                </div>
                <?php echo form_close(); ?>
            <?php endif; ?>
            <!-- //登録カラム設定フォーム -->

            <!-- プレビュー -->
            <?php if (isset($rowdata)) : ?>
                <h2>登録行の確認</h2>
                <p>登録しないデータ行は、チェックを外して下さい。</p>
                <?php if (count($patterns) > 0) : ?>
                <p>以下のパターンに一致した行は省かれています。</p>
                <p class="alert alert-primary">
                    <?php foreach ($patterns as $pattern) : ?>
                        <?php echo $pattern['value']; ?> |
                    <?php endforeach; ?>
                </p>
                <?php endif; ?>
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
                            <th>BreadCrumb1</th>
                            <th>BreadCrumb2</th>
                            <th>BreadCrumb3</th>
                            <th>BreadCrumb4</th>
                            <th>BreadCrumb5</th>
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
                                <td><?php echo (isset($this->session->columns['breadcrumb1'])) ? $row[$this->session->columns['breadcrumb1']] : '&nbsp;'; ?></td>
                                <td><?php echo (isset($this->session->columns['breadcrumb2'])) ? $row[$this->session->columns['breadcrumb2']] : '&nbsp;'; ?></td>
                                <td><?php echo (isset($this->session->columns['breadcrumb3'])) ? $row[$this->session->columns['breadcrumb3']] : '&nbsp;'; ?></td>
                                <td><?php echo (isset($this->session->columns['breadcrumb4'])) ? $row[$this->session->columns['breadcrumb4']] : '&nbsp;'; ?></td>
                                <td><?php echo (isset($this->session->columns['breadcrumb5'])) ? $row[$this->session->columns['breadcrumb5']] : '&nbsp;'; ?></td>
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
            <!-- //プレビュー -->

            <!-- プレビュー Googleキャッシュ -->
            <?php if (isset($rowdatacache)) : ?>
                <h2>登録行の確認</h2>
                <p>登録しないデータ行は、チェックを外して下さい。</p>
                <?php if (count($patterns) > 0) : ?>
                <p>以下のパターンに一致した行は省かれています。</p>
                <p class="alert alert-primary">
                    <?php foreach ($patterns as $pattern) : ?>
                        <?php echo $pattern['value']; ?> |
                    <?php endforeach; ?>
                </p>
                <?php endif; ?>
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
            <!-- //プレビュー Googleキャッシュ -->
        </div>
    </div>
    <div class="row" id="footer">

    </div>
