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

class Site extends CI_Controller
{
    public $data = array();

    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = 'サイト管理';

        // モデルをロード
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $this->load->model('groups_model');
        $this->load->model('menues_model');

        // ログインしてなかったら、ログイン画面に戻る
        if ( ! $this->session->userdata("is_logged_in"))
        {
            redirect('login');
        }

        // メニュー
        $menues = $this->menues_model->load($this->session->users['status'], 'home', 'site');
        $this->data['menues'] = $menues;

        // サイト一覧
        $wheredata = array();
        if ($this->session->users['group_id'] > 0)
        {
            $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $this->session->users['group_id']);
        }
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.group_id', 'value' => 'ASC');
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.name', 'value' => 'ASC');
        $sites = $this->sites_model->load($wheredata);
        $this->data['sites'] = $sites;

        // グループ一覧
        $wheredata = array();
        if ($this->session->users['group_id'] > 0)
        {
            $wheredata[] = array('kind' => 'where', 'field_name' => 'id', 'value' => $this->session->users['group_id']);
        }
        $this->data['groups'] = $this->groups_model->load($wheredata);

    }

    public function index()
    {
        $this->data['title'] = 'サイト管理';

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('site', $this->data);
        $this->load->view('_footer', $this->data);
    }

    // サイト登録
    public function insert()
    {
        // バリデーションライブラリをロード
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'name',
            'サイト名',
            'required|is_unique[sites.name]',
            array(
                'required' => '%s を入力していません。',
                'is_unique' => '%s はすでに存在します。'
            )
        );
        $this->form_validation->set_rules(
            'url',
            'URL',
            'required|is_unique[sites.url]',
            array(
                'required' => '%s を入力していません。',
                'is_unique' => '%s はすでに存在します。'
            )
        );

        if ($this->form_validation->run() == FALSE)
        {
            // ページを表示
            $this->load->view('_header', $this->data);
            $this->load->view('site', $this->data);
            $this->load->view('_footer', $this->data);
        }
        else
        {
            $this->sites_model->insert();
            redirect('site');
        }
    }

    // サイト更新
    public function edit($site_id = 0)
    {
        $this->data['title'] = 'サイト管理';

        // 現在のサイト
        $wheredata = array();
        $wheredata[] = array('kind' => 'where', 'field_name' => 'sites.id', 'value' => $site_id) ;
        $sitedata = $this->sites_model->load($wheredata);
        $this->data['sitedata'] = $sitedata[0];

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('site', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function update($site_id = 0)
    {
        // バリデーションライブラリをロード
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'name',
            'サイト名',
            'required',
            array(
                'required' => '%s を入力していません。'
            )
        );
        $this->form_validation->set_rules(
            'url',
            'URL',
            'required',
            array(
                'required' => '%s を入力していません。'
            )
        );

        if ($this->form_validation->run() == FALSE)
        {
            // 現在のサイト
            $wheredata = array();
            $wheredata[] = array('kind' => 'where', 'field_name' => 'id', 'value' => $site_id) ;
            $sitedata = $this->sites_model->load($wheredata);
            $this->data['sitedata'] = $sitedata[0];

            // ページを表示
            $this->load->view('_header', $this->data);
            $this->load->view('site', $this->data);
            $this->load->view('_footer', $this->data);
        }
        else
        {
            $this->sites_model->update();
            if ( ! empty($this->input->post("deletecheck")))
            {
                redirect('site');
            }
            else
            {
                redirect('site/edit/'.$site_id);
            }
        }
    }
}