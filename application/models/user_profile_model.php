<?php

class User_profile_model extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();   
    }

    public function getUser ($user_id,$visitor_id) //Информация о пользователе 
    {                                              //и о том, как за него проголосовал авторизованный пользователь
        $query = "SELECT  u.user_id, u.username, u.avatar, u.sex, u.rating, e.estimation FROM "
                . "(SELECT user_id, username, avatar, sex, rating "
                . "FROM users WHERE user_id = ?) u "
                . "LEFT OUTER JOIN "
                . "(SELECT  userprofile_id, estimation, visitor_id "
                . "FROM estimations WHERE visitor_id = ?) e "
                . "ON(u.user_id=e.userprofile_id) ";
        $result = $this->db->query($query,array($user_id,$visitor_id));
        return $result->row_array();
    }
    
    public function getUserGuest ($user_id) //Информация о пользователе 
    {                                       //для неавторизованных пользователей
        $query = "SELECT user_id, username, avatar, sex, rating FROM users WHERE user_id = ?";
        $result = $this->db->query($query,$user_id);
        return $result->row_array();
    }
    
    public function getVotes ($user_id) //История голосов: id, имя, пол проголосовавшего 
    {                                   //Голос, дата голоса, id владельца страницы
        $query = "SELECT u.user_id, u.username, u.sex, e.estimation, e.date, e.userprofile_id "
                . "FROM estimations e INNER JOIN users u "
                . "ON (e.visitor_id=u.user_id) "
                . "WHERE e.userprofile_id = ? ORDER BY e.date DESC, e.estimation_id DESC";
        $result = $this->db->query($query,$user_id);
        return $result->result_array();
    }
    
    public function getComments ($user_id) //Комментарий: id, имя, пол комментатора                                   
    {                                      //Комментарий, дата комментария, id владельца страницы
        $query = "SELECT u.user_id, u.username, u.sex, c.contents, c.date, c.comment_id "
                . "FROM comments c INNER JOIN users u ON (c.visitor_id=u.user_id) "
                . "WHERE c.userprofile_id = ? ORDER BY c.date DESC, comment_id DESC";
        $result = $this->db->query($query,$user_id);
        return $result->result_array();
    }
   
    public function putComment ($data) //Добавление комментария
    {
        $this->db->insert('comments',$data);
    }  
}

 