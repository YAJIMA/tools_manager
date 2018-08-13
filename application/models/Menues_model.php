<?php
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-6月-1
 * Time: 20:41
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Menues_model extends CI_Model
{
    public $menues = array();

    // TODO: メニューをロード
    public function load($user_status = 0, $kind = 'default', $current_page = '')
    {

        // 中メニュー
        switch (strtolower($kind))
        {
            // ホームのメニュー
            case "home":

                switch ($user_status)
                {
                    case '9':
                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('home');
                        $link_items['text'] = '管理ホーム';
                        $link_items['active'] = ($current_page == 'home') ? 'active' : '';
                        $this->menues['link_item'][] = $link_items;

                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('group');
                        $link_items['text'] = 'グループ管理';
                        $link_items['active'] = ($current_page == 'group') ? 'active' : '';
                        $this->menues['link_item'][] = $link_items;

                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('user');
                        $link_items['text'] = 'ユーザ管理';
                        $link_items['active'] = ($current_page == 'user') ? 'active' : '';
                        $this->menues['link_item'][] = $link_items;

                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('site');
                        $link_items['text'] = 'サイト管理';
                        $link_items['active'] = ($current_page == 'site') ? 'active' : '';
                        $this->menues['link_item'][] = $link_items;
                        break;
                    case '7':
                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('home');
                        $link_items['text'] = '管理ホーム';
                        $link_items['active'] = ($current_page == 'home') ? 'active' : '';
                        $this->menues['link_item'][] = $link_items;

                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('group');
                        $link_items['text'] = 'グループ管理';
                        $link_items['active'] = ($current_page == 'group') ? 'active' : '';
                        $this->menues['link_item'][] = $link_items;

                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('site');
                        $link_items['text'] = 'サイト管理';
                        $link_items['active'] = ($current_page == 'site') ? 'active' : '';
                        $this->menues['link_item'][] = $link_items;
                        break;
                    case '1':
                        break;
                }

                // ツールへのリンク
                // 低品質ページ
                $link_items = array('href' => '', 'text'=>'', 'active' => '');
                // メニューの設定
                $link_items['href'] = base_url('lowpages');
                $link_items['text'] = '&gt;&gt; 低品質ページ';
                if ($current_page == 'lowpages')
                {
                    $link_items['active'] = 'active';
                }
                $this->menues['link_item'][] = $link_items;

                break;

            // 低評価ページのメニュー
            case "lowpages":
                // 大メニュー
                // メニューのテンプレート
                $tool_items = array('href' => '', 'text'=>'', 'active' => '');
                // メニューの設定
                $tool_items['href'] = base_url('lowpages');
                $tool_items['text'] = '低品質ページ';
                if ($current_page == 'lowpages')
                {
                    $tool_items['active'] = 'active';
                }
                $this->menues['tool_item'][] = $tool_items;

                // サイトメニュー

                // メニューのテンプレート
                $link_items = array('href' => '', 'text'=>'', 'active' => '');
                // メニューの設定
                $link_items['href'] = base_url('lowpages');
                $link_items['text'] = '低品質ページ';
                if ($current_page == 'lowpages')
                {
                    $link_items['active'] = 'active';
                }
                $this->menues['link_item'][] = $link_items;

                if ($this->session->site_id > 0)
                {
                    // メニューのテンプレート
                    $link_items = array('href' => '', 'text'=>'', 'active' => '');
                    // メニューの設定
                    $link_items['href'] = base_url('lp_reports');
                    $link_items['text'] = 'レポート';
                    if ($current_page == 'lp_reports')
                    {
                        $link_items['active'] = 'active';
                    }
                    $this->menues['link_item'][] = $link_items;

                    if ($user_status == 9)
                    {
                        // CSVファイルアップロード
                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('lp_csv');
                        $link_items['text'] = 'CSVファイル';
                        if ($current_page == 'lp_csv')
                        {
                            $link_items['active'] = 'active';
                        }
                        $this->menues['link_item'][] = $link_items;

                        /*
                        // インデックスチェック
                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '', 'target' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('lp_indexcheck');
                        $link_items['text'] = 'インデックスチェック';
                        if ($current_page == 'lp_indexcheck')
                        {
                            $link_items['active'] = 'active';
                        }
                        $this->menues['link_item'][] = $link_items;
                        */

                        // 設定
                        // メニューのテンプレート
                        $link_items = array('href' => '', 'text'=>'', 'active' => '');
                        // メニューの設定
                        $link_items['href'] = base_url('lp_setting');
                        $link_items['text'] = '設定';
                        if ($current_page == 'lp_setting')
                        {
                            $link_items['active'] = 'active';
                        }
                        $this->menues['link_item'][] = $link_items;
                    }
                }
                break;
        }

        return $this->menues;
    }

    // TODO: サイトメニュー
    public function sitemenues($sites = NULL, $current_id = 0, $uri_format = 'lowpages/site/%s')
    {
        $this->menues['site_item'] = array();

        foreach ($sites as $site)
        {
            // メニューのテンプレート
            $site_items = array('href' => '', 'text'=>'', 'active' => '');
            // メニューの設定
            $uri = sprintf($uri_format, $site['id']);
            $site_items['href'] = base_url($uri);
            $site_items['text'] = $site['name'];
            $site_items['site_url'] = $site['url'];
            if ($current_id == $site['id'])
            {
                $site_items['active'] = 'active';
            }
            $this->menues['site_item'][] = $site_items;
        }

        return $this->menues;
    }
}