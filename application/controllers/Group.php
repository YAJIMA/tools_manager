<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-5月-28
 * Time: 21:25
 *
 * @property Users_model $users_model
 * @property Sites_model $sites_model
 * @property Groups_model $groups_model
 */

class Group extends CI_Controller
{
    public $data = array();

    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = 'グループ管理';

        // モデルをロード
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $this->load->model('groups_model');

        // ログインしてなかったら、ログイン画面に戻る
        if ( ! $this->session->userdata("is_logged_in"))
        {
            redirect('login');
        }

        // メニュー
        $menues = array();

        switch ($this->session->users['status'])
        {
            case '9': // 管理者
                $menues['site_head'] = '管理';
                // ツールリンク
                $menues['tool_item'][] = array('href' => base_url('home'), 'text'=>'管理', 'active' => 'active');
                $menues['tool_item'][] = array('href' => base_url('lowpages'), 'text'=>'低評価ページ', 'active' => '');
                // サブメニュー
                $menues['link_item'][] = array('href' => base_url('home'), 'text'=>'管理ホーム', 'active' => '');
                $menues['link_item'][] = array('href' => base_url('group'), 'text'=>'グループ管理', 'active' => 'active');
                $menues['link_item'][] = array('href' => base_url('user'), 'text'=>'ユーザ管理', 'active' => '');
                $menues['link_item'][] = array('href' => base_url('site'), 'text'=>'サイト管理', 'active' => '');
                break;
            case '7': // スタッフ
                $menues['site_head'] = '管理';
                // ツールリンク
                $menues['tool_item'][] = array('href' => base_url('home'), 'text'=>'管理', 'active' => 'active');
                $menues['tool_item'][] = array('href' => base_url('lowpages'), 'text'=>'低評価ページ', 'active' => '');
                // サブメニュー
                $menues['link_item'][] = array('href' => base_url('home'), 'text'=>'管理ホーム', 'active' => '');
                $menues['link_item'][] = array('href' => base_url('group'), 'text'=>'グループ管理', 'active' => 'active');
                $menues['link_item'][] = array('href' => base_url('site'), 'text'=>'サイト管理', 'active' => '');
                break;
            case '1': // 一般ユーザ
                $menues['site_head'] = '管理';
                // ツールリンク
                $menues['tool_item'][] = array('href' => base_url('lowpages'), 'text'=>'低評価ページ', 'active' => '');
                break;
            default:
                break;
        }

        $this->data['menues'] = $menues;

        // グループ一覧
        $wheredata = array();
        if ($this->session->users['group_id'] > 0)
        {
            $wheredata[] = array('kind' => 'where', 'field_name' => 'id', 'value' => $this->session->users['group_id']);
        }
        $this->data['groups'] = $this->groups_model->load($wheredata);

        // 配下のサイトを取得
        $this->data['sites'] = array();
        $querydata = array();
        $querydata[] = array('kind'=>'order_by', 'field_name'=>'sites.id', 'value'=>'desc');
        if ($this->session->users['group_id'] == '0')
        {
            $querydata[] = array('kind'=>'where', 'field_name'=>'sites.id >', 'value'=>'0');
        }
        else
        {
            $querydata[] = array('kind'=>'where', 'field_name'=>'sites.group_id', 'value'=>$this->session->users['group_id']);
        }
        $this->data['sites'] = $this->sites_model->load($querydata);
    }

    public function index()
    {
        $this->data['title'] = 'グループ管理';

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('group', $this->data);
        $this->load->view('_footer', $this->data);
    }

    // グループ登録
    public function insert()
    {
        // バリデーションライブラリをロード
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'name',
            'グループ名称',
            'required|is_unique[groups.name]',
            array(
                'required' => '%s を入力していません。',
                'is_unique' => '%s はすでに存在します。'
            )
        );

        if ($this->form_validation->run() == FALSE)
        {
            // ページを表示
            $this->load->view('_header', $this->data);
            $this->load->view('group', $this->data);
            $this->load->view('_footer', $this->data);
        }
        else
        {
            $this->groups_model->insert();
            redirect('group');
        }
    }

    // グループ更新
    public function edit($group_id = 0)
    {
        $this->data['title'] = 'グループ管理';

        // 現在のグループ
        $wheredata = array();
        $wheredata[] = array('kind' => 'where', 'field_name' => 'id', 'value' => $group_id) ;
        $this->data['groupdata'] = $this->groups_model->load($wheredata);

        // 配下のサイト
        $wheredata = array();
        $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $group_id) ;
        $this->data['groupsites'] = $this->sites_model->load($wheredata);

        // 所属ユーザー
        $wheredata = array();
        $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $group_id) ;
        $this->data['groupusers'] = $this->users_model->load($wheredata);

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('group', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function update($group_id = 0)
    {
        // バリデーションライブラリをロード
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'name',
            'グループ名称',
            'required',
            array(
                'required' => '%s を入力していません。'
            )
        );

        if ($this->form_validation->run() == FALSE)
        {
            // 現在のグループ
            $wheredata = array();
            $wheredata[] = array('kind' => 'where', 'field_name' => 'id', 'value' => $group_id) ;
            $this->data['groupdata'] = $this->groups_model->load($wheredata);

            // 配下のサイト
            $wheredata = array();
            $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $group_id) ;
            $this->data['groupsites'] = $this->sites_model->load($wheredata);

            // 所属ユーザー
            $wheredata = array();
            $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $group_id) ;
            $this->data['groupusers'] = $this->users_model->load($wheredata);

            // ページを表示
            $this->load->view('_header', $this->data);
            $this->load->view('group', $this->data);
            $this->load->view('_footer', $this->data);
        }
        else
        {
            $this->groups_model->update();
            redirect('group/edit/'.$group_id);
        }
    }
}