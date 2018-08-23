<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property Users_model $users_model
 * @property Sites_model $sites_model
 * @property Excel_model $excel_model
 * @property Menues_model $menues_model
 * @property Lowpages_model $lowpages_model
 */

class Lp_csv extends CI_Controller
{

    public $data = array();

    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = "";

        // モデルをロード
        $this->load->model('users_model');
        $this->load->model('sites_model');
        $this->load->model('excel_model');
        $this->load->model('menues_model');
        $this->load->model('lowpages_model');


        // ログインしてなかったら、ログイン画面に戻る
        if (!$this->session->userdata("is_logged_in")) {
            redirect('login');
        }

        // アクセス拒否
        switch ($this->session->users['status'])
        {
            case '1':
            case '7':
                redirect('lowpages');
                break;
            default:
                break;
        }

        // サイト一覧
        $wheredata = array();
        if ($this->session->users['group_id'] > 0) {
            $wheredata[] = array('kind' => 'where', 'field_name' => 'group_id', 'value' => $this->session->users['group_id']);
        }
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.group_id', 'value' => 'ASC');
        $wheredata[] = array('kind' => 'order_by', 'field_name' => 'sites.name', 'value' => 'ASC');
        $sites = $this->sites_model->load($wheredata);
        $this->data['sites'] = $sites;
        $this->data['site_menues'] = $this->menues_model->sitemenues($sites, $this->session->site_id);

