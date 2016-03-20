function vote(el, flag){
    var items = document.querySelectorAll(".b-vote .b-vote__item");
    
    for(var i=0;i<items.length;i++){
        var item = items[i];
        
        if(flag){
            if(item.className.indexOf("b-vote__item_over") == -1){
                item.className+= " b-vote__item_over";
            }
        }else{
            item.className = item.className.replace("b-vote__item_over").trim();
        }
        
        if(item == el){
            break;
        }
    }
}