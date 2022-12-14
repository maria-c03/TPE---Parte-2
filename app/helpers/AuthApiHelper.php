<?php


class AuthApiHelper{
    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function isLoggedIn(){
        $payload = $this->getToken();
        if(isset($payload->id)){
            return true;
        }else{
            return false;
        }
    }
    
    function getToken(){
        $key = "WebKeyTPE";
        $authentication = $this->getAuthHeader();
        $authentication = explode(" ", $authentication);
        
        if($authentication[0] != "Bearer" || count($authentication) != 2){
            return array();
        }
        $token = explode(".", $authentication[1]);
        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];
        $new_signature = hash_hmac('SHA256', "$header.$payload", $key, true);
        $new_signature = $this->base64url_encode($new_signature);

        if($signature!=$new_signature){
            return array();   
        }
        $payload = json_decode(base64_decode($payload));

        if(!isset($payload->exp) || $payload->exp<time()){
            return array();
        }
        return $payload;
    }

    function getAuthHeader(){
        $header = null;
        if(isset($_SERVER['HTTP_AUTHORIZATION'])){
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        }
        if(isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])){
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        return $header;
    }
}