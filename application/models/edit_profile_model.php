<?php

class Edit_profile_model extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function editUser($data,$id) //Изменение данных пользователя (пол, аватар)
    {
        $this->db->where('user_id', $id);
        $this->db->update('users', $data);
    }
}