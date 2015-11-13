function updateRating(user_id, page, vote, click, url) 
{ 
    if (click) {
        if(vote==1) {
            $('#'+'button'+user_id+'plus').attr('disabled', true);
        } else {
            $('#'+'button'+user_id+'minus').attr('disabled', true);
        }  
    } else {
        $('#'+'button'+user_id+'plus').attr('disabled', true);
        $('#'+'button'+user_id+'minus').attr('disabled', true);
    }
    
    if(page==1) { //Перегружаем главную страницу
        $("#main").empty(); //Удаляем содержимое
        $("<img src='../uploads/ajax-loader.gif' />").appendTo("#main"); //Подгружаем прелоадер
        $("#main").load(url,{vote:vote,click:click,user_id:user_id}); //Перегружаем блок
    } else if (page==0) { //Перегружаем личный профиль (информацию о пользователе и историю)
        $("#user-info").empty();
        $("#votes").empty();
        $("<img src='../uploads/ajax-loader.gif' />").appendTo("#user-info");
        $("#user-info").load(url,{vote:vote,click:click,user_id:user_id});
    }
}




function showPassword() 
{
    var check = $("#check").prop("checked");
    if(check) { 
        $("#password").attr("type","text")
    } else {
         $("#password").attr("type","password")
    }   
}

function addComment(user_id, url) { //Загружаем комментарий в БД и перезагружаем комментарии на странице.
    var comment = $("#comment").val();
    if (comment) {
        $("#comments").empty();
        $("<img src='../uploads/ajax-loader.gif' />").appendTo("#comments");
        $("#comments").load(url,{comment:comment,user_id:user_id}); 
    }
}