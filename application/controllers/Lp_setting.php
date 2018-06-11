<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property Users_model $users_model
 * @property Sites_model $sites_model
 * @property Menues_model $menues_model
 */
class Lp_setting extends CI_Controller {

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

        // TODO: メニュー
        $menues = $this->menues_model->load($this->session->users['status'], 'lowpages', 'lp_setting');
        $this->data['menues'] = $menues;
    }

    public function index()
	{
	    $this->data['title'] = 'レポート';

	    // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_setting', $this->data);
        $this->load->view('_footer', $this->data);
	}

}
