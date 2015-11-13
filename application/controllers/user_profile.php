<?php

class User_profile extends CI_Controller 
{
    private $data = array();
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_profile_model');
        $this->load->model('rating_model');
        $this->data['page'] = 0; //Для подсветки в навигации
        $this->data['title_page'] = 'Профиль';
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
        if(null!==$this->input->post('comment')) { //Ajax комментарий
            $user_id = $this->input->post('user_id');
            $data = array (
                'contents' => $this->input->post('comment'),
                'date' => date('Y-m-d'),
                'visitor_id' => $this->data['userID'],
                'userprofile_id' => $user_id
            );    
                
            $this->user_profile_model->putComment($data); //Добавляем в базу комментарий(текст, дату, 
                                                          //id пользователя, id профиля)
            $dataComments['user_id'] = $user_id; 
            $dataComments['comments'] = $this->user_profile_model->getComments($user_id);
            
            $len = count($dataComments['comments']); //Подготавливаем комментарий для вывода, преобразуем дату, 
            for ($i=0; $i<$len; $i++) {              //и обрабатываем комментарий
                $dataComments['comments'][$i]['date'] = $this->russianDate($dataComments['comments'][$i]['date']); 
                $dataComments['comments'][$i]['contents'] = htmlspecialchars($dataComments['comments'][$i]['contents']);
            }
            $dataComments['id_exist']=  $this->data['id_exist'];
            $this->load->view('user_profile/comments_view',$dataComments);
        } elseif(null!==$this->input->post('vote')) { //Меняем рейтинг Ajax
            $vote = $this->input->post('vote');       //Голос пользователя(+/-)
            $click = $this->input->post('click');     //Голосовал ли наш пользователь за другого пользователя раньше 
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
            $this->rating_model->updateRating($user_id, $rating);
            //Удаляем информацию о том, что наш пользователь голосовал за другого пользоватедя раньше
            $this->rating_model->deleteEstimation($data['visitor_id'], $data['userprofile_id']);  
            //Добавляем информацию о данном голосе
            $this->rating_model->putEstimation($user_id, $data);   
            //Информация о пользователе dataUser и том, кто за него голосовал dataVotes
            $dataUser = $this->user_profile_model->getUser($user_id,  $this->data['userID']);
            $dataUser['visitor_id']=  $this->data['userID'];
            $dataUser['id_exist']=  $this->data['id_exist'];
            $dataUser['page']=  $this->data['page'];
            $dataUser['rating'] = $rating;
            
            $dataVotes['votes'] = $this->user_profile_model->getVotes($user_id);
            $len = count($dataVotes['votes']);
            for ($i=0; $i<$len; $i++) { //Подготавливаем историю голосов для вывода, преобразуем дату
                $dataVotes['votes'][$i]['date'] = $this->russianDate($dataVotes['votes'][$i]['date']); 
            }
            
            $this->load->view('user_profile/user_profile_view',$dataUser);
            $this->load->view('user_profile/votes_view',$dataVotes);  
        } else { //Начальная загрузка страницы
            $user_id = $this->input->get('id');
            
            if($this->data['id_exist']) {  //подгружаем рейтинг нашему пользователю
                $rating = $this->rating_model->getRating($this->data['userID']);
                $this->data['rating'] = $rating['rating'];  
                if($user_id==$this->data['userID']) {
                    $this->data['page'] = 4;  //если пользователь вошел в свой профиль, это будет обозначено в навигации
                }  // загружаем информацию о пользователе, и голосовал ли наш авторизованный пользователь за него
                $dataUser = $this->user_profile_model->getUser($user_id,  $this->data['userID']);
                $dataUser['visitor_id']=  $this->data['userID'];
                $dataUser['id_exist']=  $this->data['id_exist'];
                $dataUser['page']=  $this->data['page'];
            } else { //загружаем информацию о пользователе для неавторизованных пользователей
                $dataUser = $this->user_profile_model->getUserGuest($user_id);
            }
            //История голосов
            $dataVotes['votes'] = $this->user_profile_model->getVotes($user_id);
            $len = count($dataVotes['votes']);
            for ($i=0; $i<$len; $i++) {
                
//Если использовать setlocale (LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
//Возвращает false и выводит английские названия
//Если использовать setlocale (LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
//Возвращает  "Russian_Russia.1251"  и выводит черные ромбы с вопросами 
                
//setlocale (LC_ALL, 'ru_RU', 'rus_RUS', 'ru_RU');
//$tmstp = strtotime($dataVotes['votes'][$i]['date']);
//strftime('%d %B %Y', $tmstp);
                
                $dataVotes['votes'][$i]['date'] = $this->russianDate($dataVotes['votes'][$i]['date']); 
            }
            //Комментарии
            $dataComments['comments'] = $this->user_profile_model->getComments($user_id);
           
            $len = count($dataComments['comments']);
            for ($i=0; $i<$len; $i++) {
                $dataComments['comments'][$i]['date'] = $this->russianDate($dataComments['comments'][$i]['date']);
                $dataComments['comments'][$i]['contents'] = htmlspecialchars($dataComments['comments'][$i]['contents']);
            }
            
            $dataComments['id_exist']=  $this->data['id_exist'];
            
            $this->load->view('templates/header_view',  $this->data);
            $this->load->view('user_profile/user_profile_view',$dataUser);
            $this->load->view('user_profile/votes_view',$dataVotes);
            $this->load->view('user_profile/comments_view',$dataComments);
            $this->load->view('templates/footer_view');
        }
    }
    
    public function russianDate($dateVote) //Преобразует дату из БД Y-m-d 
    {                                      //в удобную для чтения
        $date=explode("-", $dateVote);
        switch ($date[1]){
            case 1: 
                $m='января'; 
                break;
            case 2: 
                $m='февраля'; 
                break;
            case 3: 
                $m='марта'; 
                break;
            case 4: 
                $m='апреля'; 
                break;
            case 5: 
                $m='мая'; 
                break;
            case 6: 
                $m='июня'; 
                break;
            case 7: 
                $m='июля'; 
                break;
            case 8: 
                $m='августа'; 
                break;
            case 9: 
                $m='сентября'; 
                break;
            case 10: 
                $m='октября'; 
                break;
            case 11: 
                $m='ноября'; 
                break;
            case 12: 
                $m='декабря'; 
                break;
        }
        return $date[2]. ' ' . $m . ' ' . $date[0];
    }
}