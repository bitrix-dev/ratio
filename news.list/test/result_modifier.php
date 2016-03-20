<?
$arUserIDs = array();

function p(){
    foreach(func_get_args() AS $arg){
        echo "<pre>" . print_r($arg, true) . "</pre>";
    }
}

/*user likes*/
//get all user IDs
foreach($arResult["ITEMS"] AS $arItem){
    if(is_array($arItem["PROPERTIES"]["USER_LIKES"]["VALUE"])){
        foreach($arItem["PROPERTIES"]["USER_LIKES"]["VALUE"] AS $userID){
            $arUserIDs[$userID] = 1;
        }
    }
}

if(count($arUserIDs)){
    $arUserIDs = array_keys($arUserIDs);
    
    //get users info
    $resUsers = CUser::GetList(
        $by = "login", 
        $order = "desc", 
        array(
            "=ID" => $arUserIDs
        ),
        array(
            "FIELDS" => array("LOGIN", "ID")
        )
    );
    
    $arUsers = array();
    
    while($arUser = $resUsers->Fetch()){
        $arUsers[$arUser["ID"]] = $arUser;
    }
    
    foreach($arResult["ITEMS"] AS &$arItem){
        if(is_array($arItem["PROPERTIES"]["USER_LIKES"]["VALUE"])){
            $arItem["PROPERTIES"]["USER_LIKES"]["INFO"] = array();
            
            foreach($arItem["PROPERTIES"]["USER_LIKES"]["VALUE"] AS $userID){
                //if user like this article, add
                if(isset($arUsers[$userID])){
                    $arItem["PROPERTIES"]["USER_LIKES"]["INFO"][$userID] = $arUsers[$userID];
                }
            }
        }
    }
    
    unset($arItem);
}

global $USER;

$arResult["CURRENT_USER"] = false;

if($USER->IsAuthorized()){
    $arResult["CURRENT_USER"] = $USER->GetID();
}

$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array("CURRENT_USER"));


/*user likes*/


?>