<?php

namespace model\service;

use model\entity\user;

class UserService{
    
    function add(user $user) {
       
        $stmt = \util\MySQL::$db->prepare("INSERT INTO users (id,Login,Password,Email,FirstName,LastName) VALUES(NULL,:login,:pass,:email,:fn,:ln)");
        
        $login = $user->getLogin();
        $pass = $user->getPassword();
        $email = $user->getEmail();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        
        
        $stmt->bindParam(":login",$login);
        $stmt->bindParam(":pass",$pass);
        $stmt->bindParam(":email",$email);
        $stmt->bindParam(":fn",$firstName);
        $stmt->bindParam(":ln",$lastName);
        
        $stmt->execute();
        
        $r = new \util\Request();
        $r->setSessionValue('user_info_plus', $user->getLogin());
        
    }//add
            
    function authorize($login, $password) {

        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE ( (Login = :login or Email =:login) and Password = :pass)");
        $stmt->bindParam(":login",$login);
        $stmt->bindParam(":pass",$password);
        
        $stmt->execute();
        $user = $stmt->fetchObject(user::class);
        
        if(is_a($user, 'model\entity\user')){
            
            return $user;
            
        }
        else{
            
            return NULL;
            
        }
        
    }
    
    function leaveResource(){
        
        $r = new \util\Request();
        $r->unsetCookie('user_info_plus');
        $r->unsetSeesionValue('user_info_plus');
        
    }
    
    function getUser($login){
        
        $stmt = \util\MySQL::$db->prepare("SELECT * FROM users WHERE Login = :login");
        $stmt->bindParam(":login",$login);
        
        $stmt->execute();
        $user = $stmt->fetchObject(user::class);
        
        if(is_a($user, 'model\entity\user')){
           return $user;
        }//if
        else {
            
            return NULL;
            
        }
    }
    
}