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
        <div class="col-md-2">
            <!-- TODO: ファイル種別選択 -->
            <nav class="nav nav-pills flex-column">
                <a href="<?php echo base_url('lp_csv/csv'); ?>" class="nav-link <?php if (uri_string() == "lp_csv/csv") : echo "active"; endif; ?>">CSV</a>
                <a href="<?php echo base_url('lp_csv/excel'); ?>" class="nav-link <?php if (uri_string() == "lp_csv/excel") : echo "active"; endif; ?>">Excel</a>
            </nav>
        </div>
        <div class="col-md-4">
            <!-- TODO: 送信フォーム -->
            <?php echo validation_errors(); ?>
            <?php if (uri_string() == "lp_csv/csv" or uri_string() == "lp_csv/excel") : ?>
            <?php echo form_open_multipart("lp_csv/upload", array("role" => "form", "class" => "form"), array("uri_string" => uri_string())); ?>
            <?php if (uri_string() == "lp_csv/csv") : ?>
                <div class="form-group">
                    <label for="filename">CSVファイル</label>
                    <input type="file" name="filename" id="filename" value="" class="form-control" required>
                </div>
            <?php endif; ?>
            <?php if (uri_string() == "lp_csv/excel") : ?>
                <div class="form-group">
                    <label for="filename">Excelファイル</label>
                    <input type="file" name="filename" id="filename" value="" class="form-control" required>
                </div>
            <?php endif; ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">アップロード</button>
                </div>
            <?php echo form_close(); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            <!-- TODO: 送信結果 -->
            <?php if (isset($data)) : echo print_r($data); endif; ?>
            <?php if (isset($rowdata)) : echo print_r($rowdata); endif; ?>
            <?php if (isset($error)) : echo print_r($error); endif; ?>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
