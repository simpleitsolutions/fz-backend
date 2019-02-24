<?php 
namespace App\Helper\BookingPaymentHelper;

class PassengerPaymentStatus
{
    private $id;
    private $subPaymentTotal;
    private $owingBalance;

    public function __construct($passenger)
    {
        $this->id = $passenger->getId();
        $this->subPaymentTotal = 0.0;
        $this->owingBalance = $passenger->calculateOwing();
    }

    public function getSubPaymentTotal()
    {
        return $this->subPaymentTotal;
    }

    public function getOwingBalance()
    {
        return $this->owingBalance;
    }
 
    public function adjustStatus($calculatedPayment)
    {
        if($this->owingBalance <> 0.0)
        {
        $this->subPaymentTotal = $this->subPaymentTotal + $calculatedPayment;
        $this->owingBalance = $this->owingBalance - $calculatedPayment;
        }   
    }
    
}
