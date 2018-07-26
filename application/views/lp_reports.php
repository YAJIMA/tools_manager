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
            <h2>レポート</h2>
            <?php if (isset($reports)) : ?>
            <!-- <?php echo $excelfile; ?> -->
                <p>インデックスチェックは最大 <?php echo $indexmonth; ?>ヶ月分の履歴を保持しています。</p>
                <p><a href="<?php echo base_url('lp_reports/file/'.basename($excelfile));?>">レポートファイル（Excel）ダウンロード</a></p>
            <?php if (isset($directories)) : ?>
            <h3>階層で絞り込み</h3>
                <p>階層（ディレクトリ）でレポートを絞り込み表示できます。<br>階層を選んでください。</p>
            <div class="list-group">
                <a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/__'); ?>" class="list-group-item">絞り込みしない（全て表示）</a>
                <?php foreach ($directories as $key => $val) : ?>
                    <a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$key); ?>" class="list-group-item"><?php echo $val['path']; ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="text-center">
                <?php echo $pagination; ?>
                <p><?php echo $pages; ?>ページ中<?php echo $cur_page; ?>ページを表示</p>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>施策</th>
                    <th>URL</th>
                    <th>ディレクトリ</th>
                    <th>TITLE</th>
                    <th>更新日</th>
                    <th>Googleキャッシュ日</th>
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
                    <td><?php echo $operations[$report['operation']]; ?></td>
                    <td><?php echo $report['path']; ?></td>
                    <td>
                        <a href="<?php echo base_url('lp_reports/site/'.$this->session->site_id.'/'.$report['breadcrumb']); ?>">
                            <?php echo $directories[$report['breadcrumb']]['path']; ?>
                        </a>
                    </td>
                    <td><?php echo $report['title']; ?></td>
                    <td><?php echo $report['upload_datetime']; ?></td>
                    <td><?php echo $report['cache_datetime']; ?></td>
                    <?php foreach ($reports['cols'] as $col) : ?>
                        <?php if ( ! isset($report['indexchecks'][$col])) : ?>
                            <td class="text-right">&nbsp;</td>
                        <?php else : ?>
                            <td class="text-right"><?php echo $report['indexchecks'][$col]; ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
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
    <div class="row" id="footer">

    </div>
