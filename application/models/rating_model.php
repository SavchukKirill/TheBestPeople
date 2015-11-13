<?php

class Rating_model extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function getRating($user_id) //Получение рейтинга пользователя по id
    {
        $userID = $this->session->userdata('userID');
        if($userID != $user_id) {
            $query = "SELECT rating FROM users WHERE user_id = ? ORDER BY rating DESC";
            $result = $this->db->query($query,$user_id);
            return $result->row_array();
        }
    }
    
    public function updateRating($user_id,$rating) //Изменение рейтинга пользователя по id
    {
        $userID = $this->session->userdata('userID');
        if($userID != $user_id) {
            $query = "UPDATE users SET rating = ? WHERE user_id = ?";
            $this->db->query($query,array($rating,$user_id));
        }
    }
    
    public function putEstimation($user_id, $data) //Добавление голоса
    {
        $userID = $this->session->userdata('userID');
        if($userID != $user_id) {
            $this->db->insert('estimations',$data);
        }
    }
    
    public function deleteEstimation($visitor_id,$userprofile_id) //Удаление старого голоса
    {
        if($visitor_id != $userprofile_id) {
           $query = "DELETE FROM estimations WHERE visitor_id = ? AND userprofile_id = ?";
          $this->db->query($query,array($visitor_id,$userprofile_id)); 
        } 
    }
}

