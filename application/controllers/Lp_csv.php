<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property Users_model $users_model
 * @property Sites_model $sites_model
 * @property Excel_model $excel_model
 * @property Menues_model $menues_model
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Lp_csv extends CI_Controller {

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


        // ログインしてなかったら、ログイン画面に戻る
        if ( ! $this->session->userdata("is_logged_in"))
        {
            redirect('login');
        }

        // TODO: メニュー
        $menues = $this->menues_model->load($this->session->users['status'], 'lowpages', 'lp_csv');
        $this->data['menues'] = $menues;
    }

    public function index()
	{
	    $this->data['title'] = 'CSVファイル';

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

    public function upload()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = '*';
        if ($this->input->post('uri_string') == "lp_csv/csv")
        {
            $config['file_name']        = 'upload-'.date("Ymd").'.csv';
        }
        if ($this->input->post('uri_string') == "lp_csv/excel")
        {
            $config['file_name']        = 'upload-'.date("Ymd").'.xlsx';
        }

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('filename'))
        {
            $this->data['error'] = array('error' => $this->upload->display_errors());
        }
        else
        {
            $this->data['data'] = array('upload_data' => $this->upload->data());

            $this->data['rowdata'] = $this->excel_model->read($this->upload->data('full_path'));
        }

        // ページを表示
        $this->load->view('_header', $this->data);
        $this->load->view('lp_csv', $this->data);
        $this->load->view('_footer', $this->data);
    }

}
