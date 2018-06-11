<?php
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-5月-27
 * Time: 0:44
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Sites_model extends CI_Model
{
    /**
     * @param null $data Array [[name],[url],[group_id]]
     * @return bool
     */
    public function add($data = NULL)
    {
        if ( ! empty($data))
        {
            $this->db->insert('sites', $data);
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
        // サイト名
        if ( ! empty($this->input->post("name")))
        {
            $this->db->set("name", $this->input->post("name"));
        }
        // URL
        if ( ! empty($this->input->post("url")))
        {
            $this->db->set("url", $this->input->post("url"));
        }
        // グループ
        if ( ! empty($this->input->post("group_id")))
        {
            $this->db->set("group_id", $this->input->post("group_id"));
        }
        $this->db->insert("sites");
        return TRUE;
    }

    /**
     * @param null $data Array [[kind],[field_name],[value]]
     * @return array
     */
    public function load($data = NULL)
    {
        $result = array();

        $this->db->select('sites.id,sites.name,url,group_id,groups.name as group_name');
        $this->db->from('sites');
        $this->db->join('groups', 'groups.id = sites.group_id', 'left');

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
     * @param null $data Array [[name],[url],[group_id]]
     * @return bool
     */
    public function update()
    {
        // サイト名
        if ( ! empty($this->input->post("name")))
        {
            $this->db->set("name", $this->input->post("name"));
        }
        // URL
        if ( ! empty($this->input->post("url")))
        {
            $this->db->set("url", $this->input->post("url"));
        }
        // グループID
        if ( ! empty($this->input->post("group_id")))
        {
            $this->db->set("group_id", $this->input->post("group_id"));
        }
        // 削除
        if ( ! empty($this->input->post("deletecheck")))
        {
            $this->delete($this->input->post("deletecheck"));
            return TRUE;
        }
        else
        {
            $this->db->where("id", $this->input->post("site_id"));
            $this->db->update("sites");
            return TRUE;
        }
    }

    /**
     * @param int $site_id
     */
    public function delete($site_id = 0)
    {
        $this->db->delete('sites', array('id' => $site_id));
    }
}