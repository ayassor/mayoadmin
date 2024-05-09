<?php

namespace RequestAPI;

class RequestAPI {
    protected $url;
    protected $token;
    protected $id;

    public function __construct($url){
        $this->url = $url;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function signIn($phone, $password) {
        $url_auth = "http://104.196.146.173:9000/api/v1/auth/signin";
        $this->url = $url_auth;

        $donnees_auth = [
            "password" => $password,
            "phone" => $phone
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($donnees_auth)
            ]
        ];

        $context = stream_context_create($options);
        $resultat = file_get_contents($this->url, false, $context);
        $token = json_decode($resultat, true)['token'];
        $id = json_decode($resultat, true)['data']['id'];
        $this->setToken($token);
        $this->setID($id);
    }

    public function get($url) {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer $this->token"
            ]
        ];

        $context = stream_context_create($options);
        $resultat = file_get_contents($this->url, false, $context);
        return $resultat;
    }

    public function getByID($url,$id) {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer $this->token"
            ]
        ];

        $context = stream_context_create($options);
        $resultat = file_get_contents($this->url . $id, false, $context);
        return $resultat;
    }
}

?>