<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller
{
    private $data = array();
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('register_model');
        $this->load->model('login_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));
        $this->data['id_exist'] = $this->session->has_userdata('userID');
        if($this->data['id_exist']) {
            redirect('main');
        }
        $this->data['title_page'] = 'Регистрация';
        $this->data['page'] = 3; //Для подсветки в навигации
        $this->load->view('templates/header_view', $this->data);   
    }
    
    public function index() 
    {
        $this->data['error']='';
        //Задаем правила для валидации
        $this->form_validation->set_rules('username', 'Логин', 'trim|required|min_length[4]|max_length[15]|callback_checkCorrectnessUsername|callback_checkUniqueUsername');
        $this->form_validation->set_rules('password', 'Пароль', 'trim|min_length[5]|max_length[25]|required|callback_checkCorrectnessPassword|sha1');
        //Задаем текст ошибок при выводе
        $this->form_validation->set_message('required', 'Поле {field} должно быть заполнено');
        $this->form_validation->set_message('min_length', 'Длина поля {field} не должна быть меньше {param} символов');
        $this->form_validation->set_message('max_length', 'Длина поля {field} не должна быть больше {param} символов');
        //Задаем параметры для загружаемого файла
        $config['upload_path'] = './uploads/'; //папка загрузки
	$config['allowed_types'] = 'gif|jpg|png|jpeg';
	$config['max_size']	= '5120';
        $config['remove_spaces'] = TRUE; //все пробелы в имени файла будут преобразованы в знак подчеркивания
        $config['overwrite'] = FALSE;    //если при загрузке изображения, уже есть изображение с таким же именем, 
                                          //оно не перезаписывается,
                                         //а добавляет к имени заливаемого изображения порядковый номер
		
	$this->load->library('upload', $config); 
            
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('register/register_view', $this->data);
        } else {
            $dataUser = array (
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password'),
                    'sex' => $this->input->post('sex')
                );
               
            if ($_FILES['userfile']['size']!=0) { 
                if ( !$this->upload->do_upload()){ //Загружаем изображение, и если возникли ошибки, 
                    $error = array('error' => $this->upload->display_errors()); // выводим их    
                    $this->load->view('register/register_view', $error);
                    return;
                } else {
                    $dataImg = $this->upload->data(); //информация о файле
                    $dataUser['avatar'] = '.' . $config['upload_path'] . $dataImg['file_name'];
                    $imgSize = 50; 
                    $iw = $dataImg['image_width'];
                    $ih = $dataImg['image_height'];
                    if( $iw > $imgSize ||  $ih > $imgSize ) { //Если высота или ширина изображения больше 50, то уменьшаем его
                        if($iw >= $ih) {                      //Задаем новые размеры изображения
                            $width = $imgSize;
                            $height = round($imgSize*$ih/$iw);
                        } else {
                            $height = $imgSize;
                            $width = round($imgSize*$iw/$ih);
                        }
                        $config['image_library'] = 'gd2'; // выбираем библиотеку
                        $config['source_image']	= $config['upload_path'] . $dataImg['file_name']; 
                        $config['maintain_ratio'] = TRUE; //сохраняем пропорции
                        $config['width']	= $width;
                        $config['height']	= $height;

                        $this->load->library('image_lib', $config); // загружаем библиотеку
                        $this->image_lib->resize();                 // уменьшение изображения с заданными параметрами
                    }
                }
            }
            
            $this->register_model->createUser($dataUser);
            $row = $this->login_model->getUser($dataUser['username']);
            //Задаем сессию на 24 часа(application/config/config.php 
            //$config['sess_expiration'] = 86400;
            $this->session->set_userdata('userID', $row['user_id']);
            $this->session->set_userdata('username', $row['username']); 
            $this->session->set_userdata('avatar', $row['avatar']); 
            $this->session->set_userdata('sex', $row['sex']); 
            redirect('user_profile?id='.$row["user_id"]);        
        }   
    }
    
    public function checkUniqueUsername($str) 
    {
        $username = $this->register_model->getUsername($str);
        if($username) {
            $this->form_validation->set_message('checkUniqueUsername', 'Такой {field} уже существует');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    public function checkCorrectnessUsername($str) 
    {
        $regex = '/^[a-zA-Z][a-zA-Z0-9]{3,14}$/';
        if(preg_match($regex, $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('checkCorrectnessUsername', 
                    'Поле {field} должно содержать хотя бы один символ. В имени первым должен быть символ.');
            return FALSE;
        }
    }
    
    public function checkCorrectnessPassword($str) 
    {
        $regex = "/[0123456789]/";
        if(preg_match($regex, $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('checkCorrectnessPassword', 
                    'Поле {field} должно содержать хотя бы одну цифру.');
            return FALSE;
        }
    }   
}
