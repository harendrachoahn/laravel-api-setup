<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BuyerRepositoryInterface;
use App\Models\Buyer;

/**
 * Class BuyerRepository.
 */
class BuyerRepository  implements BuyerRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    protected $buyerModel ='';
    
    public function __construct(Buyer $buyerModel)
    {
        $this->buyerModel = $buyerModel;
    }

 
    public function getAllUsers()
    {
        return $this->buyerModel->all();
    }
 
    public function getUserById($id)
    {
        return $this->buyerModel->find($id);
    }

    public function updateBuyer($data, $id)
    {
        return $this->buyerModel->find($id)->update($data);
    }
}
