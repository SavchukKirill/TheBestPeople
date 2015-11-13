<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller 
{
    
    private $data = array();

    public function __construct() 
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('main_model');
        $this->load->model('rating_model');
        $this->data['title_page'] = 'Лучшие люди Интернета';
        $this->data['page'] = 1; //Для подсветки в навигации и в ifelse update_rating.js
        $this->data['id_exist'] = $this->session->has_userdata('userID');
        if($this->data['id_exist']) {
            $this->data['userID'] = $this->session->userdata('userID');
            $this->data['username'] = $this->session->userdata('username');
            $this->data['avatar'] = $this->session->userdata('avatar');
            $this->data['sex'] = $this->session->userdata('sex');      
        }
    }

    public function index() 
    {
        if(null!==$this->input->post('vote')) { //Меняем рейтинг и перегружаем топ AJAX          
            $vote = $this->input->post('vote'); //Голос пользователя(+/-)
            $click = $this->input->post('click'); //Голосовал ли наш пользователь за другого пользователя раньше 
            $user_id = $this->input->post('user_id'); //id пользователя, за которого проголосовали

            $rating_array = $this->rating_model->getRating($user_id);
            $rating=$rating_array['rating'];
            $data = array (
                'estimation' => $vote,
                'date' => date('Y-m-d'),
                'visitor_id' => $this->data['userID'],
                'userprofile_id' => $user_id,
            );
            //Если голосовали ранее, значит прибалвяем или убавляем 2 голоса
            if($click) {
                if($vote==1) {
                    $rating+=2;  
                } else {
                    $rating-=2;
                }
            } else {
                if($vote==1) {
                    $rating++;  
                } else {
                    $rating--;
                }
            }
            //Изменяем рейтинг
            $this->rating_model->updateRating($user_id,$rating);
            //Удаляем информацию о том, что наш пользователь голосовал за другого пользоватедя раньше
            $this->rating_model->deleteEstimation($data['visitor_id'], $data['userprofile_id']);  
             //Добавляем информацию о данном голосе
            $this->rating_model->putEstimation($data); 
            //Данные для топа
            $data['top'] = $this->main_model->getTopUser($this->data['userID']);  
            $data['id_exist'] = TRUE;
            $data['user_id'] = $this->data['userID'];
            $data['page'] = $this->data['page'];
            
            $this->load->view('main/top15_view', $data);
            
        } else { //Начальная загрузка страницы
            if($this->data['id_exist']) { //подгружаем рейтинг нашему пользователю
                $rating = $this->rating_model->getRating($this->data['userID']);
                $this->data['rating'] = $rating['rating'];  
            } 
            $this->load->view('templates/header_view', $this->data); 
               
        
            if($this->data['id_exist']) { //загружаем информацию о пользователях топа для авторизованного пользователя
                $data['top'] = $this->main_model->getTopUser($this->data['userID']); 
                $data['id_exist'] = TRUE;
                $data['user_id'] = $this->data['userID'];
                $data['page'] = $this->data['page'];
            } else { //загружаем информацию о пользователе для неавторизованных пользователей
                $data['top'] = $this->main_model->getTopGuest();
                $data['id_exist'] = FALSE;
            }

            $this->load->view('main/top15_view', $data);
            $this->load->view('templates/footer_view');
        }
    }
}