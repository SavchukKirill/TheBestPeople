<?php

class Register_model extends CI_Model
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function getUsername($username) //Поиск пользователей с заданным логином
    {
        $this->db->where('username',$username);
        $this->db->select('username');
        $result = $this->db->get('users');
        return $result->row_array();
    }
    
    public function createUser($dataUser)  //Добавление нового пользователя
    {
        $this->db->insert('users', $dataUser);
    }
}

