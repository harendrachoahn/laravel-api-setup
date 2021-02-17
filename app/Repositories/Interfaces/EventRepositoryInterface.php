<?php
namespace App\Repositories\Interfaces;
 
 interface EventRepositoryInterface {
    
      public function getAllUsers();
 
      public function getUserById($id);
 
      public function updateBuyer($data, $id);
   }