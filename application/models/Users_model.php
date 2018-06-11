<?php
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-5月-14
 * Time: 0:26
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model
{
    public function login()
    {
        $this->db->where("username", $this->input->post("inputUserName") );	//POSTされたユーザーIDとDB情報を照合する
        $this->db->where("password", sha1( md5( $this->input->post("inputPassword") ) ) );	//POSTされたパスワードとDB情報を照合する
        $this->db->where("status >", 0);
        $query = $this->db->get("users");

        if ($query->num_rows() == 1)
        {   //ユーザーが存在した場合の処理
            return TRUE;
        }
        else
        {   //ユーザーが存在しなかった場合の処理
            return FALSE;
        }
    }

    public function add_validate()
    {
        $this->db->where("username", $this->input->post("inputUserName"));
        $query = $this->db->get("users");
        if ($query->num_rows() > 0)
        {
            return FALSE;
        }
        else
        {
            $data = array(
                "username" => $this->input->post("inputUserName"),
                "password" => sha1( md5( $this->input->post("inputPassword") ) ),
                "status" => 0
            );
            $this->db->insert("users", $data);

            $sesdata = array(
                'add_user' => $this->db->insert_id(),
                'expired' => time()+60,
            );
            $this->session->set_userdata($sesdata);
            return TRUE;
        }
    }

    public function update()
    {
        // ユーザ名
        if ( ! empty($this->input->post("username")))
        {
            $this->db->set("username", $this->input->post("username"));
        }
        // メールアドレス
        if ( ! empty($this->input->post("email")))
        {
            $this->db->set("email", $this->input->post("email"));
        }
        // パスワード
        if ( ! empty($this->input->post("password")))
        {
            $this->db->set("password", sha1( md5( $this->input->post("password") ) ));
        }
        // ステータス
        if ( ! empty($this->input->post("status")))
        {
            $this->db->set("status", $this->input->post("status"));
        }
        // グループ
        if ( ! empty($this->input->post("group_id")))
        {
            if ( ! empty($this->input->post("status")) && $this->input->post("status") > 5)
            {
                $this->db->set("group_id", 0);
            }
            else
            {
                $this->db->set("group_id", $this->input->post("group_id"));
            }
        }
        // 削除
        if ( ! empty($this->input->post("deletecheck")))
        {
            $this->db->set("status", 0);
        }
        $this->db->where("id", $this->input->post("user_id"));
        $this->db->update("users");
        return TRUE;
    }

    public function insert()
    {
        // ユーザ名
        if ( ! empty($this->input->post("username")))
        {
            $this->db->set("username", $this->input->post("username"));
        }
        // メールアドレス
        if ( ! empty($this->input->post("email")))
        {
            $this->db->set("email", $this->input->post("email"));
        }
        // パスワード
        if ( ! empty($this->input->post("password")))
        {
            $this->db->set("password", sha1( md5( $this->input->post("password") ) ));
        }
        // ステータス
        if ( ! empty($this->input->post("status")))
        {
            $this->db->set("status", $this->input->post("status"));
        }
        // グループ
        if ( ! empty($this->input->post("group_id")))
        {
            if ( ! empty($this->input->post("status")) && $this->input->post("status") > 5)
            {
                $this->db->set("group_id", 0);
            }
            else
            {
                $this->db->set("group_id", $this->input->post("group_id"));
            }
        }
        $this->db->insert("users");
        return TRUE;
    }

    public function load($data = NULL)
    {
        $result = array();

        // 特定のデータを探す
        if ( ! empty($data))
        {
            foreach ($data as $val)
            {
                switch (strtolower($val['kind']))
                {
                    case "where":
                        $this->db->where($val['field_name'], $val['value']);
                        break;
                    case "or_where":
                        // 次を生成: WHERE name != 'Joe' OR id > 50
                        $this->db->or_where($val['field_name'], $val['value']);
                        break;
                    case "where_in":
                        // 次を生成: WHERE username IN ('Frank', 'Todd', 'James')
                        $this->db->where_in($val['field_name'], $val['value']);
                        break;
                    case "or_where_in":
                        // 次を生成: OR username IN ('Frank', 'Todd', 'James')
                        $this->db->or_where_in($val['field_name'], $val['value']);
                        break;
                    case "where_not_in":
                        // 次を生成: WHERE username NOT IN ('Frank', 'Todd', 'James')
                        $this->db->where_not_in($val['field_name'], $val['value']);
                        break;
                    case "or_where_not_in":
                        // 次を生成: OR username NOT IN ('Frank', 'Todd', 'James')
                        $this->db->or_where_not_in($val['field_name'], $val['value']);
                        break;
                    case "like":
                        // 次を生成: WHERE `title` LIKE '%match%' ESCAPE '!'
                        $this->db->like($val['field_name'], $val['value']);
                        break;
                    case "or_like":
                        // WHERE `title` LIKE '%match%' ESCAPE '!' OR  `body` LIKE '%match%' ESCAPE '!'
                        $this->db->or_like($val['field_name'], $val['value']);
                        break;
                    case "not_like":
                        // WHERE `title` NOT LIKE '%match% ESCAPE '!'
                        $this->db->not_like($val['field_name'], $val['value']);
                        break;
                    case "or_not_like":
                        // WHERE `title` LIKE '%match% OR  `body` NOT LIKE '%match%' ESCAPE '!'
                        $this->db->or_not_like($val['field_name'], $val['value']);
                        break;
                    case "order_by":
                        $this->db->order_by($val['field_name'], $val['value']);
                        break;
                }
            }
            unset($key, $val);

            if (isset($data['where']))
            {
                foreach ($data['where'] as $val)
                {
                    $this->db->where($val['field_name'], $val['value']);
                }
                unset($val);
            }
        }

        $this->db->where("status >", 0);
        $this->db->order_by("username", "ASC");
        $query = $this->db->get("users");

        $result = $query->result_array();

        return $result;
    }

    public function load_one($username = NULL)
    {
        $result = array();

        $this->db->where("status >", 0);
        $this->db->where("username", $username);
        $this->db->order_by("username", "ASC");
        $query = $this->db->get("users");

        $result = $query->row_array();

        return $result;
    }
}