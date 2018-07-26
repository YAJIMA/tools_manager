<?php
/**
 * Created by PhpStorm.
 * User: yuichiro
 * Date: 2018/06/13
 * Time: 0:02
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Lowpages_model extends CI_Model
{
    // 除外パターンをロード
    public function setting_load($data = NULL)
    {
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

        }

        $this->db->from('settings');

        // クエリ実行
        $query = $this->db->get();

        // 結果を配列で取得
        $result = $query->result_array();

        return $result;
    }

    // 優先度の設定をロード
    public function priority_load($site_id = 0)
    {
        $result = array(
            'google_cache_days' => array(
                0 => GOOGLECACHEDAYS0,
                1 => GOOGLECACHEDAYS1,
                2 => GOOGLECACHEDAYS2,
            ),
            'index_check' => array(
                0 => INDEXCHECK0,
                1 => INDEXCHECK1,
                2 => INDEXCHECK2,
            ),
            'google_cache_days_check' => array(
                0 => GOOGLECACHEDAYSCHECK0,
                1 => GOOGLECACHEDAYSCHECK1,
                2 => GOOGLECACHEDAYSCHECK2,
            ),
            'index_check_check' => array(
                0 => INDEXCHECKCHECK0,
                1 => INDEXCHECKCHECK1,
                2 => INDEXCHECKCHECK2,
            ),
        );

        if ($site_id > 0)
        {
            $this->db->where('site_id', $site_id);

            $params = array(
                'google_cache_days_0',
                'google_cache_days_1',
                'google_cache_days_2',
                'index_check_0',
                'index_check_1',
                'index_check_2'
            );
            $this->db->where_in('name',$params);

            $this->db->from('settings');

            // クエリ実行
            $query = $this->db->get();

            // 結果を配列で取得
            $result_array = $query->result_array();

            foreach ($result_array as $item)
            {
                switch ($item['name'])
                {
                    case "google_cache_days_0":
                        $result['google_cache_days'][0] = $item['value'];
                        $result['google_cache_days_check'][0] = "on";
                        break;
                    case "google_cache_days_1":
                        $result['google_cache_days'][1] = $item['value'];
                        $result['google_cache_days_check'][1] = "on";
                        break;
                    case "google_cache_days_2":
                        $result['google_cache_days'][2] = $item['value'];
                        $result['google_cache_days_check'][2] = "on";
                        break;
                    case "index_check_0":
                        $result['index_check'][0] = $item['value'];
                        $result['index_check_check'][0] = "on";
                        break;
                    case "index_check_1":
                        $result['index_check'][1] = $item['value'];
                        $result['index_check_check'][1] = "on";
                        break;
                    case "index_check_2":
                        $result['index_check'][2] = $item['value'];
                        $result['index_check_check'][2] = "on";
                        break;
                }
            }
            unset($item);
        }

        return $result;
    }

    // 除外パターンを登録
    public function setting_update()
    {
        $site_id = $this->input->post("site_id");
        $rowdata = array();

        // 除外パターンを登録
        if ( ! empty($this->input->post("pattern")))
        {
            // 既存のレコードを削除
            $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'pattern'));

            foreach ($this->input->post("pattern") as $pattern)
            {
                if ( ! empty($pattern))
                {
                    $rowdata[] = array(
                        'site_id' => $site_id,
                        'name' => 'pattern',
                        'value' => $pattern
                    );
                }
            }
        }

        // インデックス履歴を登録
        if ( ! empty($this->input->post("indexmonth")))
        {
            // 既存のレコードを削除
            $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'indexmonth'));

            if ( ! empty($this->input->post("indexmonth")))
            {
                $rowdata[] = array(
                    'site_id' => $site_id,
                    'name' => 'indexmonth',
                    'value' => $this->input->post("indexmonth")
                );
            }
        }

        // 優先度設定を登録
        // 優先度：高
        // 既存のレコードを削除
        $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'google_cache_days_0'));
        $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'index_check_0'));

        if ( ! empty($this->input->post("google_cache_days_check_0")) && ! empty($this->input->post("google_cache_days_0")))
        {

            if ( ! empty($this->input->post("google_cache_days_0")) && $this->input->post("google_cache_days_check_0") == "on")
            {
                $rowdata[] = array(
                    'site_id' => $site_id,
                    'name' => 'google_cache_days_0',
                    'value' => $this->input->post("google_cache_days_0")
                );
            }
        }
        if ( ! empty($this->input->post("index_check_check_0")) && ! empty($this->input->post("index_check_0")))
        {

            if ( ! empty($this->input->post("index_check_0")) && $this->input->post("index_check_check_0") == "on")
            {
                $rowdata[] = array(
                    'site_id' => $site_id,
                    'name' => 'index_check_0',
                    'value' => $this->input->post("index_check_0")
                );
            }
        }

        // 優先度：中
        // 既存のレコードを削除
        $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'google_cache_days_1'));
        $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'index_check_1'));

        if ( ! empty($this->input->post("google_cache_days_check_1")) && ! empty($this->input->post("google_cache_days_1")))
        {

            if ( ! empty($this->input->post("google_cache_days_1")) && $this->input->post("google_cache_days_check_1") == "on")
            {
                $rowdata[] = array(
                    'site_id' => $site_id,
                    'name' => 'google_cache_days_1',
                    'value' => $this->input->post("google_cache_days_1")
                );
            }
        }
        if ( ! empty($this->input->post("index_check_check_1")) && ! empty($this->input->post("index_check_1")))
        {

            if ( ! empty($this->input->post("index_check_1")) && $this->input->post("index_check_check_1") == "on")
            {
                $rowdata[] = array(
                    'site_id' => $site_id,
                    'name' => 'index_check_1',
                    'value' => $this->input->post("index_check_1")
                );
            }
        }

        // 優先度：低
        // 既存のレコードを削除
        $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'google_cache_days_2'));
        $this->db->delete('settings', array('site_id' => $site_id, 'name' => 'index_check_2'));

        if ( ! empty($this->input->post("google_cache_days_check_2")) && ! empty($this->input->post("google_cache_days_2")))
        {

            if ( ! empty($this->input->post("google_cache_days_2")) && $this->input->post("google_cache_days_check_2") == "on")
            {
                $rowdata[] = array(
                    'site_id' => $site_id,
                    'name' => 'google_cache_days_2',
                    'value' => $this->input->post("google_cache_days_2")
                );
            }
        }
        if ( ! empty($this->input->post("index_check_check_2")) && ! empty($this->input->post("index_check_2")))
        {

            if ( ! empty($this->input->post("index_check_2")) && $this->input->post("index_check_check_2") == "on")
            {
                $rowdata[] = array(
                    'site_id' => $site_id,
                    'name' => 'index_check_2',
                    'value' => $this->input->post("index_check_2")
                );
            }
        }

        // アップデート
        if (count($rowdata) > 0)
        {
            $this->db->insert_batch('settings', $rowdata);
        }
    }

    // パターンマッチ
    public function pattern_match($rowdata = NULL, $patterns = NULL)
    {
        if (empty($rowdata))
        {
            return FALSE;
        }
        elseif (empty($patterns))
        {
            return $rowdata;
        }

        foreach ($rowdata as $key => $value)
        {
            $address = $value['Address'];
            foreach ($patterns as $pattern)
            {
                $patternstr = $pattern['value'];
                if (strpos($patternstr, '*') === 0)
                {
                    if (substr($patternstr, -1) === "*")
                    {
                        // 部分一致
                        $pat = str_replace("*", "", $patternstr);
                        if (strpos($address, $pat, 0) > 0)
                        {
                            unset($rowdata[$key]);
                        }
                    }
                    else
                    {
                        // 後方一致
                        $pat = str_replace("*", "", $patternstr);
                        if (strpos($address, $pat, 0) !== FALSE)
                        {
                            unset($rowdata[$key]);
                        }
                    }
                }
                elseif (substr($patternstr, -1) === "*")
                {
                    // 前方一致
                    $pat = str_replace("*", "", $patternstr);
                    if (strpos($address, $pat, 0) === 0)
                    {
                        unset($rowdata[$key]);
                    }
                }
                else
                {
                    // 部分一致
                    $pat = str_replace("*", "", $patternstr);
                    if (strpos($address, $pat, 0) > 0)
                    {
                        unset($rowdata[$key]);
                    }
                }
            }
            unset($pattern);
        }
        unset($key, $value);

        return $rowdata;
    }

    // 選択データ
    public function selected_data($rowdata = NULL, $patterns = NULL)
    {
        if (empty($rowdata))
        {
            return FALSE;
        }
        elseif (empty($patterns))
        {
            return $rowdata;
        }

        foreach ($rowdata as $key => $value)
        {
            if (isset($value['Address']))
            {
                $address = $value['Address'];
            }
            else
            {
                $address = $value[0];
            }

            if ( ! in_array($address, $patterns))
            {
                unset($rowdata[$key]);
            }
        }
        unset($key, $value);

        return $rowdata;
    }

    public function insert_data($rowdata = NULL, $site_id = 0)
    {
        if (empty($rowdata))
        {
            return FALSE;
        }

        // トランザクションのスタート
        $this->db->trans_begin();

        foreach ($rowdata as $row)
        {
            $rowvalues = array();

            if (isset($row['Address']))
            {
                $rowvalues['address'] = $row['Address'];
            }
            if (isset($row['Content']))
            {
                $rowvalues['content'] = $row['Content'];
            }
            if (isset($row['Title 1']))
            {
                $rowvalues['title'] = $row['Title 1'];
            }
            if (isset($row['Title 1 Length']))
            {
                $rowvalues['title_length'] = $row['Title 1 Length'];
            }
            if (isset($row['Title 1 Pixel Width']))
            {
                $rowvalues['title_width'] = $row['Title 1 Pixel Width'];
            }
            if (isset($row['Meta Description 1']))
            {
                $rowvalues['description'] = $row['Meta Description 1'];
            }
            if (isset($row['Meta Description 1 Length']))
            {
                $rowvalues['description_length'] = $row['Meta Description 1 Length'];
            }
            if (isset($row['Meta Description 1 Pixel Width']))
            {
                $rowvalues['description_width'] = $row['Meta Description 1 Pixel Width'];
            }
            if (isset($row['Meta Keyword 1']))
            {
                $rowvalues['keyword'] = $row['Meta Keyword 1'];
            }
            if (isset($row['Meta Keywords 1 Length']))
            {
                $rowvalues['keyword_length'] = $row['Meta Keywords 1 Length'];
            }
            if (isset($row['H1-1']))
            {
                $rowvalues['h1'] = $row['H1-1'];
            }
            if (isset($row['H1-1 length']))
            {
                $rowvalues['h1_length'] = $row['H1-1 length'];
            }
            if (isset($row['H2-1']))
            {
                $rowvalues['h2'] = $row['H2-1'];
            }
            if (isset($row['H2-1 length']))
            {
                $rowvalues['h2_length'] = $row['H2-1 length'];
            }
            if (isset($row['Meta Robots 1']))
            {
                $rowvalues['robots'] = $row['Meta Robots 1'];
            }
            if (isset($row['Canonical Link Element 1']))
            {
                $rowvalues['canonical'] = $row['Canonical Link Element 1'];
            }
            if (isset($row['Size']))
            {
                $rowvalues['size'] = $row['Size'];
            }
            if (isset($row['Word Count']))
            {
                $rowvalues['word_counts'] = $row['Word Count'];
            }
            if (isset($row['Text Ratio']))
            {
                $rowvalues['text_ratio'] = $row['Text Ratio'];
            }
            if (isset($row['Crawl Depth']))
            {
                $rowvalues['crawl_depth'] = $row['Crawl Depth'];
            }
            if (isset($row['Inlinks']))
            {
                $rowvalues['inlinks'] = $row['Inlinks'];
            }
            if (isset($row['Unique Inlinks']))
            {
                $rowvalues['unique_inlinks'] = $row['Unique Inlinks'];
            }
            if (isset($row['% of Total']))
            {
                $rowvalues['percent'] = $row['% of Total'];
            }
            if (isset($row['Outlinks']))
            {
                $rowvalues['outlinks'] = $row['Outlinks'];
            }
            if (isset($row['Unique Outlinks']))
            {
                $rowvalues['unique_outlinks'] = $row['Unique Outlinks'];
            }
            if (isset($row['External Outlinks']))
            {
                $rowvalues['external_outlinks'] = $row['External Outlinks'];
            }
            if (isset($row['Unique External Outlinks']))
            {
                $rowvalues['unique_external_outlinks'] = $row['Unique External Outlinks'];
            }
            if (isset($row['Response Time']))
            {
                $rowvalues['response_time'] = $row['Response Time'];
            }

            if (isset($this->session->columns))
            {
                foreach ($this->session->columns as $key => $val)
                {
                    $rowvalues[$key] = $row[$val];
                }
                unset($key,$val);
            }

            $rowvalues['upload_datetime'] = date("Y-m-d H:i:s");
            $rowvalues['site_id'] = $site_id;

            // インサート
            $this->db->replace('lowpages', $rowvalues);

        }
        unset($row);

        // トランザクション
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        }
        else
        {
            $this->db->trans_commit();
            return TRUE;
        }

    }

    public function cache_update($rowdata = NULL)
    {
        if (empty($rowdata))
        {
            return FALSE;
        }

        // トランザクションのスタート
        $this->db->trans_begin();

        foreach ($rowdata as $row)
        {
            $address = $row[0];
            $data = array("cache_datetime" => strtotime($row[1]));

            $this->db->where('address', $address);
            $this->db->update('lowpages', $data);
        }

        // トランザクション
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return FALSE;
        }
        else
        {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    /**
     * @param $site_id
     * @return array
     */
    public function build_directories($site_id)
    {
        $result = array();

        // クエリビルダ
        $this->db->select('lowpages.breadcrumb1, lowpages.breadcrumb2, lowpages.breadcrumb3, lowpages.breadcrumb4, lowpages.breadcrumb5, CONCAT(IFNULL(tm_lowpages.breadcrumb1,""),IFNULL(tm_lowpages.breadcrumb2,""),IFNULL(tm_lowpages.breadcrumb3,""),IFNULL(tm_lowpages.breadcrumb4,""),IFNULL(tm_lowpages.breadcrumb5,"")) AS breadcrumb');
        $this->db->from('lowpages');
        $this->db->where('lowpages.site_id', $site_id);
        $this->db->group_by('breadcrumb');
        $this->db->order_by('lowpages.address','ASC');

        // クエリ実行
        $query = $this->db->get();

        // 結果を配列で取得
        $result = $query->result_array();

        return $result;
    }

    /**
     * @param $site_id
     * @param int $indexmonth
     * @param null $directory
     * @return mixed
     */
    public function count_report($site_id, $indexmonth = INDEXMONTH, $directory = NULL)
    {
        $olderdate = date('Ym', strtotime("-".$indexmonth." month"));

        // クエリビルダ
        $this->db->from('lowpages');
        $this->db->join('sites','sites.id = lowpages.site_id','left');
        $this->db->where('lowpages.site_id', $site_id);
        if ( ! empty($directory))
        {
            $this->db->like('CONCAT(IFNULL(tm_lowpages.breadcrumb1,""),IFNULL(tm_lowpages.breadcrumb2,""),IFNULL(tm_lowpages.breadcrumb3,""),IFNULL(tm_lowpages.breadcrumb4,""),IFNULL(tm_lowpages.breadcrumb5,""))', $directory, 'after');
        }
        $this->db->order_by('lowpages.address','ASC');

        return $this->db->count_all_results();
    }

    /**
     * @param $site_id
     * @param int $indexmonth
     * @param string $directory
     * @return array
     */
    public function build_report($site_id, $indexmonth = INDEXMONTH, $directory = NULL, $index = 0, $limit = PAGEMAX)
    {
        $result = array();
        $cols = array();

        $olderdate = date('Ym', strtotime("-".$indexmonth." month"));

        // 施策	URL	ディレクトリ	更新日	Googleキャッシュ日	2018/04/12	2018/03/13	2018/02/13	2018/01/06	2017/12/04

        // クエリビルダ
        $this->db->select('lowpages.id, lowpages.site_id, lowpages.address, lowpages.title, lowpages.upload_datetime, lowpages.cache_datetime, lowpages.update_datetime, lowpages.breadcrumb1, lowpages.breadcrumb2, lowpages.breadcrumb3, lowpages.breadcrumb4, lowpages.breadcrumb5, CONCAT(IFNULL(tm_lowpages.breadcrumb1,""),IFNULL(tm_lowpages.breadcrumb2,""),IFNULL(tm_lowpages.breadcrumb3,""),IFNULL(tm_lowpages.breadcrumb4,""),IFNULL(tm_lowpages.breadcrumb5,"")) AS breadcrumb, sites.name, sites.url');
        $this->db->from('lowpages');
        $this->db->join('sites','sites.id = lowpages.site_id','left');
        $this->db->where('lowpages.site_id', $site_id);
        if ( ! empty($directory))
        {
            $this->db->like('CONCAT(IFNULL(tm_lowpages.breadcrumb1,""),IFNULL(tm_lowpages.breadcrumb2,""),IFNULL(tm_lowpages.breadcrumb3,""),IFNULL(tm_lowpages.breadcrumb4,""),IFNULL(tm_lowpages.breadcrumb5,""))', $directory, 'after');
        }
        $this->db->order_by('lowpages.address','ASC');

        if ($limit > 0)
        {
            $this->db->limit($limit, $index);
        }

        //var_dump($this->db->get_compiled_select());exit();

        // クエリ実行
        $query = $this->db->get();

        // 結果を配列で取得
        $rows = $query->result_array();

        foreach ($rows as $row)
        {
            // インデックスチェックを検索
            $this->db->select('icbs.indexcheck, icbs.yyyymm');
            $this->db->from('icbs');
            $this->db->where('icbs.lowpage_id', $row['id']);
            $this->db->where('icbs.yyyymm >', $olderdate);
            $this->db->order_by('icbs.yyyymm','DESC');

            // クエリ実行
            $query2 = $this->db->get();

            // 結果を配列で取得
            $rows2 = $query2->result_array();

            $indexchecks = array();

            foreach ($rows2 as $row2)
            {
                $indexchecks[$row2['yyyymm']] = $row2['indexcheck'];

                if ( ! in_array($row2['yyyymm'], $cols))
                {
                    $cols[] = $row2['yyyymm'];
                }
            }

            $path = str_replace($row['url'], '', $row['address']);
            $directory = dirname($path);

            $result[$row['id']] = array(
                'id' => $row['id'],
                'site_id' => $row['site_id'],
                'address' => $row['address'],
                'path' => $path,
                'directory' => $directory,
                'title' => $row['title'],
                'breadcrumb' => $row['breadcrumb'],
                'breadcrumb1' => $row['breadcrumb1'],
                'breadcrumb2' => $row['breadcrumb2'],
                'breadcrumb3' => $row['breadcrumb3'],
                'breadcrumb4' => $row['breadcrumb4'],
                'breadcrumb5' => $row['breadcrumb5'],
                'upload_datetime' => $row['upload_datetime'],
                'cache_datetime' => $row['cache_datetime'],
                'update_datetime' => $row['update_datetime'],
                'indexchecks' => $indexchecks
            );

        }
        unset($row);

        // var_dump($result);exit();

        // 施策
        // 優先度パターン
        $priorities = $this->priority_load($site_id);
        $priority_str = array(0 => 'high', 1 => 'mid', 2 => 'low');

        // Googleキャッシュの古い判定とする日付
        // $olddate = mktime(0,0,0,date('n'),date('j')-40,date('Y'));

        foreach ($result as $key => $val)
        {
            $priority_value = 'none';

            for ($m = 0; $m < 2; $m++)
            {
                $i = $j = 0;
                if ( ! empty($priorities['google_cache_days_check'][$m]) && $priorities['google_cache_days_check'][$m] == "on")
                {
                    $i += 1;
                    $olddate = strtotime("-".$priorities['google_cache_days'][$m]." day");
                    if (empty($val['cache_datetime']) or strtotime($val['cache_datetime']) < $olddate)
                    {
                        $j += 1;
                    }
                }
                if ( ! empty($priorities['index_check_check'][$m]) && $priorities['index_check_check'][$m] == "on")
                {
                    $i += 10;
                    $index_month = $priorities['index_check'][$m];
                    $k = $l = 0;
                    foreach ($val['indexchecks'] as $yyyymm => $indexcheck)
                    {
                        $k += $indexcheck;
                        $l++;
                        if ($l == $index_month)
                        {
                            if ($k === 0)
                            {
                                $j += 10;
                            }
                            break;
                        }
                    }
                    unset($item);
                }
                switch ($i)
                {
                    case 11:
                        if ($j == 11)
                        {
                            $priority_value = $priority_str[$m];
                        }
                        break;
                    case 10:
                        if ($j == 10)
                        {
                            $priority_value = $priority_str[$m];
                        }
                        break;
                    case 1:
                        if ($j == 1)
                        {
                            $priority_value = $priority_str[$m];
                        }
                        break;
                    default:
                        break;
                }

                // 優先度が設定されたらループを抜ける
                if ($priority_value !== 'none')
                {
                    break;
                }
            }

            // 優先度をセット
            $result[$key]['operation'] = $priority_value;

        }
        unset($key,$val);

        // インデックスチェックの列数
        $result['cols'] = $cols;

        return $result;
    }
}