<?php

namespace App\Entity;

use App\Service\BillGenerator;
use App\Service\BillMicroserviceClient;

const BILL_TYPE_Partner = 1;
const BILL_TYPE_OTHER = 2;

class Bill
{
    public $id;
    public $sum;
    public $items = [];
    public $billType;
    public $isPaid;
    public $billGenerator;
    public $billMicroserviceClient;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getPayUrl()
    {
        return "http://pay" . $this->id;
    }

    public function setBillGenerator($billGenerator)
    {
        $this->billGenerator = $billGenerator;
    }

    public function getBillUrl()
    {
        return $this->billGenerator->generate($this);
    }

    public function setBillClient(BillMicroserviceClient $cl)
    {
        $this->billMicroserviceClient = $cl;
    }

    public function isPaid()
    {
        if ($this->billType == BILL_TYPE_Partner) {
            return $this->isPaid;
        }
        if ($this->billType == BILL_TYPE_OTHER) {
            return $this->billMicroserviceClient->IsPaid($this->id);
        }
    }
}