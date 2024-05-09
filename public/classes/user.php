<?php

namespace User;

class User extends RequestAPI {
    private $user_id;
    private $role_id;
    //private $role_name;
    //private $permissions = [];
    private $password;
    private $f_name;
    private $l_name;
    private $phone_num;
    private $profile_pic;
    private $status;
    private $otp_code;
    private $cashback_pts;
    private $created_at;
    private $update_at;

    public function __construct($user_id,$role_id,$password,$f_name,$l_name,$phone_num,$status,$created_at,$updated_at){
       $this->user_id = $user_id;
       $this->role_id = $role_id;
       $this->password = $password;
       $this->f_name = $f_name;
       $this->l_name = $l_name;
       $this->phone_num = $phone_num;
       $this->status = $status;
       $this->cashback_pts = $cashback_pts;
       $this->created_at = $created_at;
       $this->updated_at = $updated_at;
    }

    public function getID() {
        $this->user_id = $user_id;

        return $this->user_id;
    }

    public function getFullName() {
        $this->f_name = $f_name;
        $this->l_name = $l_name;

        $full_name = $this->l_name + '' + $this->f_name;

        return $full_name;
    }

    public function getOperatorName() {

        $this->phone_num = $phone_num;
        
        $ope_name = "";
           for ($i = 0; $i < strlen($phone_num); $i++) {
               $ope_digit = $phone_num[5]; 
               if ($ope_digit == 0 || $ope_digit == 1 || $ope_digit == 2 || $ope_digit == 3) {
                  $ope_name = "TOGOCOM"; 
               } else if ($ope_digit == 9 || $ope_digit == 8 || $ope_digit == 7 || $ope_digit == 6) {
                  $ope_name = "MOOV AFRICA"; 
               } else {
                  $ope_name = "INCONNU"; 
               }
           }

           return $ope_name;
    }

    public function getPhoneNumber() {
        $this->phone_num = $phone_num;

           return $this->phone_num;
    }

    public function getStatus() {
        $this->status = $status;

        return strtoupper($this->user_id);
    }

    public function getCreatedAt() {
        $this->created_at = $created_at;

        return $this->created_at;
    }

    public function getLastUpdatedAt() {
        $this->updated_at = $updated_at;

        return $this->updated_at;
    }

    public function getCashbackPoints() {
        $this->cashback_pts = $cashback_pts;

        return $this->cashback_pts;
    }
}

?>