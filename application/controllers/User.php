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

class User extends CI_Controller
{
    public $data = array();

    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = 'ユーザ管理';

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
        $menues = $this->menues_model->load($this->session->users['status'], 'home', 'user');
        $this->data['menues'] = $menues;

        // ユーザ一覧
        $wheredata = array();
        if ($this->session->users['group_id'] > 0)
        {
            $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $this->session->users['group_id']);
        }
        $this->data['users'] = $this->users_model->load($wheredata);

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
        $this->data['title'] = 'ユーザ管理';

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('user', $this->data);
        $this->load->view('_footer', $this->data);
    }

    // ユーザ登録
    public function insert()
    {
        // バリデーションライブラリをロード
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'username',
            'ユーザ名',
            'required|is_unique[users.username]',
            array(
                'required' => '%s を入力していません。',
                'is_unique' => '%s はすでに存在します。'
            )
        );
        $this->form_validation->set_rules(
            'email',
            'メールアドレス',
            'required|valid_email|is_unique[users.email]',
            array(
                'required' => '%s を入力していません。',
                'is_unique' => '%s はすでに存在します。'
            )
        );


        if ($this->form_validation->run() == FALSE)
        {
            // ページを表示
            $this->load->view('_header', $this->data);
            $this->load->view('user', $this->data);
            $this->load->view('_footer', $this->data);
        }
        else
        {
            $this->users_model->insert();
            redirect('user');
        }
    }

    // ユーザ更新
    public function edit($user_id = 0)
    {
        $this->data['title'] = 'ユーザ管理';


        // 対象のユーザー
        $wheredata = array();
        $wheredata[] = array('kind' => 'where', 'field_name' => 'id', 'value' => $user_id) ;
        $userdata = $this->users_model->load($wheredata);
        $this->data['userdata'] = $userdata[0];


        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('user', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function update($user_id = 0)
    {
        // バリデーションライブラリをロード
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'username',
            'ユーザ名',
            'required',
            array(
                'required' => '%s を入力していません。'
            )
        );
        $this->form_validation->set_rules(
            'email',
            'メールアドレス',
            'required|valid_email'
        );


        if ($this->form_validation->run() == FALSE)
        {
            // 対象のユーザー
            $wheredata = array();
            $wheredata[] = array('kind' => 'where', 'field_name' => 'id', 'value' => $user_id) ;
            $userdata = $this->users_model->load($wheredata);
            $this->data['userdata'] = $userdata[0];

            // ページを表示
            $this->load->view('_header', $this->data);
            $this->load->view('user', $this->data);
            $this->load->view('_footer', $this->data);
        }
        else
        {
            $this->users_model->update();
            redirect('user/edit/'.$user_id);
        }
    }
}