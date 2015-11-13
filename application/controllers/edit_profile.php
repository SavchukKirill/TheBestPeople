<?php

class Edit_profile extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('form_validation','session'));
        $this->load->helper(array('form', 'url'));
        $this->load->model('edit_profile_model');
        $this->load->model('rating_model');
        $this->data['title_page'] = 'Редактирование профиля';
        $this->data['page'] = 0; //Для подсветки в навигации
        if($this->input->get('id')){
            $this->data['profile_id'] = $this->input->get('id');
        }
        $this->data['id_exist'] = $this->session->has_userdata('userID');
        if($this->data['id_exist']) {
            $this->data['userID'] = $this->session->userdata('userID');
            $this->data['username'] = $this->session->userdata('username');
            $this->data['avatar'] = $this->session->userdata('avatar');
            $this->data['sex'] = $this->session->userdata('sex');      
        }
        if(!($this->data['id_exist'] && $this->data['userID']==$this->data['profile_id'])) {
            redirect('main');
        }
        $rating = $this->rating_model->getRating($this->data['userID']);
        $this->data['rating'] = $rating['rating'];
        $this->load->view('templates/header_view', $this->data);
    }
    
    public function index() 
    {
        $this->data['error']='';
        $this->form_validation->set_rules('sex', 'Пол', 'required');
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
            $this->load->view('edit_profile/edit_profile_view', $this->data);
        } else {
            $dataUser = array (
                    'sex' => $this->input->post('sex')
            );
            if ($_FILES['userfile']['size']!=0) {
                if ( !$this->upload->do_upload()){ //Загружаем изображение, и если возникли ошибки, 
                    $error = array('error' => $this->upload->display_errors()); // выводим их
                    $this->load->view('edit_profile/edit_profile_view', $error);
                    return;
                } else {   
                    $dataImg = $this->upload->data();
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
            //Изменяем данные пользователя
            $this->edit_profile_model->editUser($dataUser,$this->data['userID']);
            //Меняем данные сессии
            $this->data['sex'] = $this->session->userdata('sex');
            if($dataUser['avatar']) {
                $this->session->set_userdata('avatar', $dataUser['avatar']);
            }
            redirect('user_profile?id='.$this->data['userID']);
        } 
    }   
}