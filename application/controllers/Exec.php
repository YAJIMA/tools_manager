<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-6月-18
 * Time: 13:55
 */

class Exec extends CI_Controller {

    public $api_dir = 'http://tool.kunugi-inc.com/apis/';

    public function sitecheck($limit = 20, $usleep = 10000000, $yyyymm = NULL)
    {
        $time_start = microtime(true);

        if ( ! defined('API_DIR'))
        {
            define('API_DIR', $this->api_dir);
        }

        $response = '';
        $nowtime = time();
        if (is_cli())
        {
            echo 'START '.date("Y-n-j H:i:s", $nowtime).PHP_EOL;
        }
        else
        {
            $response .= 'START '.date("Y-n-j H:i:s", $nowtime).'<br>';
        }

        if (empty($yyyymm))
        {
            $yyyymm = date("Ym");
        }

        $this->db->select('tm_lowpages.id,tm_lowpages.address');
        $this->db->from('tm_lowpages');
        $this->db->join('tm_icbs', 'tm_icbs.lowpage_id = tm_lowpages.id and tm_icbs.yyyymm = '.$yyyymm, 'left');
        $this->db->where('tm_icbs.id IS NULL');
        $this->db->order_by('tm_lowpages.id', 'ASC');
        $this->db->limit($limit);
        $query = $this->db->get();
        //$query = $this->db->get_compiled_select();
        //echo $query; exit();

        foreach ($query->result_array() as $row)
        {
            $objectURL = substr($row['address'],strpos($row['address'],"//")+2);
            $objectURL_org = $row['address'];

            $objectURL = str_replace(array("\r\n","\r","\n"), '', $objectURL);
            $objectURL_org = str_replace(array("\r\n","\r","\n"), '', $objectURL_org);
            if(strpos($objectURL_org, '://') !== FALSE)
            {
                $objectURL = urlencode($objectURL);
                $objectURL_org = urlencode($objectURL_org);
            }

            $opts = array('http' =>
                array(
                    'method'  => 'GET',
                    'header'  => "Content-Type: text/xml\r\n"."Authorization: Basic ".base64_encode("hiro:1212")."\r\n",
                    'timeout' => 60
                )
            );

            $context  = stream_context_create($opts);
            $api_url = API_DIR."indexSearch.php?q=site%3A".$objectURL."&u=sonet";

            if (is_cli())
            {
                echo $api_url.PHP_EOL;
            }
            else
            {
                $response .= 'API URL : '.$api_url.'<br>';
            }

            $index = file_get_contents($api_url, false, $context, 0, 40000);

            if (is_numeric($index) && $index >= 0)
            {
                $this->db->set('lowpage_id', $row['id']);
                $this->db->set('yyyymm', $yyyymm);
                $this->db->set('indexcheck', $index);
                $this->db->insert('tm_icbs');
            }

            if (is_cli())
            {
                echo $objectURL.PHP_EOL;
                echo 'So-net:'.$index.PHP_EOL;
            }
            else
            {
                $response .= 'URL : '.$objectURL.'<br>';
                $response .= 'So-net インデックス :'.$index.'<br>';
            }

            //7秒間遅延させる
            usleep($usleep);
        }

        if (is_cli())
        {
            echo 'END '.date("Y-n-j H:i:s", time()).PHP_EOL;
        }
        else
        {
            $response .= 'END '.date("Y-n-j H:i:s", time()).'<br>';
        }

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        if (is_cli())
        {
            echo "実行時間:{$time}秒".PHP_EOL;
        }
        else
        {
            $response .= "実行時間:{$time}秒<br>";
        }

        if ( ! is_cli())
        {
            $response .= '<br><a href="'.current_url().'">もう一度実行</a><br>終了する場合は、タブ（ウィンドウ）を閉じて下さい。';
            echo $response;
        }
    }
}