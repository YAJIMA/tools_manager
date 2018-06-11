<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Login
 * @property Users_model $users_model
 */
class Login extends CI_Controller {

    public $data = array();

    public function __construct()
    {
        parent::__construct();

        // ログインしてなかったら、ログイン画面に戻る
        if ( ! $this->session->userdata("is_logged_in"))
        {

        }

    }

    public function index()
	{
	    // ログインページを表示
		$this->load->view('login', $this->data);
	}

	// ログアウト
    public function out()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

	// ログイン
	public function in()
    {
        $this->data['title'] = 'ログイン';

        // フォームバリデーションライブラリ
        $this->load->library('form_validation');

        // 検証ルール
        $this->form_validation->set_rules('inputUserName', 'ユーザーID', 'required|callback_login_validate_credentials');
        $this->form_validation->set_rules('inputPassword', 'パスワード', 'required',
            array('required' => '%s は必須です。')
        );

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('login', $this->data);
        }
        else
        {
            // ログイン成功
            $data = array(
                "username" => $this->input->post("inputUserName"),
                "is_logged_in" => 1
            );
            $this->session->set_userdata($data);

            redirect('home');
        }
    }

    public function login_validate_credentials()
    {
        $this->load->model('users_model');
        $login_result = $this->users_model->login();

        if($login_result === FALSE)
        {
            //ユーザーがログインできなかったときに実行する処理
            $this->form_validation->set_message("login_validate_credentials", "ユーザー名かパスワードが異なります。");
            return FALSE;
        }
        else
        {
            //ユーザーがログインできたあとに実行する処理
            return TRUE;
        }
    }

}
