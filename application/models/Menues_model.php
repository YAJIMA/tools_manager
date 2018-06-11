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
        // 大メニュー
        // 管理ページ
        if ($user_status > 5)
        {
            // メニューのテンプレート
            $tool_items = array('href' => '', 'text'=>'', 'active' => '');
            // メニューの設定
            $tool_items['href'] = base_url('home');
            $tool_items['text'] = '管理';
            if ($current_page == 'home')
            {
                $tool_items['active'] = 'active';
            }

            $this->menues['tool_item'][] = $tool_items;
        }

        // 低評価ページ
        // メニューのテンプレート
        $tool_items = array('href' => '', 'text'=>'', 'active' => '');
        // メニューの設定
        $tool_items['href'] = base_url('lowpages');
        $tool_items['text'] = '低評価ページ';
        if ($current_page == 'lowpages')
        {
            $tool_items['active'] = 'active';
        }
        $this->menues['tool_item'][] = $tool_items;

        // 中メニュー
        switch (strtolower($kind))
        {
            // 低評価ページのメニュー
            case "lowpages":
                // メニューのテンプレート
                $link_items = array('href' => '', 'text'=>'', 'active' => '');
                // メニューの設定
                $link_items['href'] = base_url('lowpages');
                $link_items['text'] = '低評価ページ';
                if ($current_page == 'lowpages')
                {
                    $link_items['active'] = 'active';
                }
                $this->menues['link_item'][] = $link_items;

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

                if ($user_status > 5)
                {
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

                break;
        }

        return $this->menues;
    }
}