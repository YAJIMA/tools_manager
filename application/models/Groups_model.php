<?php
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-5月-27
 * Time: 0:44
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups_model extends CI_Model
{
    /**
     * @param null $data Array [[name],[url],[group_id]]
     * @return bool
     */
    public function add($data = NULL)
    {
        if ( ! empty($data))
        {
            $this->db->insert('groups', $data);
            // name, url, group_id

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function insert()
    {
        // ユーザ名
        if ( ! empty($this->input->post("name")))
        {
            $this->db->set("name", $this->input->post("name"));
        }
        $this->db->insert("groups");
        return TRUE;
    }

    /**
     * @param null $data Array [[kind],[field_name],[value]]
     * @return array
     */
    public function load($data = NULL)
    {
        $result = array();

        $this->db->select('id,name');
        $this->db->from('groups');

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

        // クエリ実行
        $query = $this->db->get();

        // 結果を配列で取得
        $result = $query->result_array();

        return $result;
    }

    /**
     * @param int $site_id
     * @param null $data Array [[name]]
     * @return bool
     */
    public function update()
    {
        // グループ名称
        if ( ! empty($this->input->post("name")))
        {
            $this->db->set("name", $this->input->post("name"));
        }
        // 削除
        if ( ! empty($this->input->post("deletecheck")))
        {
            $this->delete($this->input->post("deletecheck"));
            return TRUE;
        }
        else
        {
            $this->db->where("id", $this->input->post("group_id"));
            $this->db->update("groups");
            return TRUE;
        }
    }

    /**
     * @param int $site_id
     */
    public function delete($site_id = 0)
    {
        $this->db->delete('groups', array('id' => $site_id));
    }
}