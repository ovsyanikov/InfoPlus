<?php

namespace controller;

use model\entity\user;
use model\service\UserService;

class UserController extends BaseController{
    
      private $userService;
      
      function leaveAction(){
          
          if(empty($this->userService)){
              
              $this->userService = new UserService();
              $this->userService->leaveResource();
              
          }//if
          
          header('Location: ?ctrl=start&act=welcome');
          
      }//leaveAction
      
      function authorizeAction() {
        
        if(empty($this->userService)){
              
              $this->userService = new UserService();
              
          }//if
          $r = new \util\Request();
              
          $login = $r->getPostValue("userLE");
          $password = $r->getPostValue("userPS");
          $remember = $r->getPostValue("remember_me");
          
          $cuser = $this->userService->authorize($login, $password);
              
          if($cuser != NULL){
                  
                  $this->view->currentUser = $cuser;
                  
                  if($remember == 'on'){
                      
                      $r->setCookies($cuser->getLogin());
                      
                  }//if
                  else{
                      
                      $r->setSessionValue('user_info_plus', $cuser->getLogin());
                      
                  }//else
                  
                  header("Location: ?ctrl=news&act=news");
                  
          }//if
          else{
              
              return 'user_not_found';
              
          }
    }
    
      function registerAction(){
                
               return 'register';
        
      }
      
      function newuserAction(){
          
          if(empty($this->userService)){
              
              $this->userService = new UserService();
              
          }//if
          
          $r = new \util\Request();
              
//          $login = $r->getPostValue("new_user_login");
//          $password = $r->getPostValue("new_user_password");
//          $cpassword = $r->getPostValue("new_user_confirm_password");
//          $email = $r->getPostValue("new_user_email");
//          $firstName = $r->getPostValue("new_user_first_name");
//          $lastName = $r->getPostValue("new_user_last_name");
          
          $user = new user();
          $user->setEmail('newEM');
          $user->setFirstName('newFN');
          $user->setLastName('newFN');
          $user->getLogin('newLogin');
          $user->getPassword('newPS');
          
          $newID = $this->userService->add($user);
          
          
          header("Location: index.php?ctrl=news&act=news&newId=$newID");
          
      }//newuserAction
      
}//UserController