        // TODO: メニュー
        $menues = $this->menues_model->load($this->session->users['status'], 'lowpages', 'lp_csv');
        $this->data['menues'] = $menues;

    }

    public function index()
    {
        $this->data['title'] = 'CSVファイル';

        $this->data['disp'] = 'form';
        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function csv()
    {
        $this->data['title'] = 'CSVファイルアップロード';

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function excel()
    {
        $this->data['title'] = 'Excelファイルアップロード';

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

    // ファイルアップロード
    public function upload()
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';

        $filetype = '';

        switch ($this->input->post('filetype')) {
            case "csv":
                $config['file_name'] = 'upload-' . date("Ymd") . '.csv';
                $filetype = 'csv';
                break;
            case "excel":
            case "xlsx":
                $config['file_name'] = 'upload-' . date("Ymd") . '.xlsx';
                $filetype = 'xlsx';
                break;
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('filename')) {
            $this->data['error'] = array('error' => $this->upload->display_errors());
        } else {
            $this->data['data'] = array('upload_data' => $this->upload->data());

            $rowdata = array();

            // ファイルパスをセッションに登録
            $full_path = $this->upload->data('full_path');
            $this->session->full_path = $full_path;
            $this->session->file_type = $filetype;
            $this->session->site_id = $site_id = $this->input->post('site_id');

            switch ($filetype) {
                case "csv":
                    $headline = $this->excel_model->csvheadread($full_path);
                    $rowdata = $this->excel_model->csvread($full_path);
                    break;
                case "xlsx":
                    $headline = $this->excel_model->headread($full_path,1);
                    $rowdata = $this->excel_model->read($full_path);
                    break;
            }

            // 除外パターン
            $wheredata[] = array('kind' => 'where', 'field_name' => 'site_id', 'value' => $site_id);
            $wheredata[] = array('kind' => 'where', 'field_name' => 'name', 'value' => 'pattern');
            $patterns = $this->lowpages_model->setting_load($wheredata);

            // パターンマッチ
            $rowdata2 = $this->lowpages_model->pattern_match($rowdata, $patterns);

            // 必須パラメータ
            $params = array(
                'Address',
                'Content',
                'Title 1'
            );

            $this->data['params'] = $params;
            $this->data['headline'] = $headline;
            $this->data['patterns'] = $patterns;
            $this->data['previewdata'] = $rowdata2;
        }

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

    public function preview()
    {
        if ( ! isset($this->session->file_type, $this->session->full_path, $this->session->site_id))
        {
            redirect('lp_csv');
        }

        switch ($this->session->file_type) {
            case "csv":
                $rowdata = $this->excel_model->csvread($this->session->full_path);
                break;
            case "xlsx":
                $rowdata = $this->excel_model->read($this->session->full_path);
                break;
        }

        // 選択カラムの保持
        $posts = $this->input->post(NULL,TRUE);
        $columns = array();
        foreach ($posts as $key => $val)
        {
            if (strpos($key, 'col_') !== FALSE && $val !== "-none-")
            {
                $valsplit = explode("--", $val, 2);
                $columns[$valsplit[0]] = $valsplit[1];
            }
        }
        unset($key,$val);
        $this->session->columns = $columns;

        // 除外パターン
        $wheredata[] = array('kind' => 'where', 'field_name' => 'site_id', 'value' => $this->session->site_id);
        $wheredata[] = array('kind' => 'where', 'field_name' => 'name', 'value' => 'pattern');
        $patterns = $this->lowpages_model->setting_load($wheredata);

        // パターンマッチ
        $rowdata = $this->lowpages_model->pattern_match($rowdata, $patterns);

        $this->data['patterns'] = $patterns;
        $this->data['rowdata'] = $rowdata;

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

    // DB登録
    public function submit()
    {
        $full_path = $this->session->full_path;
        switch ($this->session->file_type) {
            case "csv":
                $rowdata = $this->excel_model->csvread($full_path);
                break;
            case "xlsx":
                $rowdata = $this->excel_model->read($full_path);
                break;
        }

        // 除外パターン
        $site_id = $this->session->site_id;
        $wheredata[] = array('kind' => 'where', 'field_name' => 'site_id', 'value' => $site_id);
        $wheredata[] = array('kind' => 'where', 'field_name' => 'name', 'value' => 'pattern');
        $patterns = $this->lowpages_model->setting_load($wheredata);

        // パターンマッチ
        $rowdata = $this->lowpages_model->pattern_match($rowdata, $patterns);

        // 選択データ
        $rowdata = $this->lowpages_model->selected_data($rowdata, $this->input->post('addresses'));

        // インサート
        $this->lowpages_model->insert_data($rowdata, $site_id);

        $this->data['patterns'] = $patterns;
        $this->data['resultdata'] = $rowdata;

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

    // ファイルアップロード
    public function uploadcache()
    {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = '*';

        $filetype = '';

        switch ($this->input->post('filetype')) {
            case "csv":
                $config['file_name'] = 'upload-cache-' . date("Ymd") . '.csv';
                $filetype = 'csv';
                break;
            case "excel":
            case "xlsx":
                $config['file_name'] = 'upload-cache-' . date("Ymd") . '.xlsx';
                $filetype = 'xlsx';
                break;
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('filename')) {
            $this->data['error'] = array('error' => $this->upload->display_errors());
        } else {
            $this->data['data'] = array('upload_data' => $this->upload->data());

            $rowdata = array();

            // ファイルパスをセッションに登録
            $full_path = $this->upload->data('full_path');
            $this->session->full_path = $full_path;
            $this->session->file_type = $filetype;

            switch ($filetype) {
                case "csv":
                    $rowdata = $this->excel_model->csvcacheread($full_path);
                    break;
                case "xlsx":
                    $rowdata = $this->excel_model->cacheread($full_path);
                    break;
            }
            $this->data['rowdatacache'] = $rowdata;
        }

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

    // DB登録
    public function submitcache()
    {
        $full_path = $this->session->full_path;
        switch ($this->session->file_type) {
            case "csv":
                $rowdata = $this->excel_model->csvcacheread($full_path);
                break;
            case "xlsx":
                $rowdata = $this->excel_model->cacheread($full_path);
                break;
        }

        // 選択データ
        $rowdata = $this->lowpages_model->selected_data($rowdata, $this->input->post('addresses'));

        // 更新
        $this->lowpages_model->cache_update($rowdata);

        $this->data['resultdata'] = $rowdata;

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }
}
