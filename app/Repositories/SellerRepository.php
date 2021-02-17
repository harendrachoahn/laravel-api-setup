<?php 
namespace App\Repositories;

use App\Repositories\Interfaces\SellerRepositoryInterface;
use App\Models\Seller;

 
class SellerRepository implements SellerRepositoryInterface {

    protected $sellerModel ='';
    
    public function __construct(Seller $sellerModel)
    {
        $this->sellerModel = $sellerModel;
    }

 
    public function getAllUsers()
    {
        return $this->sellerModel->all();
    }
 
    public function getUserById($id)
    {
        return $this->sellerModel->find($id);
    }

    public function updateSeller($data, $id)
    {
        return $this->sellerModel->find($id)->update($data);
    }

 
}