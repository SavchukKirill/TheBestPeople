<?php

class Main_model extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function getTopUser($user_id) //Топ 15 пользователей с наибольшим рейтингом
    {                                    //И информация о том, как авторизованный пользователь голосовал за них
        $query = "SELECT u.user_id, u.username, u.avatar, u.sex, u.rating, e.estimation FROM " .
            "(SELECT * FROM users ORDER BY rating DESC LIMIT 15) u ".
            "LEFT OUTER JOIN " .
            "(SELECT estimation, visitor_id, userprofile_id FROM estimations WHERE visitor_id = ?) e " .
            "ON(e.userprofile_id=u.user_id)" .
            "ORDER BY rating DESC";
        $result = $this->db->query($query,$user_id);
        return $result->result_array();
    }
    
    public function getTopGuest() //Топ 15 пользователей с наибольшим рейтингом
    {                             //для неавторизованных пользователей
        $query = "SELECT user_id, username, avatar, sex, rating FROM users ORDER BY rating DESC LIMIT 15";
        $result = $this->db->query($query);
        return $result->result_array();
    }
}

