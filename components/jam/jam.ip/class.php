<?php



/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
Loader::includeModule("highloadblock"); 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
class JamCatalogList extends \CBitrixComponent
{
    protected function _getPost($strIp)
    {
        $pattern = '/^((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/';
        if (preg_match($pattern, $strIp)) {
            if( $this->_getHl($strIp) ){
                return ["error" => false, "data" => $this->_getHl($strIp)];
            }else {
                return ["error" => true, "data" => $this->_getSearchApi($strIp)];
            }
            
        } else {
            return ["error" => true]; 
        }
    }
    protected function _getHl($strIp)
    {
        $hlbl = 1;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $getDataHl = $entity->getDataClass(); 
        $rsData = $getDataHl::getList(array(
           "select" => array("*"),
           "order" => array("ID" => "ASC"),
           "filter" => array("UF_IP"=>$strIp),
        ));
        while($arData = $rsData->Fetch()){
           return $arData;
        }
    }

    protected function _getSearchApi($strIp)
    {
        $ch = curl_init('https://api.sypexgeo.net/json/' . $strIp);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res);
        $data = array(
            "UF_CITY" => $res->city->name_ru,
            "UF_ID" => "0",
            "UF_IP" => $res->ip,
            "UF_REG" => $res->region->iso,
        );
        $hlbl = 1;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
        $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
        $addData = $entity->getDataClass(); 
        $result = $addData::add($data);
        return $data;
    }
    
    protected function _getData()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if( $method == "POST" ){
            $strIp = $_POST["text"];
            $data = $this->_getPost($strIp);
        }
        return $data;
    }

    public function executeComponent()
    {

        // $data = FALSE;
        // $cache_id = "cache_jam_".$_GET["id"];
        // $cach_path = "cache_jam_".$_GET["id"];
        
        // $obCache = new CPHPCache;
        // if ( $this->arParams["CACHE_TIME"] > 0 && $obCache->InitCache($this->arParams["CACHE_TIME"], $cache_id, $cach_path) ) {
        //     $res = $obCache->GetVars();
        //     if ( isset($res['data']) ) {
        //         $data = $res['data'];
        //     }
        // }
        // if ( !$data )
        // {
        $data = $this->_getData();
        //     if ( $data && is_array($data) && $this->arParams["CACHE_TIME"] > 0 ) {
        //         $obCache->StartDataCache($this->arParams["CACHE_TIME"], $cache_id, $cach_path);
        //         $obCache->EndDataCache(array('data' => $data));
        //     }
        // }
        $this->arResult = $data;
        $this->IncludeComponentTemplate();
    }

}

?>