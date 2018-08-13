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
        <div class="col-md-6">
            <h2>ダッシュボード</h2>
            <?php if (isset($this->session->site_id) && $this->session->site_id > 0) : ?>
            <h3>現在選択中のサイト</h3>
                <p>選択サイトの変更は、上のメニューバーで変更して下さい。</p>
                <p class="text-success"><?php echo $siteinfo['name']; ?> (<?php echo $siteinfo['url']; ?>)</p>
            <?php else : ?>
            <h3>サイトを選択して下さい</h3>
                <ul>
                <?php foreach ($site_menues['site_item'] as $site) : ?>
                    <li><a href="<?php echo $site['href']; ?>"><?php echo $site['text']; ?> (<?php echo $site['site_url']; ?>)</a></li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (isset($this->session->site_id) && $this->session->site_id > 0) : ?>
            <h3>ツール</h3>
            <p>利用したいツールを以下のリンクから選んで下さい。</p>
            <ul>
                <li><a href="<?php echo base_url('lowpages'); ?>">低品質ページツール</a></li>
            </ul>
            <?php endif; ?>
        </div>
        <div class="col-md-4">
            &nbsp;
        </div>
    </div>
    <div class="row" id="footer">

    </div>
