<?php
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-6月-7
 * Time: 11:03
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class Excel_model extends CI_Model
{

    // レポートのExcel書き出し
    public function write_reports($data = NULL, $priority_data = NULL, $filename = NULL)
    {

        // 出力ファイルパス
        if ( ! empty($filename))
        {
            $filename = BASEPATH . '../outputs/' . $filename;
        }
        else
        {
            $filename = BASEPATH . '../outputs/' . 'reports-' . date("YmdHis") . '.xlsx';
        }

        if (file_exists($filename))
        {
            return FALSE;
        }

        // ファイル作成
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()
            ->setTitle('低評価ページレポート')
            ->setSubject('低評価ページレポートExcel')
            ->setCreator('クヌギツール')
            ->setCompany('株式会社クヌギ')
            ->setManager('株式会社クヌギ')
            ->setCategory('レポート')
            ->setDescription('')
            ->setKeywords('');

        $sheet = $spreadsheet->getActiveSheet();

        // シート名を設定
        $sheet->setTitle('■低品質ページ推移');

        // 年月（今月、先月）
        $cols = array();
        if (isset($data['cols']))
        {
            $cols = $data['cols'];
        }
        // 年月の最初2個を切り取り
        $colarr = array_slice($cols,0,2);

        // セルをマージ
        $sheet->mergeCells('B1:F1');
        $sheet->mergeCells('G1:K1');
        $sheet->mergeCells('L1:P1');

        $sheet->setCellValue('B1','前月差分');
        if (isset($colarr[0]))
        {
            $sheet->setCellValue('G1',substr($colarr[0],0,4).'年'.substr($colarr[0],4,2).'月');
        }
        if (isset($colarr[1]))
        {
            $sheet->setCellValue('L1',substr($colarr[1],0,4).'年'.substr($colarr[1],4,2).'月');
        }

        $sheet->getStyle('B1:P1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // ヘッダー（2行目）
        $sheet->setCellValue('A2','合計');


        $styleSumHead = [
            'font' => [
                'color' => [
                    'argb' => 'FF000000',
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFE5E5E5',
                ],
                'endColor' => [
                    'argb' => 'FFE5E5E5',
                ],
            ],
        ];
        $sheet->getStyle('A2:P2')->applyFromArray($styleSumHead);

        // ヘッダー（3行目）
        $sheet->setCellValue('A3','区分');

        $sheet->setCellValue('B3','記事数');
        $sheet->setCellValue('C3','問題なし');
        $sheet->setCellValue('D3','低品質(優先度:低)');
        $sheet->setCellValue('E3','低品質(優先度:中)');
        $sheet->setCellValue('F3','低品質(優先度:高)');

        $sheet->setCellValue('G3','記事数');
        $sheet->setCellValue('H3','問題なし');
        $sheet->setCellValue('I3','低品質(優先度:低)');
        $sheet->setCellValue('J3','低品質(優先度:中)');
        $sheet->setCellValue('K3','低品質(優先度:高)');

        $sheet->setCellValue('L3','記事数');
        $sheet->setCellValue('M3','問題なし');
        $sheet->setCellValue('N3','低品質(優先度:低)');
        $sheet->setCellValue('O3','低品質(優先度:中)');
        $sheet->setCellValue('P3','低品質(優先度:高)');

        $styleBlackHead = [
            'font' => [
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF000000',
                ],
                'endColor' => [
                    'argb' => 'FF000000',
                ],
            ],
        ];
        $sheet->getStyle('A3:P3')->applyFromArray($styleBlackHead);

        // データ書き込み

        // 4行目からデータスタート
        $row = 4;

        if (isset($colarr[0], $colarr[1]))
        {

            // 直近月のデータ
            $lastdata = $this->monthsum($data, $colarr[0]);

            // その前月のデータ
            $currentdata = current($data);
            $lastlastdata = $this->monthsumload($currentdata['site_id'], $currentdata['id'], $currentdata['path'], $colarr[1]);

            foreach ($lastdata as $path => $lastdatum)
            {
                $sheet->setCellValue('A'.$row,$path);

                $sheet->setCellValue('B'.$row,'=SUM(C'.$row.':F'.$row.')');
                $sheet->setCellValue('C'.$row,'=H'.$row.'-M'.$row);
                $sheet->setCellValue('D'.$row,'=I'.$row.'-N'.$row);
                $sheet->setCellValue('E'.$row,'=J'.$row.'-O'.$row);
                $sheet->setCellValue('F'.$row,'=K'.$row.'-P'.$row);

                $sheet->setCellValue('G'.$row,'=SUM(H'.$row.':K'.$row.')');
                $sheet->setCellValue('H'.$row,$lastdatum['none']);
                $sheet->setCellValue('I'.$row,$lastdatum['low']);
                $sheet->setCellValue('J'.$row,$lastdatum['mid']);
                $sheet->setCellValue('K'.$row,$lastdatum['high']);

                $sheet->setCellValue('L'.$row,'=SUM(M'.$row.':P'.$row.')');
                if (isset($lastlastdata[$path]['none']))
                {
                    $sheet->setCellValue('M'.$row,$lastlastdata[$path]['none']);
                }
                else
                {
                    $sheet->setCellValue('M'.$row,'0');
                }
                if (isset($lastlastdata[$path]['low']))
                {
                    $sheet->setCellValue('N'.$row,$lastlastdata[$path]['low']);
                }
                else
                {
                    $sheet->setCellValue('N'.$row,'0');
                }
                if (isset($lastlastdata[$path]['mid']))
                {
                    $sheet->setCellValue('O'.$row,$lastlastdata[$path]['mid']);
                }
                else
                {
                    $sheet->setCellValue('O'.$row,'0');
                }
                if (isset($lastlastdata[$path]['high']))
                {
                    $sheet->setCellValue('P'.$row,$lastlastdata[$path]['high']);
                }
                else
                {
                    $sheet->setCellValue('P'.$row,'0');
                }

                $row++;
            }


        }

        $row--;
        $sheet->getStyle('B4:B'.$row)->applyFromArray($styleSumHead);
        $sheet->getStyle('G4:G'.$row)->applyFromArray($styleSumHead);
        $sheet->getStyle('L4:L'.$row)->applyFromArray($styleSumHead);

        // ヘッダー行に式を挿入
        $sheet->setCellValue('B2','=SUM(C2:F2)');
        $sheet->setCellValue('C2','=SUM(C4:C'.$row.')');
        $sheet->setCellValue('D2','=SUM(D4:D'.$row.')');
        $sheet->setCellValue('E2','=SUM(E4:E'.$row.')');
        $sheet->setCellValue('F2','=SUM(F4:F'.$row.')');

        $sheet->setCellValue('G2','=SUM(H2:K2)');
        $sheet->setCellValue('H2','=SUM(H4:H'.$row.')');
        $sheet->setCellValue('I2','=SUM(I4:I'.$row.')');
        $sheet->setCellValue('J2','=SUM(J4:J'.$row.')');
        $sheet->setCellValue('K2','=SUM(K4:K'.$row.')');

        $sheet->setCellValue('L2','=SUM(M2:P2)');
        $sheet->setCellValue('M2','=SUM(M4:M'.$row.')');
        $sheet->setCellValue('N2','=SUM(N4:N'.$row.')');
        $sheet->setCellValue('O2','=SUM(O4:O'.$row.')');
        $sheet->setCellValue('P2','=SUM(P4:P'.$row.')');

        // 列Aの幅を自動調整
        $sheet->getColumnDimension('A')->setAutoSize(true);

        // 新しいシートを追加
        $sheet = $spreadsheet->createSheet();

        $sheet->setTitle('■低品質ページ詳細');

        // TODO: ヘッダー行
        $a1 = '低品質(優先度:高)=';
        $a2 = '低品質(優先度:中)=';
        $a3 = '低品質(優先度:低)=';
        if ( ! empty($priority_data))
        {
            // 優先度:高
            if (isset($priority_data['google_cache_days_check'][0]) && $priority_data['google_cache_days_check'][0] == "on")
            {
                $a1 .= 'Googleキャッシュ日が概ね';
                $a1 .= (isset($priority_data['google_cache_days'][0])) ? $priority_data['google_cache_days'][0] : GOOGLECACHEDAYS0;
                $a1 .= '日前';
            }

            if (isset($priority_data['index_check_check'][0]) && $priority_data['index_check_check'][0] == "on")
            {
                $a1 .= ',';
                $a1 .= (isset($priority_data['index_check'][0])) ? $priority_data['index_check'][0] : INDEXCHECK0;
                $a1 .= 'ヶ月連続So-netインデックスなし';
            }

            // 優先度:中
            if (isset($priority_data['google_cache_days_check'][1]) && $priority_data['google_cache_days_check'][1] == "on")
            {
                $a2 .= 'Googleキャッシュ日が概ね';
                $a2 .= (isset($priority_data['google_cache_days'][1])) ? $priority_data['google_cache_days'][1] : GOOGLECACHEDAYS1;
                $a2 .= '日前';
            }

            if (isset($priority_data['index_check_check'][1]) && $priority_data['index_check_check'][1] == "on")
            {
                $a2 .= ',';
                $a2 .= (isset($priority_data['index_check'][1])) ? $priority_data['index_check'][1] : INDEXCHECK1;
                $a2 .= 'ヶ月連続So-netインデックスなし';
            }

            // 優先度:低
            if (isset($priority_data['google_cache_days_check'][2]) && $priority_data['google_cache_days_check'][2] == "on")
            {
                $a3 .= 'Googleキャッシュ日が概ね';
                $a3 .= (isset($priority_data['google_cache_days'][2])) ? $priority_data['google_cache_days'][2] : GOOGLECACHEDAYS2;
                $a3 .= '日前';
            }

            if (isset($priority_data['index_check_check'][2]) && $priority_data['index_check_check'][2] == "on")
            {
                $a3 .= ',';
                $a3 .= (isset($priority_data['index_check'][2])) ? $priority_data['index_check'][2] : INDEXCHECK2;
                $a3 .= 'ヶ月連続So-netインデックスなし';
            }

        }
        $sheet->setCellValue('A1', $a1);
        $sheet->setCellValue('A2', $a2);
        $sheet->setCellValue('A3', $a3);
        $sheet->setCellValue('F2', 'so-netインデックスあり');
        $sheet->setCellValue('F3', 'so-netインデックスなし');

        // TODO: インデックス列の数だけ繰り返す
        $colsnum = 0;
        if (isset($data['cols']))
        {
            $colsnum = count($data['cols']);
            for ($i = 0; $i < $colsnum; $i++)
            {
                $colstring = $this->stringfromcolindex($i + 7);
                $sheet->setCellValue($colstring.'2', '=COUNTIF('.$colstring.'4:'.$colstring.'9999,">0")');
                $sheet->setCellValue($colstring.'3', '=COUNTIF('.$colstring.'4:'.$colstring.'9999,"0")');
            }
        }

        // TODO: タイトル行の書き込み
        $styleArray = array(
            'font' => array(
                'bold' => true,
                'color' => array(
                    'argb' => 'FFFFFFFF',
                ),
            ),
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array(
                    'argb' => 'FF000000',
                ),
                'endColor' => array(
                    'argb' => 'FF000000',
                ),
            ),
        );

        $sheet->setCellValue('A4','施策');
        $sheet->setCellValue('B4','URL');
        $sheet->setCellValue('C4','ディレクトリ');
        $sheet->setCellValue('D4','TITLE');
        $sheet->setCellValue('E4','更新日');
        $sheet->setCellValue('F4','Googleキャッシュ日');

        for ($i = 0; $i < $colsnum; $i++)
        {
            $colstring = $this->stringfromcolindex($i + 7);
            $sheet->setCellValue($colstring.'4',$data['cols'][$i]);
        }

        if (isset($colstring))
        {
            // タイトル行の配色
            $sheet->getStyle('A4:'.$colstring.'4')->applyFromArray($styleArray);
        }


        // TODO: データ書き込み
        $startRow = 5;
        $olddate = mktime(0,0,0,date('n'),date('j')-40,date('Y'));
        $datestr = 'DATE('.date("Y,n,j",$olddate).')';

        foreach ($data as $key => $val)
        {
            if (is_numeric($key))
            {
                // $sesaku = '=IF(E'.$startRow.' < '.$datestr.', "低品質(優先度:高)", IF(AND(F'.$startRow.'=0,G'.$startRow.'=""), "低品質(優先度:低)", IF(AND(F'. $startRow.'=0,G'.$startRow.'=0), "低品質(優先度:中)", "問題なし")))';
                $operations = array('high'=>'低品質(優先度:高)','mid'=>'低品質(優先度:中)','low'=>'低品質(優先度:低)','none'=>'問題なし');
                $sheet->setCellValue('A'.$startRow, $operations[$val['operation']]);

                $sheet->setCellValue('B'.$startRow, $val['path']);
                $sheet->setCellValue('C'.$startRow, $val['breadcrumb']);
                $sheet->setCellValue('D'.$startRow, $val['title']);
                $sheet->setCellValue('E'.$startRow, $val['upload_datetime']);
                $sheet->setCellValue('F'.$startRow, $val['cache_datetime']);

                $colnumber = 7;
                foreach ($data['cols'] as $colname)
                {
                    $colstring = $this->stringfromcolindex($colnumber);
                    if ( ! isset($val['indexchecks'][$colname]))
                    {
                        $sheet->setCellValue($colstring.$startRow, '見取得');
                    }
                    else
                    {
                        $sheet->setCellValue($colstring.$startRow, $val['indexchecks'][$colname]);
                    }
                    $colnumber++;
                }
                $startRow++;
            }
        }
        unset($key,$val);

        $sheet->getColumnDimension('A')->setWidth('24');
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);

        return $filename;
    }

    // Excelの列名をインデックス（数値）から変換
    public function stringfromcolindex($colindex)
    {
        $c = intval($colindex);
        if ($c <= 0) return '';

        $letter = '';

        while($c != 0){
            $p = ($c - 1) % 26;
            $c = intval(($c - $p) / 26);
            $letter = chr(65 + $p) . $letter;
        }
        return $letter;
    }

    // 月データ集計
    // 読み出し
    public function monthsumload($site_id,$lowpage_id,$path,$yyyymm)
    {
        $results = array();

        $this->db->select('*');
        $this->db->from('lpsum');
        $this->db->where('site_id',$site_id);
        $this->db->where('yyyymm',$yyyymm);

        $query = $this->db->get();

        foreach ($query->result_array() as $row)
        {
            $results[$row['path']] = $row;
        }

        return $results;
    }

    public function monthsum($data,$yyyymm)
    {
        $results = array();

        foreach ($data as $key => $datum)
        {
            if ( ! is_numeric($key))
            {
                continue;
            }
            if ( ! isset($results[$datum['path']]))
            {
                $results[$datum['path']] = array(
                    'site_id' => $datum['site_id'],
                    'lowpage_id' => $datum['id'],
                    'path' => $datum['path'],
                    'yyyymm' => $yyyymm,
                    'pages' => 0,
                    'none' => 0,
                    'low' => 0,
                    'mid' => 0,
                    'high' => 0,
                );
            }

            $results[$datum['path']][$datum['operation']] += 1;
            $results[$datum['path']]['pages'] += 1;

        }
        unset($datum);

        foreach ($results as $result)
        {
            $data = array(
                'site_id' => $result['site_id'],
                'lowpage_id' => $result['lowpage_id'],
                'path' => $result['path'],
                'pages' => $result['pages'],
                'none' => $result['none'],
                'low' => $result['low'],
                'mid' => $result['mid'],
                'high' => $result['high'],
                'yyyymm' => $result['yyyymm']
            );
            $this->db->replace('lpsum', $data);
        }

        return $results;
    }

    // Excelファイルのヘッダー読み込み
    public  function headread($filename = NULL, $row = 1)
    {
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filename);
        $sheet = $spreadsheet->getActiveSheet();
        $records = $sheet->toArray(); // これで表の2次元配列が得られます。
        $rowArray = array();

        $head = $records[$row];

        return $head;
    }

    // CSVファイルのヘッダー読み込み
    public function csvheadread($filename = NULL)
    {

        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset

        $records = $csv->getRecords();

        return $records[0];
    }

    // Excelファイルの読み込み
    public function read($filename = NULL)
    {
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filename);
        $sheet = $spreadsheet->getActiveSheet();
        $records = $sheet->toArray(); // これで表の2次元配列が得られます。
        $rowArray = array();

        $head = $records[1];

        foreach ($records as $offset => $record)
        {
            //$offset : represents the record offset
            //var_export($record) returns something like
            // array(
            //  'First Name' => 'jane',
            //  'Last Name' => 'doe',
            //  'E-mail' => 'jane.doe@example.com'
            // );
            //

            $rows = array();
            foreach ($head as $index => $keyname)
            {
                $rows[$keyname] = $record[$index];
            }
            unset($index, $keyname);
            //var_dump($rows);

            if ($rows['Address'] == "Address")
            {
                continue;
            }
            // 1行目はスキップ
            if ($rows['Address'] == "Internal - All")
            {
                continue;
            }
            // HTTP ステータス200のみ
            if (strval($rows['Status Code']) !== "200")
            {
                continue;
            }
            // Status = "OK"のみ
            if ($rows['Status'] !== "OK")
            {
                continue;
            }
            // noindexは除外
            if (strpos(strtolower($rows['Meta Robots 1']), "noindex") !== FALSE)
            {
                continue;
            }
            // text/html のみ
            if (strpos(strtolower($rows['Content']), "text/html") === FALSE)
            {
                continue;
            }

            //var_dump($rows);
            $rowArray[] = $rows;
            //var_dump($rows);
        }

        return $rowArray;
    }

    // Googleキャッシュファイルの読み込み
    public function cacheread($filename = NULL)
    {
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filename);
        $sheet = $spreadsheet->getActiveSheet();
        $records = $sheet->toArray(); // これで表の2次元配列が得られます。
        $rowArray = array();

        $head = $records[1];

        foreach ($records as $offset => $record)
        {
            //$offset : represents the record offset
            //var_export($record) returns something like
            // array(
            //  'First Name' => 'jane',
            //  'Last Name' => 'doe',
            //  'E-mail' => 'jane.doe@example.com'
            // );
            //

            $rowArray[] = $rows;
        }

        return $rowArray;
    }

    // CSVファイルの読み込み
    public function csvread($filename = NULL)
    {

        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(1); //set the CSV header offset

        $records = $csv->getRecords();

        $rowArray = array();

        foreach ($records as $offset => $record)
        {
            //$offset : represents the record offset
            //var_export($record) returns something like
            // array(
            //  'First Name' => 'jane',
            //  'Last Name' => 'doe',
            //  'E-mail' => 'jane.doe@example.com'
            // );
            //
            // 1行目はスキップ
            if ($record['Address'] == "Internal - All ")
            {
                continue;
            }
            // HTTP ステータス200のみ
            if ($record['Status Code'] !== "200")
            {
                continue;
            }
            // Status = "OK"のみ
            if ($record['Status'] !== "OK")
            {
                continue;
            }
            // noindexは除外
            if (strpos(strtolower($record['Meta Robots 1']), "noindex") !== FALSE)
            {
                continue;
            }
            // text/html のみ
            if (strpos(strtolower($record['Content']), "text/html") === FALSE)
            {
                continue;
            }

            $rowArray[] = $record;
        }

        return $rowArray;
    }

    // Googleキャッシュファイル（CSV）の読み込み
    public function csvcacheread($filename = NULL)
    {

        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset

        $records = $csv->getRecords();

        $rowArray = array();

        foreach ($records as $offset => $record)
        {
            //$offset : represents the record offset
            //var_export($record) returns something like
            // array(
            //  'First Name' => 'jane',
            //  'Last Name' => 'doe',
            //  'E-mail' => 'jane.doe@example.com'
            // );

            $rowArray[] = $record;
        }

        return $rowArray;
    }


}