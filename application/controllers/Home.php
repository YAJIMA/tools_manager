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

            // ユーザーデータ
            $this->session->set_userdata(array("users" => $userdata));

            // サイト一覧
            $wheredata = array();
            if ($this->session->users['group_id'] > 0) {
                $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $this->session->users['group_id']);
            }
            $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.group_id', 'value' => 'ASC');
            $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.name', 'value' => 'ASC');
            $sites = $this->sites_model->load($wheredata);
            $this->data['sites'] = $sites;

            $this->data['site_menues'] = $this->menues_model->sitemenues($sites, $this->session->site_id, 'home/site/%s');
        }

        if (isset($this->session->site_id) && $this->session->site_id > 0)
        {
            // サイト情報
            $data = array();
            $data[] = array('kind' => 'where', 'field_name' => 'sites.id', 'value' => $this->session->site_id);
            $site_info = $this->sites_model->load($data);
            $this->data['siteinfo'] = $site_info[0];
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

    public  function site($site_id = NULL)
    {
        $this->data['title'] = '低評価ページ';

        // セッションに現在見ているサイトIDを登録
        $this->session->site_id = $site_id;

        redirect('home');
    }
}
