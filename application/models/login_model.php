<?php

class Login_model extends CI_Model {
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function getUser($login) //Получение информации о пользователе по логину
    {
        $this->db->where('username', $login);
        $result = $this->db->get('users');    
        if ($result->num_rows() == 0) {  
            return FALSE;
        }
        return $result->row_array();        
    }
    
    public function putCaptcha($data) //Добавление новой капчи в БД
    {
        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);
    }
    
    public function getCaptcha($binds) //Проверка ввел ли пользователь верный текст с картинки
    {
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';        
        $query = $this->db->query($sql, $binds);
        return $query->row_array();
    }
    
    public function deleteCaptcha($expiration) //Удаление старых капчей
    {
        $this->db->where('captcha_time < ', $expiration)->delete('captcha');
    }
}
