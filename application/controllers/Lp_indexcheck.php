<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property Users_model $users_model
 * @property Sites_model $sites_model
 * @property Menues_model $menues_model
 * @property Lowpages_model $lowpages_model
 * @property Excel_model $excel_model
 */
class Lp_indexcheck extends CI_Controller {

    public $data = array();
    public $sites = array();

    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = "";

        // モデルをロード
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $this->load->model('menues_model');
        $this->load->model('lowpages_model');

        // ログインしてなかったら、ログイン画面に戻る
        if ( ! $this->session->userdata("is_logged_in"))
        {
            redirect('login');
        }

        // サイト一覧
        $wheredata = array();
        if ($this->session->users['group_id'] > 0) {
            $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $this->session->users['group_id']);
        }
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.group_id', 'value' => 'ASC');
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.name', 'value' => 'ASC');
        $this->sites = $this->sites_model->load($wheredata);
        $this->data['sites'] = $this->sites;
        $this->data['site_menues'] = $this->menues_model->sitemenues($this->sites, $this->session->site_id);

        // TODO: メニュー
        $menues = $this->menues_model->load($this->session->users['status'], 'lowpages', 'lp_indexcheck');
        $this->data['menues'] = $menues;
    }

    public function index()
	{
	    $this->data['title'] = 'インデックスチェック';

	    if (isset($this->session->site_id))
        {
            // redirect('lp_indexcheck/site/'.$this->session->site_id);
        }

	    // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_indexcheck', $this->data);
        $this->load->view('_footer', $this->data);
	}

}
