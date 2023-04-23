<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Tên');
$sheet->setCellValue('B1', 'Từ khóa phụ');
$sheet->setCellValue('C1', 'Lưu ý');
$sheet->setCellValue('D1', 'Dạng từ khóa');
$sheet->setCellValue('E1', 'Số từ');
$sheet->setCellValue('F1', 'Dự án');
$sheet->setCellValue('G1', 'Deadline (yyyy-mm-dd hh:ii:ss)');
$sheet->setCellValue('H1', 'Duyệt outline? (0:Không, 1:Có)');


$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="upload-product.xlsx"');
$writer->save('php://output');