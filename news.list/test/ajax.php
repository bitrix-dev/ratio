<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

if(isset($_REQUEST["method"]) && $_REQUEST["method"] == "setLike"){
    $isLike = (int)$_REQUEST["like"];
    
    $APPLICATION->RestartBuffer();
    
    global $USER;
    
    $userID = false;
    
    if($USER->IsAuthorized()){
        $userID = $USER->GetID();
    }
    
    if($userID){
        $articleID = (int)$_REQUEST["articleID"];
        
        $resElement = CIBlockElement::GetByID($articleID);
        
        if($arElement = $resElement->Fetch()){
            $arNewPropertyValues = array();
            
            $resProps = CIBlockElement::GetProperty(
                $arElement["IBLOCK_ID"], 
                $articleID, 
                "sort", 
                "asc", 
                Array("CODE" => "USER_LIKES")
            );
            
            while($arProps = $resProps->Fetch()){
                $arNewPropertyValues[$arProps["VALUE"]] = 1;
            }
            
            if($isLike){
                $arNewPropertyValues[$userID] = 1;
            }else{
                unset($arNewPropertyValues[$userID]);
            }
            
            CIBlockElement::SetPropertyValuesEx(
                $articleID, 
                $arElement["IBLOCK_ID"],  
                array(
                    "USER_LIKES" => array_keys($arNewPropertyValues)
                )
            );
            
            $arResponse = array(
                "result"    => 1,
                "success"   => 1,
                "like" => $isLike
            );
        }else{
            $arResponse = array(
                "result"    => 1,
                "success"   => 0,
                "errors"    => array(
                    "code"      => "element not found",
                    "message"   => "element not found"
                )
            );
        }
    }else{
        $arResponse = array(
            "result"    => 1,
            "success"   => 0,
            "errors"    => array(
                "code"      => "not auth",
                "message"   => "user is not authorised"
            )
        );
    }
    
    echo CUtil::PhpToJSObject($arResponse);
    die();
}
?>