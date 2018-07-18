<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property Users_model $users_model
 * @property Sites_model $sites_model
 * @property Menues_model $menues_model
 * @property Lowpages_model $lowpages_model
 */
class Lp_setting extends CI_Controller {

    public $data = array();

    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = "設定";

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
        if ($this->session->users['group_id'] > 0)
        {
            $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $this->session->users['group_id']);
        }
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.group_id', 'value' => 'ASC');
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.name', 'value' => 'ASC');
        $sites = $this->sites_model->load($wheredata);
        $this->data['sites'] = $sites;
        $this->data['site_menues'] = $this->menues_model->sitemenues($sites, $this->session->site_id);

        // TODO: メニュー
        $menues = $this->menues_model->load($this->session->users['status'], 'lowpages', 'lp_setting');
        $this->data['menues'] = $menues;

    }

    public function index()
	{
	    $this->data['title'] = '設定';

	    // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_setting', $this->data);
        $this->load->view('_footer', $this->data);
	}

	public function site($site_id = NULL)
    {

        $this->data['site_id'] = $site_id;

        $wheredata[] = array('kind' => 'where', 'field_name' => 'sites.id', 'value' => $site_id);
        $site_data = $this->sites_model->load($wheredata);
        $this->data['site_data'] = $site_data[0];

        // 除外パターン
        $settingdata = array();
        $settingdata[] = array('kind' => 'where', 'field_name' => 'site_id', 'value' => $site_id);
        $settingdata[] = array('kind' => 'where', 'field_name' => 'name', 'value' => 'pattern');
        $this->data['patterns'] = $this->lowpages_model->setting_load($settingdata);

        // インデックスチェック履歴数
        $settingdata = array();
        $settingdata[] = array('kind' => 'where', 'field_name' => 'site_id', 'value' => $site_id);
        $settingdata[] = array('kind' => 'where', 'field_name' => 'name', 'value' => 'indexmonth');
        $this->data['indexmonth'] = $this->lowpages_model->setting_load($settingdata);

        // 優先度
        $google_cache_days_check = $index_check_check = array("off","off","off");
        $google_cache_days = array();
        $index_check = array();

        $priority_data = $this->lowpages_model->priority_load($site_id);

        $google_cache_days = $priority_data['google_cache_days'];
        $index_check = $priority_data['index_check'];
        $google_cache_days_check = $priority_data['google_cache_days_check'];
        $index_check_check = $priority_data['index_check_check'];

        $this->data['google_cache_days_check'] = $google_cache_days_check;
        $this->data['index_check_check'] = $index_check_check;
        $this->data['google_cache_days'] = $google_cache_days;
        $this->data['index_check'] = $index_check;

        $this->data['title'] = $site_data[0]['name'].'の設定';

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_setting', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function update()
    {
        $site_id = $this->input->post("site_id");
        $this->lowpages_model->setting_update();

        redirect('lp_setting/site/'.$site_id);
    }
}
