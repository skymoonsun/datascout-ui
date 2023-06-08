<?php
require_once("../../constant.php");

@session_start();

$draw = $_GET['draw'];
$row = $_GET['start'];
$rowperpage = $_GET['length']; // Rows display per page
$columnIndex = $_GET['order'][0]['column']; // Column index
$columnName = $_GET['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_GET['order'][0]['dir']; // asc or desc
$searchValue = $_GET['search']['value']; // Search value

$searchQuery = array();
if($searchValue != ''){

    $searchQuery['file'] = '%'.$searchValue.'%';

}

if($columnName=="islem"){
    $columnName = 'id';
    $columnSortOrder = 'DESC';
}

## Total number of records without filtering
$g = new DataFeed();
$totalRecords = count($g->search(array("user_id" => $_SESSION["USER_ID"])));

## Total number of records with filtering
$g = new DataFeed();
$totalRecordwithFilter = count($g->search(array("user_id" => $_SESSION["USER_ID"]), array(), array(), array(), $searchQuery));

## Fetch records
$blog = new DataFeed();
$empRecords = $blog->search(array("user_id" => $_SESSION["USER_ID"]), array($columnName => $columnSortOrder), array($row => $rowperpage), array(), $searchQuery);
$data = array();

foreach ($empRecords as $record){

    $target = new Target();
    $target->id = $record['target_id'];
    $target->find();



    if($target->export_type==0){
        $export_type = "XML";
    }elseif($target->export_type==1){
        $export_type = "JSON";
    }elseif($target->export_type==2){
        $export_type = "EXCEL";
    }

    $export_type = pathinfo($record['file'], PATHINFO_EXTENSION);

        $islem = '<div class="input-group-prepend">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      İşlemler
                    </button>
                    <div class="dropdown-menu">
                    <a class="dropdown-item" onclick="return confirm(\'Silmek istediğinizden emin misiniz?\')" href="?komut=sil&id=' . $record['id'] . '">Sil</a>
                    </div>
                  </div>';

        $data[] = array(
            "id" => $record['id'],
            "target_id" => '<a href="hedef-duzenle.php?id='.$target->id.'">'.$target->name.'</a>',
            "created_at" => date('d.m.Y H:i:s', $record['created_at']),
            "export_type" => $export_type,
            "file" => '<a href="'.$paths['cdnUrl'].$record['file'].'" target="_blank" class="btn btn-block btn-success btn-sm">Dosyayı Gör</a>',
            "islem" => $islem


        );
}


$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);


?>