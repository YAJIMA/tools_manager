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
            <div class="row">
                <div class="col-md-6">
                    <h2>レポート</h2>
                    <?php if (isset($reports)) : ?>
                        <p>インデックスチェックは最大 <?php echo $indexmonth; ?>ヶ月分の履歴を保持しています。</p>
                        <p><a href="<?php echo base_url('lp_reports/file/'.basename($excelfile));?>">レポートファイル（Excel）ダウンロード</a></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if (isset($directories)) : ?>
                        <h3>階層で絞り込み</h3>
                        <p>階層（ディレクトリ）でレポートを絞り込み表示できます。<br>階層を選んでください。</p>

                        <?php echo form_open('',array('class'=>'form-inline','name'=>'directory_form')); ?>
                        <select name="sort" class="form-control" onchange="directorysort();">
                            <option value="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/__'); ?>">絞り込みしない（全て表示）</option>
                            <?php foreach ($directories as $key => $val) : ?>
                            <?php if ( ! empty($val['path'])) : ?>
                                <option value="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$key); ?>" <?php echo ($val['current'] == 1) ? 'selected' : ''; ?>><?php echo $val['path']; ?></option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <?php echo form_close(); ?>

                        <div class="list-group">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if (isset($reports)) : ?>
                        <div class="text-center mt-5">
                            <?php echo $pagination; ?>
                            <p><?php echo $pages; ?>ページ中<?php echo $cur_page; ?>ページを表示</p>
                            <?php echo form_open(uri_string(), array('class'=>'form-inline','name'=>'limit_form')); ?>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <select class="custom-select" id="limit" name="limit" onchange="limitformsubmit();">
                                        <?php if (PAGE_1 > 0) : ?>
                                            <option value="<?php echo PAGE_1; ?>" <?php echo (isset($this->session->limit) && $this->session->limit == PAGE_1) ? 'selected' : ''; ?>><?php echo PAGE_1; ?></option>
                                        <?php endif; ?>
                                        <?php if (PAGE_2 > 0) : ?>
                                            <option value="<?php echo PAGE_2; ?>" <?php echo (isset($this->session->limit) && $this->session->limit == PAGE_2) ? 'selected' : ''; ?>><?php echo PAGE_2; ?></option>
                                        <?php endif; ?>
                                        <?php if (PAGE_3 > 0) : ?>
                                            <option value="<?php echo PAGE_3; ?>" <?php echo (isset($this->session->limit) && $this->session->limit == PAGE_3) ? 'selected' : ''; ?>><?php echo PAGE_3; ?></option>
                                        <?php endif; ?>
                                        <?php if (PAGE_4 > 0) : ?>
                                            <option value="<?php echo PAGE_4; ?>" <?php echo (isset($this->session->limit) && $this->session->limit == PAGE_4) ? 'selected' : ''; ?>><?php echo PAGE_4; ?></option>
                                        <?php endif; ?>
                                        <?php if (PAGE_5 > 0) : ?>
                                            <option value="<?php echo PAGE_5; ?>" <?php echo (isset($this->session->limit) && $this->session->limit == PAGE_5) ? 'selected' : ''; ?>><?php echo PAGE_5; ?></option>
                                        <?php endif; ?>
                                    </select>
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="inputGroupSelect02">件表示</label>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>

                        <?php echo form_open('lp_reports/delete', array('class'=>'form','name'=>'delete_form'), array('back_uri'=>uri_string())); ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <?php if ($this->session->users['status'] == '9' or $this->session->users['status'] == '1') : ?>
                                <th>
                                    <input type="checkbox" name="allcheck" id="allcheck" value="on" onchange="ToggleChecks();">
                                </th>
                                <?php endif; ?>
                                <th>施策</th>
                                <th>
                                    URL
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/url_a'); ?>"
                                             class="<?php if (strpos(uri_string(),'/url_a')) : ?>text-danger<?php endif; ?>">▲</a>
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/url_d'); ?>"
                                             class="<?php if (strpos(uri_string(),'/url_d')) : ?>text-danger<?php endif; ?>">▼</a>
                                </th>
                                <th>
                                    ディレクトリ
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/dir_a'); ?>"
                                             class="<?php if (strpos(uri_string(),'/dir_a')) : ?>text-danger<?php endif; ?>">▲</a>
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/dir_d'); ?>"
                                             class="<?php if (strpos(uri_string(),'/dir_d')) : ?>text-danger<?php endif; ?>">▼</a>
                                </th>
                                <th>
                                    TITLE
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/title_a'); ?>"
                                             class="<?php if (strpos(uri_string(),'/title_a')) : ?>text-danger<?php endif; ?>">▲</a>
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/title_d'); ?>"
                                             class="<?php if (strpos(uri_string(),'/title_d')) : ?>text-danger<?php endif; ?>">▼</a>
                                </th>
                                <th>
                                    更新日
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/update_a'); ?>"
                                             class="<?php if (strpos(uri_string(),'/update_a')) : ?>text-danger<?php endif; ?>">▲</a>
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/update_d'); ?>"
                                             class="<?php if (strpos(uri_string(),'/update_d')) : ?>text-danger<?php endif; ?>">▼</a>
                                </th>
                                <th>
                                    Googleキャッシュ日
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/gcache_a'); ?>"
                                             class="<?php if (strpos(uri_string(),'/gcache_a')) : ?>text-danger<?php endif; ?>">▲</a>
                                    &nbsp;<a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$directory.'/gcache_d'); ?>"
                                             class="<?php if (strpos(uri_string(),'/gcache_d')) : ?>text-danger<?php endif; ?>">▼</a>
                                </th>
                                <?php foreach ($reports['cols'] as $col) : ?>
                                    <th><?php echo $col; ?></th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $operations = array('high'=>'低品質(優先度:高)','mid'=>'低品質(優先度:中)','low'=>'低品質(優先度:低)','none'=>'問題なし'); ?>
                            <?php foreach ($reports as $key => $report) : ?>
                                <?php if (is_numeric($key)) : ?>
                                    <tr>
                                        <?php if ($this->session->users['status'] == '9' or $this->session->users['status'] == '1') : ?>
                                            <td>
                                                <input type="checkbox" name="page_ids[]" id="page_ids_<?php echo $key; ?>" value="<?php echo $report['id']; ?>" onclick="DisChecked();">
                                            </td>
                                        <?php endif; ?>
                                        <td><span class="<?php echo $report['operation']; ?>"><?php echo $operations[$report['operation']]; ?></span></td>
                                        <td><?php echo $report['path']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$report['breadcrumb']); ?>">
                                                <?php echo $directories[$report['breadcrumb']]['path']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $report['title']; ?></td>
                                        <td><?php echo $report['update_datetime']; ?></td>
                                        <td><?php echo $report['cache_datetime']; ?></td>
                                        <?php foreach ($reports['cols'] as $col) : ?>
                                            <?php if ( ! isset($report['indexchecks'][$col])) : ?>
                                                <td class="text-right"><?php echo '未取得'; ?></td>
                                            <?php else : ?>
                                                <td class="text-right"><?php echo $report['indexchecks'][$col]; ?></td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php if ($this->session->users['status'] == '9' or $this->session->users['status'] == '1') : ?>
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('チェックされたページを削除します。\nこの作業は取り消しできません。\nよろしいですか？');return false;">チェックしたページを削除</button>
                        </div>
                    <?php endif; ?>
                        <?php echo form_close(); ?>
                        <div class="text-center">
                            <?php echo $pagination; ?>
                            <p><?php echo $pages; ?>ページ中<?php echo $cur_page; ?>ページを表示</p>
                        </div>
                    <?php else : ?>
                        <p>サイトを選んで下さい。</p>
                        <?php if (isset($site_menues['site_item'])) : ?>
                            <?php foreach ($site_menues['site_item'] as $site) : ?>
                                <a href="<?php echo $site['href']; ?>"><?php echo $site['text']; ?></a><br>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
