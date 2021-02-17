<?php
namespace App\Repositories\Interfaces;
 
 interface SellerRepositoryInterface {
    
      public function getAllUsers();
 
      public function getUserById($id);
 
      public function updateSeller($data, $id);
   }

?>