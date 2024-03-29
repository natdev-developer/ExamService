<?php

namespace App\Repository\Admin;

use App\Entity\Admin\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

require_once 'C:\xampp\htdocs\examServiceProject\vendor\autoload.php';
class UserRepository {
    protected $reference;
    private $auth;

    /**
     * UserRepository constructor.
     */
    public function __construct() {
        $database = new DatabaseConnection();
        $this->reference = $database->getReference('User');

        $this->auth = $database->getAuthentication();
    }

    public function registerUser(int $uid,String $email,String $password) {
        $userProperties = [
            'uid' => $uid,
            'email' => $email,
            'emailVerified' => false,
            'password' => $password,
            'displayName' => $email
        ];
        $this->auth->createUser($userProperties);
    }

    public function getUsersFromAuthentication() {
        $users = $this->auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);
        return $users;
    }

    public function getUserIdFromAuthentication(String $email) {
        $user = $this->auth->getUserByEmail($email);
        $data= $user->toArray();
        return $data['uid'];
    }

    public function deleteUserFromAuthenticationByEmail(string $email) {
        $user = $this->auth->getUserByEmail($email);
        $id = $user->uid;
        print_r("ID: ".$user->uid);
        $this->auth->deleteUser(strval($id));
        return true;
    }

    public function deleteUserFromAuthentication(int $id){
        $this->auth->deleteUser(strval($id));
        return true;
    }

    public function editUserPasswordFromAuthentication(int $id,String $password){
        $updatedUser = $this->auth->changeUserPassword(strval($id), $password);
    }

    public function editUserEmailFromAuthentication(int $id,String $email){
        $updatedUser = $this->auth->changeUserEmail(strval($id), $email);
    }

    public function sendResetLinkToEmail(String $email){

        $this->auth->sendPasswordResetEmail($email, 'login');
    }

    public function checkPassword(String $email,String $password){
        return $this->auth->verifyPassword($email, $password);
    }

   public function getUser(int $userId) {
        if ($this->reference->getSnapshot()->hasChild($userId)) {
            return $this->reference->getChild($userId)->getValue();
        } else {
            return 0;
       }
   }
    public function getUserByEmail(string $email) {
        $idUsers = $this->getIdUsers();
        if($idUsers==0){
            $amount =0;
        } else {
            $amount = count($idUsers);
        }

        for($i=0;$i<$amount;$i++) {
            $userInfo = $this->reference->getChild($idUsers[$i])->getValue();

            if($userInfo['email'] == $email) {
                return $userInfo;
            }
        }
        return 0;
    }
   public function insert(int $id, array $data) {
       if(empty($data)) {
           return false;
       }

       $this->reference->getChild($id)->set([
           'id' => $id,
           'first_name' => $data[1],
           'last_name' => $data[2],
           'email' => $data[3],
           'role' => $data[4],
           'last_login' => $data[5],
           'last_password_change' => $data[6],
           'date_registration' => $data[7],
           'group_of_students' => $data[8]
       ]);
       return true;
   }

    public function update(array $data, int $id) {
        if (empty($data) ) {
            return false;
        }
        $email = $data[3];
        $idFromAuthentication = $this->getUserIdFromAuthentication($email);
        $user = $this->auth->getUser($idFromAuthentication);
        $this->auth->changeUserPassword($user->uid, $data[0]);
        $this->reference
            ->getChild($id)->update([
                'first_name' => $data[1],
                'last_name' => $data[2],
                'group_of_students' => $data[8],
                'last_password_change' => $data[6]
            ]);
        return true;
    }

    public function updateLastLogin($data,$id){
        if (empty($data) ) {
            return false;
        }
        $this->reference
            ->getChild($id)->update([
                'last_login' => $data[5]
            ]);
        return true;
    }

    public function delete(int $userId) {
        if ($this->reference->getSnapshot()->hasChild($userId)) {
            $this->reference->getChild($userId)->remove();
            return true;
        } else {
            return false;
        }
    }

    public function existAdmin(){
        $usersId = $this->getIdUsers();
        if($usersId==0){
            return false;
        } else {
            $usersAmount = count($usersId);
        }
        for($i=0;$i<$usersAmount;$i++){
            $userInformation = $this->getUser($usersId[$i]);
            if($userInformation['email']=="administrator@admin.pl"){
                return true;
            }
        }
        return false;
    }

    public function getQuantity() { return $this->reference->getSnapshot()->numChildren(); }

    public function getIdUsers() {
        if($this->reference->getSnapshot()->hasChildren()==NULL){
            return 0;
        } else {
            return $this->reference->getChildKeys();
        }
    }

    public function getIdNextUser(){
        $usersId = $this->getIdUsers();
        if($usersId!=0){
            $usersAmount = count($usersId);
        } else {
            $usersAmount=0;
        }
        switch ($usersAmount) {
            case 0:{
                $maxNumber = 0;
                break;
            }
            case 1:{
                $maxNumber=$usersId[0]+1;
                break;
            }
            default:{
                $maxNumber=$usersId[0];
                for($i=1;$i<$usersAmount;$i++){
                    if($maxNumber<=$usersId[$i]){
                        $maxNumber =$usersId[$i];
                    }
                }
                $maxNumber=$maxNumber+1;
            }
        }
        return $maxNumber;
    }

    public function find(int $userId){
        $information = $this->reference->getChild($userId)->getValue();

        $user = new User([]);
        $user->setId($information['id']);
        $user->setFirstName($information['first_name']);
        $user->setLastName($information['last_name']);
        $user->setEmail($information['email']);
        $user->setRoles($information['role']);
        $user->setGroupOfStudents($information['group_of_students']);

        return $user;
    }
}
