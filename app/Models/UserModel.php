<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    public function getUsers($data=array()){
        $db = \Config\Database::connect();
        $builder = $db->table('userdata');
        $builder->select(['user_id','username','name','surname','email','user_type','org_code']);
        if(isset($data['onlyRegistered']))$builder->where('user_type !=','user');
        if(isset($data['except'])&&count($data['except'])>0)$builder->whereNotIn('user_type',$data['except']);
        $users = $builder->get()->getResult();
        return $users;
    }
    
    public function getUser($user_id){
        $db = \Config\Database::connect();
        $builder = $db->table('userdata');
        $builder->where('user_id', $user_id);
        $result=$builder->get()->getResult();
        return $result[0];
    }
    public function getUnregisterUsers(){
        $db = \Config\Database::connect();
        $builder = $db->table('userdata');
        $builder->select(['user_id','username','name','surname','email','user_type']);
        $builder->where('user_type','user');
        $users = $builder->get()->getResult();
        return $users;
    }
    public function checkUser($data){
        $db = \Config\Database::connect();
        $user = $db->table('userdata')->getWhere($data)->getResult();
        return $user;
    }
    public function checkEmail($email){
        $db = \Config\Database::connect();
        $user = $db->table('userdata')->getWhere(['email'=>$email])->getResult();
        return $user;
    }
    public function addUser($data){
        $db = \Config\Database::connect();
        $builder = $db->table('userdata');
        $result=$builder->insert($data);
        return $result;
    }
    public function updateUser($email,$data){
        $db = \Config\Database::connect();
        $builder = $db->table('userdata');
        $builder->where('email', $email);
        $result=$builder->update($data);
        return $result;
    }
    public function register($data){
        //print_r($data);
        $db = \Config\Database::connect();
        $builder = $db->table('user_register');
        $result=$builder->replace($data);
        return $result;
    }
    public function getRegister($user_id=false){
        $db = \Config\Database::connect();
        $builder = $db->table('user_register');
        if($user_id)$builder->where('user_id', $user_id);
        $result=$builder->get()->getResult();
        //print_r($result);
        return $result;
    }
}