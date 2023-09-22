<?php
    $method = $_SERVER['REQUEST_METHOD'];
    if( $method === "POST" ){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        die(json_encode($arResult));
    }else{
?>

<div class="jam-form">
    <div class="jam-filtr-name">IP</div>
    <div class="jam-search">
        <input class="jam-input-search">
        <a class="jam-button-search" onclick="aj()">Поиск </a>
    </div>
</div>

<?php 

    } 

?>