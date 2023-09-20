<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?php 
    $method = $_SERVER['REQUEST_METHOD'];
    $APPLICATION->IncludeComponent("jam:jam.ip", "", array(
        "METHOD" => $method,
        "AJAX_MODE" => "Y",
        ),
        false,
        array('HIDE_ICONS'=>'Y')
    );

    //echo json_encode($arResult);
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>