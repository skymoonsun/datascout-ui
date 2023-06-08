<?php
require_once("/var/www/app/constant.php");

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {

    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    die("File does not exists!");

}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if($_POST){

    $target = new Target();
    $target->id = $_POST['targetId'];
    $target->find();

    $user = new User();
    $user->id = $target->user_id;
    $user->find();

    $targetTags = new TargetTag();
    $targetTags = $targetTags->search(['target_id' => $_POST['targetId']]);

    $finalArr = [];
    $indexArr = [];


//    $hc = "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0";
//
//    $curl = new Zebra_cURL();
//    $curl->option(CURLOPT_REFERER, 'http://www.google.com');
//    $curl->option(CURLOPT_USERAGENT, $hc);
//    $curl->option(CURLOPT_RETURNTRANSFER, 1);
//
//    $urlContent = $curl->scrap('https://www.donanimhaber.com/oyunlar', true);
//
//    echo $urlContent;
//    exit;

    $no = 0;
    foreach ($targetTags as $targetTag){

        $indexArr[] = $targetTag['slug'];

        $scraped = scrapeLinkMultiple($target->url, $targetTag['start_tag'], $targetTag['end_tag'], $targetTag['is_dom']);

        if(!$scraped){
            $finalArr[$no] = [];
        }else{
            $finalArr[$no] = $scraped;
        }

        $no++;
    }

//    print_r($finalArr);
//    exit;

    $result = merge_arrays_by_index($finalArr);
    $newArr = change_nested_index_names($result, array_keys($finalArr), $indexArr);

    if($target->export_type == 0){
        $xml = new SimpleXMLElement('<items/>');
        foreach ($newArr as $key => $value) {

            $person = $xml->addChild('item');

            foreach ($value as $subKey => $subValue){
                $person->addChild($subKey, $subValue);
            }

        }

        $exportTimestamp = date('Y-m-d_H-i-s');
        $exportFileName = permalink($user->name).'_'.$target->slug.'_'.$exportTimestamp.'.xml';
        $exportSuccess = $xml->saveXML($paths['path'].'/dashboard/exports/'.$exportFileName);
    }elseif($target->export_type == 1){
        $exportTimestamp = date('Y-m-d_H-i-s');
        $exportFileName = permalink($user->name).'_'.$target->slug.'_'.$exportTimestamp.'.json';
        $exportSuccess = file_put_contents($paths['path'].'/dashboard/exports/'.$exportFileName, json_encode($newArr));
    }elseif($target->export_type == 2){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Sütun başlıklarını belirleme
        $columnIndex = 1;
        foreach ($newArr[0] as $key => $value) {
            $sheet->setCellValueByColumnAndRow($columnIndex, 1, $key);
            $columnIndex++;
        }

// Verileri yerleştirme
        $rowIndex = 2;
        foreach ($newArr as $item) {
            $columnIndex = 1;
            foreach ($item as $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
                $columnIndex++;
            }
            $rowIndex++;
        }

        $writer = new Xlsx($spreadsheet);

        $exportTimestamp = date('Y-m-d_H-i-s');
        $exportFileName = permalink($user->name).'_'.$target->slug.'_'.$exportTimestamp.'.xlsx';

        $writer->save($paths['path'].'/dashboard/exports/'.$exportFileName);

        $exportSuccess = true;
    }

    if($exportSuccess){
        $createDataFeed = new DataFeed();
        $createDataFeed->user_id = $target->user_id;
        $createDataFeed->target_id = $_POST['targetId'];
        $createDataFeed->created_at = time();
        $createDataFeed->file = 'exports/'.$exportFileName;
        $createDataFeed->create();
    }

    echo "Başarılı!<hr>";
    exit;

}
