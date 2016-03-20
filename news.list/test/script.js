function setLike(el, articleID, bool){
    BX.ajax.loadJSON(
        userLikeAjaxURL, 
        {
            articleID: articleID,
            method: "setLike",
            like: bool
        },
        function(r){
            if(r && r.result){
                if(r.success){
                    if(bool){
                        el.parentNode.innerHTML = "<a href=\"#\" onclick=\"setLike(this, " + articleID + ", 0); return false;\">Уже не нравится</a>";
                    }else{
                        el.parentNode.innerHTML = "<a href=\"#\" onclick=\"setLike(this, " + articleID + ", 1); return false;\">Мне нравится статья</a>";
                    }
                }else{
                    
                }
            }
        }
    );
}