<?php
/**
 * Created by PhpStorm.
 * User: yajima
 * Date: 2018-6月-7
 * Time: 11:03
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_model extends CI_Model
{
    // use PhpOffice\PhpSpreadsheet\Spreadsheet;
    // use PhpOffice\PhpSpreadsheet\Settings;
    // use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

   public function read($filename = NULL)
    {
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filename);
        $sheet = $spreadsheet->getActiveSheet();
        $rowArray = $sheet->toArray(); // これで表の2次元配列が得られます。

        return $rowArray;
    }
}