<script type="text/javascript">

    function limitformsubmit() {
        document.limit_form.submit();
    }

    function directorysort() {
        var browser = document.directory_form.sort.value;
        location.href = browser
    }

    // 全部チェックする・しない
    function ToggleChecks() {

        // チェックボックス要素をすべて取得する
        var boxes = document.getElementsByName("page_ids[]");

        // チェックボックスの個数を取得する
        var cnt = boxes.length;
        //console.log('cnt '+cnt+'.');

        if (document.getElementById("allcheck").checked === true)
        {
            //console.log('ONにする');
            for(var i=0; i<cnt; i++) {
                boxes.item(i).checked = true;
            }
        }
        else
        {
            //console.log('OFFにする');
            for(var i=0; i<cnt; i++) {
                boxes.item(i).checked = false;
            }
        }
    }

    // 一つでもチェックを外すと「全て選択」のチェック外れる
    function　DisChecked(){

        // チェックボックス要素をすべて取得する
        var boxes = document.getElementsByName("page_ids[]");

        // チェックボックスの個数を取得する
        var cnt = boxes.length;

        var checksCount = 0;

        for (var i=0; i<cnt; i++){
            if(boxes.item(i).checked === false){
                document.getElementById("allcheck").checked = false;
            }else{
                checksCount += 1;
                if(checksCount == cnt){
                    document.getElementById("allcheck").checked = true;
                }
            }
        }
    }
</script>