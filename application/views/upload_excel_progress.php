<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileType = 'Xlsx';
$inputFileName = './assets/excel/' . $upload_data['file_name'];

/**  Create a new Reader of the type defined in $inputFileType  **/
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
/**  Advise the Reader that we only want to load cell data  **/
$reader->setReadDataOnly(true);
/**  Load $inputFileName to a Spreadsheet Object  **/
$spreadsheet = $reader->load($inputFileName);

$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray();
array_shift($rows);
unlink($inputFileName);
// printR($rows);
foreach ($rows as $key => $row) {
    if (!empty($row[0])) {
        $data = [
            'name'          => $row[0],
            'sub_name'      => $row[1],
            'keyword_note'  => $row[2],
            'keyword_type'  => $row[3],
            'word'          => $row[4],
            'project'       => $row[5],
            'deadline'      => $row[6],
            'outline_check' => $row[7],
            'dateCreate'    => date('Y/m/d H:i:s')
        ];
        insert('keyword', $data);
    }
}
