<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property Users_model $users_model
 * @property Sites_model $sites_model
 * @property Menues_model $menues_model
 */
class Home extends CI_Controller {

    public $data = array();

    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = "";

        // モデルをロード
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $this->load->model('menues_model');

        // ログインしてなかったら、ログイン画面に戻る
        if ( ! $this->session->userdata("is_logged_in"))
        {
            redirect('login');
        }
        else
        {
            $userdata = $this->users_model->load_one($this->session->userdata("username"));

            // 配下のサイトを取得
            $this->data['sites'] = array();
            $querydata = array();
            $querydata[] = array('kind'=>'order_by', 'field_name'=>'sites.id', 'value'=>'desc');
            if ($userdata['group_id'] == '0')
            {
                $querydata[] = array('kind'=>'where', 'field_name'=>'sites.id >', 'value'=>'0');
            }
            else
            {
                $querydata[] = array('kind'=>'where', 'field_name'=>'sites.group_id', 'value'=>$userdata['group_id']);
            }
            $this->data['sites'] = $this->sites_model->load($querydata);

            // ユーザーデータ
            $this->session->set_userdata(array("users" => $userdata));

        }

        // メニュー
        $menues = $this->menues_model->load($this->session->users['status'], 'home', 'home');
        $this->data['menues'] = $menues;
    }

    public function index()
	{
	    $this->data['title'] = '管理ホーム';

	    // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('home', $this->data);
        $this->load->view('_footer', $this->data);
	}

}
