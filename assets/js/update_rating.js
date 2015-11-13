function updateRating(user_id, page, vote, click) 
{ 
    if (click) {
        if(vote==1) {
            document.getElementById('button'+user_id+'plus').disabled=true;
        } else {
            document.getElementById('button'+user_id+'minus').disabled=true;
        }  
    } else {
        if(vote==1) {
            document.getElementById('button'+user_id+'plus').disabled=true;
            document.getElementById('button'+user_id+'minus').disabled=true;     
        } else {           
            document.getElementById('button'+user_id+'minus').disabled=true;
            document.getElementById('button'+user_id+'plus').disabled=true;
        }
    }
    if(page==1) { //Перегружаем главную страницу
        $("#main").empty(); //Удаляем содержимое
        $("<img src='../uploads/ajax-loader.gif' />").appendTo("#main"); //Подгружаем прелоадер
        $("#main").load("http://localhost/thebestpeople/index.php/main",{vote:vote,click:click,user_id:user_id}); //Перегружаем блок
    } else if (page==0) { //Перегружаем личный профиль (информацию о пользователе и историю)
        $("#user-info").empty();
        $("#votes").empty();
        $("<img src='../uploads/ajax-loader.gif' />").appendTo("#user-info");
        $("#user-info").load("http://localhost/thebestpeople/index.php/user_profile",{vote:vote,click:click,user_id:user_id});
    }
}




function showPassword() 
{
    var pass = document.getElementById('password');
    var check = document.getElementById('check');
    if(check.checked==true) { 
        pass.type= "text"; 
    } else {
        pass.type= "password"; 
    }   
}

function addComment(user_id) { //Загружаем комментарий в БД и перезагружаем комментарии на странице.
    var comment = $("#comment").val();
    if (comment) {
        $("#comments").empty();
        $("<img src='../uploads/ajax-loader.gif' />").appendTo("#comments");
        $("#comments").load("http://localhost/thebestpeople/index.php/user_profile",{comment:comment,user_id:user_id}); 
    }
}