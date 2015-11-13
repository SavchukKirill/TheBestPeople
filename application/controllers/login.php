<?php

class Login extends CI_Controller {

    private $data = array();
            
    function __construct() 
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('image_lib');
        $this->load->helper('captcha');
        $this->load->model('login_model');
        $this->data['id_exist'] = $this->session->has_userdata('userID');
        if($this->data['id_exist']) {
            redirect('main');
        }
        $this->data['title_page'] = 'Вход в приложение';
        $this->data['page'] = 2; //Для подсветки в навигации
        $this->load->view('templates/header_view', $this->data);
    }

    
    public function index() 
    {
        $this->form_validation->set_rules('username', 'Логин', 'trim|required');
        $this->form_validation->set_rules('password', 'Пароль', 'trim|required|sha1|callback_checkPassword');        
       
        $this->form_validation->set_message('required', 'Поле {field} должно быть заполнено');
        //Параметры для капчи
        $vals = array(
            'img_path'      => './captcha/',
            'img_url'       => 'http://example.com/captcha/',
            'font_path'     => './path/to/fonts/texb.ttf',
            'img_width'     => '150',
            'img_height'    => 30,
            'expiration'    => 7200,
            'word_length'   => 5,
            'font_size'     => 16,
            'img_id'        => 'Imageid',
            'pool'          => '123456789ABCDEFGHIJKLMNPRSTUVWXYZ', //Оставим хорошо различимые символы
            'colors'        => array(
            'background' => array(255, 255, 255),
            'border' => array(255, 255, 255),
            'text' => array(0, 0, 0),
            'grid' => array(255, 40, 40)
            )
        );
        //Создание капчи
        $cap = create_captcha($vals);       
        
        $data = array(
            'captcha_time'  => $cap['time'],
            'ip_address'    => $this->input->ip_address(),
            'word'          => $cap['word']
        );
        $this->login_model->putCaptcha($data); //Поместили капчу в БД
        //Правила валидации для капчи
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_checkCaptcha');     
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login/login_view',$cap);
        } else { //Авторизуме пользователя и направим его на главную
            $this->session->set_userdata('userID', $this->data['row']['user_id']);
            $this->session->set_userdata('username', $this->data['row']['username']); 
            $this->session->set_userdata('avatar', $this->data['row']['avatar']); 
            $this->session->set_userdata('sex', $this->data['row']['sex']); 
            redirect('main');          
        }
        
        $this->load->view('templates/footer_view');    
    }
    
    public function checkCaptcha ($captcha) 
    {
        $expiration = time() - 7200;
        $binds = array($captcha, $this->input->ip_address(), $expiration);
            
        $this->login_model->deleteCaptcha($expiration); //удаляем все капчи которые были созданы больше 2 часов назад
        $captcha_count = $this->login_model->getCaptcha($binds);
        
            
        if ($captcha_count['count'] <=0) {
            $this->form_validation->set_message('checkCaptcha', 'Текст с картинки введен не верно.');
            return FALSE;
        } else {
            return TRUE;
        }    
    }

    public function checkPassword($password) 
    {
        $row = $this->login_model->getUser($this->input->post('username'));
        if ($row === FALSE || $row['password']!== $password) {
            $this->form_validation->set_message('checkPassword', 'Логин и/или пароль введены не верно.');
            return FALSE;
        } else {
            $this->data['row'] = $row;
            return TRUE;
        }
    }
}