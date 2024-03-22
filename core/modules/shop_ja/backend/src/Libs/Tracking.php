<?php
namespace  ShopJa\Libs;

class Tracking{
    public function Read($inputFileName,$inputFileType){

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        $spreadsheet = $reader->load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        echo json_encode($sheet->toArray());
        die;
    }
}