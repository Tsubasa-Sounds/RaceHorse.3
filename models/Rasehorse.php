<?php
    require_once 'daos/RacehorseDAO.php';
    //レース投稿　一件分の投稿のデータを格納するクラス　DTO
    class Racehorse {
        public $id;
        public $user_id;
        public $image;
        public $racehorse_name;
        public $jockey_name;
        public $racecourse;
        public $race_name;
        public $content;
        
        public function __construct($user_id="", $image="", $racehorse_name="", $jockey_name="", $racecourse="", $race_name="", $content=""){
            $this->user_id = $user_id;
            $this->image = $image;
            $this->racehorse_name = $racehorse_name;
            $this->jockey_name = $jockey_name;
            $this->racecourse = $racecourse;
            $this->race_name = $race_name;
            $this->content = $content;
        }
        
        public function get_user(){
            $racehorse_dao = new RacehorseDAO();
            $user = $racehorse_dao->get_user($this->id);
            return $user;
        }
    }