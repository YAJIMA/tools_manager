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
class Lp_reports extends CI_Controller {

    public $data = array();
    public $sites = array();
    public $pagemax = PAGEMAX;

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
        $menues = $this->menues_model->load($this->session->users['status'], 'lowpages', 'lp_reports');
        $this->data['menues'] = $menues;

        // ページ件数
        $pagemax = PAGEMAX;
        if ( ! empty($this->input->post('limit')))
        {
            $this->session->limit = $this->input->post('limit');
        }
        if (isset($this->session->limit))
        {
            $this->pagemax = $this->session->limit;
        }
    }

    public function index()
	{
	    $this->data['title'] = 'レポート';

	    if (isset($this->session->site_id))
        {
            redirect('lp_reports/site/'.$this->session->site_id.'/__');
        }
	    // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_reports', $this->data);
        $this->load->view('_footer', $this->data);
	}

	public  function site($site_id = 0, $directory = '__', $sort = 'url_a', $page = 0)
    {
        if (empty($directory))
        {
            redirect('lp_reports/site/'.$site_id.'/__');
        }

        // エクセルモデル
        $this->load->model('excel_model');

        // ページネーションライブラリをロード
        $this->load->library('pagination');

        // セッションに現在見ているサイトIDを登録
        $this->session->site_id = $site_id;

        // サイトが変わったらサイトメニューを更新
        $this->data['site_menues'] = $this->menues_model->sitemenues($this->sites, $this->session->site_id);

        $this->data['title'] = 'レポート';

        // サイト情報
        $data = array();
        $data[] = array('kind' => 'where', 'field_name' => 'sites.id', 'value' => $this->session->site_id);
        $site_info = $this->sites_model->load($data);
        $this->data['siteinfo'] = $site_info[0];

        // 優先度
        $google_cache_days_check = $index_check_check = array("off","off","off");
        $google_cache_days = array();
        $index_check = array();

        $priority_data = $this->lowpages_model->priority_load($this->session->site_id);

        // インデックスチェック履歴数
        $settingdata = array();
        $settingdata[] = array('kind' => 'where', 'field_name' => 'site_id', 'value' => $site_id);
        $settingdata[] = array('kind' => 'where', 'field_name' => 'name', 'value' => 'indexmonth');
        $indexmonthes = $this->lowpages_model->setting_load($settingdata);
        if ( isset($indexmonthes[0]) )
        {
            $indexmonth = $indexmonthes[0]['value'];
        }
        else
        {
            $indexmonth = INDEXMONTH;
        }
        $this->data['indexmonth'] = $indexmonth;

        if ($directory !== NULL && $directory !== "__")
        {
            $dir = urldecode($directory);
        }
        else
        {
            $dir = NULL;
        }

        $this->data['directory'] = $directory;

        switch ($sort)
        {
            case "gcache_d":
                $sortcol = 'lowpages.cache_datetime';
                $sortasc = 'DESC';
                break;
            case "gcache_a":
                $sortcol = 'lowpages.cache_datetime';
                $sortasc = 'ASC';
                break;
            case "update_d":
                $sortcol = 'lowpages.update_datetime';
                $sortasc = 'DESC';
                break;
            case "update_a":
                $sortcol = 'lowpages.update_datetime';
                $sortasc = 'ASC';
                break;
            case "title_d":
                $sortcol = 'lowpages.title';
                $sortasc = 'DESC';
                break;
            case "title_a":
                $sortcol = 'lowpages.title';
                $sortasc = 'ASC';
                break;
            case "dir_d":
                $sortcol = 'breadcrumb';
                $sortasc = 'DESC';
                break;
            case "dir_a":
                $sortcol = 'breadcrumb';
                $sortasc = 'ASC';
                break;
            case "url_d":
                $sortcol = 'lowpages.address';
                $sortasc = 'DESC';
                break;
            case "url_a":
            default:
                $sortcol = 'lowpages.address';
                $sortasc = 'ASC';
                break;
        }


        $this->data['reports'] = $reports = $this->lowpages_model->build_report($this->session->site_id, $indexmonth, $dir, $sortcol, $sortasc, $page, $this->pagemax);

        // ページネーション
        $this->load->library('pagination');
        $config['base_url'] = base_url('lp_reports/site/'.$site_id.'/'.$directory.'/'.$sort.'/');
        $config['total_rows'] = $this->lowpages_model->count_report($this->session->site_id, $indexmonth, $dir);
        $config['per_page'] = $this->pagemax;
        $config['full_tag_open'] = '<nav aria-label="Reports navigation"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['first_link'] = '|&lt;';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '&gt;|';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $this->data['pagination'] = $this->pagination->create_links();
        $this->data['pages'] = ceil($config['total_rows'] / $this->pagemax);
        $this->data['cur_page'] = $page / $this->pagemax + 1;

        // 階層の作成
        $directories = array();
        $directories_data = $this->lowpages_model->build_directories($this->session->site_id);

        foreach ($directories_data as $val)
        {
            $path = $val['breadcrumb1'];
            $path .= ( ! empty($val['breadcrumb2'])) ? '&nbsp;&gt;&nbsp;'.$val['breadcrumb2'] : '';
            $path .= ( ! empty($val['breadcrumb3'])) ? '&nbsp;&gt;&nbsp;'.$val['breadcrumb3'] : '';
            $path .= ( ! empty($val['breadcrumb4'])) ? '&nbsp;&gt;&nbsp;'.$val['breadcrumb4'] : '';
            $path .= ( ! empty($val['breadcrumb5'])) ? '&nbsp;&gt;&nbsp;'.$val['breadcrumb5'] : '';

            $directories[$val['breadcrumb']] = array(
                'breadcrumb1' => $val['breadcrumb1'],
                'breadcrumb2' => $val['breadcrumb2'],
                'breadcrumb3' => $val['breadcrumb3'],
                'breadcrumb4' => $val['breadcrumb4'],
                'breadcrumb5' => $val['breadcrumb5'],
                'path' => $path
            );
        }

        $this->data['directories'] = $directories;

        // エクセルファイルを作成
        $template = 'reports-s%s-%s-%s.xlsx';
        $filename = sprintf($template, $site_id, $directory, date("Ymd"));
        if (file_exists(BASEPATH . '../outputs/' . $filename))
        {
            $this->data['excelfile'] = $filename;
        }
        else
        {
            $reports_all = $this->lowpages_model->build_report($this->session->site_id, $indexmonth, $dir, 0, 0);

            try
            {
                $this->data['excelfile'] = $this->excel_model->write_reports($reports_all, $priority_data, $filename);
            }
            catch (Exception $e)
            {
                $this->data['excelfile'] = $e->getMessage();
            }
        }

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_reports', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function file($filename = NULL)
    {
        $this->load->helper('download');

        force_download(BASEPATH . '../outputs/'.$filename, NULL);

        if (isset($this->session->site_id))
        {
            redirect('lp_reports/site/'.$this->session->site_id);
        }
    }
}
