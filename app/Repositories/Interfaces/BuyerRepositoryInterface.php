<?php
namespace App\Repositories\Interfaces;
 
 interface BuyerRepositoryInterface {
    
      public function getAllUsers();
 
      public function getUserById($id);
 
      public function updateBuyer($data, $id);
   }

?>