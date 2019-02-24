<?php
namespace App\Helper;


use App\Helper\BookingPaymentHelper\PassengerPaymentStatus;

class BookingPaymentHelper
{
    private $paymentAmount;
    private $passengerPaymentStatus;

    public function __construct($paymentAmount, $passengers)
    {
        $this->paymentAmount = $paymentAmount;
        $this->passengerPaymentStatus = array();
        foreach ($passengers as $passenger)
        {
            $this->passengerPaymentStatus[$passenger->getId()] = new PassengerPaymentStatus($passenger);
        }
    }

    public function getPassengerPaymentStatus()
    {
        return $this->passengerPaymentStatus;
    }

    public function hasEvenPaymentDistribution()
    {
        $owingArray = array();
        foreach ($this->passengerPaymentStatus as $passengerPaymentStatus)
        {
            if($passengerPaymentStatus->getOwingBalance() <> 0)
            {
                $owingArray[] = $passengerPaymentStatus->getOwingBalance();
            }
        }
        if(sizeof(array_unique($owingArray)) == 1)
        {
            return true;
        }
        return false;
    }
    

    private function getNoOfPassengersWithNonZeroOwing()
    {
        $noPassengersNonZero = 0;
        foreach ($this->passengerPaymentStatus as $passengerPaymentStatus)
        {
            if($passengerPaymentStatus->getOwingBalance() <> 0)
            {
                $noPassengersNonZero = $noPassengersNonZero + 1;
            }
        }
        return $noPassengersNonZero;
    }

    public function getPassengerPaymentAmount()
    {
        $noPassengersNonZero = $this->getNoOfPassengersWithNonZeroOwing();
        if($noPassengersNonZero == 0)
        {
            return 0.0;
        }
        
        return $this->paymentAmount / $noPassengersNonZero;
    }

    public function getOwingTotal()
    {
        $totalOwing = 0.0;
        foreach ($this->passengerPaymentStatus as $passengerPaymentStatus)
        {
            $totalOwing = $totalOwing + $passengerPaymentStatus->getOwingBalance();
        }
       
        return $totalOwing;
    }

    private function getCurrentMinimumOwingPayment()
    {
        $minOwingPayment = 0.0;
        $owingArray = array();
        foreach ($this->passengerPaymentStatus as $passengerPaymentStatus)
        {
            $owningBalance = $passengerPaymentStatus->getOwingBalance();
            if($owningBalance <> 0)
            {
                $owingArray[] = $owningBalance;
            }
        }
        if(sizeof($owingArray) > 0)
        {
            $minOwingPayment = min($owingArray);
        }

        return $minOwingPayment;
    }
    
    public function process($paymentAmountBalance)
    {
        $calculatedPayment = 0.0;
        $minOwingPayment = $this->getCurrentMinimumOwingPayment();
        $noPassengersNonZero = $this->getNoOfPassengersWithNonZeroOwing();

        if($noPassengersNonZero > 0)
        {
            if($paymentAmountBalance >= ($minOwingPayment * $noPassengersNonZero)) //Distribute the minimum amount owing across all non-zero owing passengers
            {
                $calculatedPayment = $minOwingPayment;
            }
            else //Distribute the entire PaymountAmountBalance across all non-zero owing passengers.
            {
                if($noPassengersNonZero == 0)
                {
                    throw new \Exception("No passengers with non-zero owing.");
                    $noPassengersNonZero = 1;
                }
                //REPLACE CALCULATION WITH ONE THAT CAN CALCULATE THE divide by 3, divide by 5, etc NON INTEGER RESULT CALCULATIONS.
                $calculatedPayment = ($paymentAmountBalance / $noPassengersNonZero);
            }
        }
        else
        {
            return $calculatedPayment;
        }
        
//         $subAmount = '';
//         $owingTotal = '';
//         foreach ($this->passengerPaymentStatus as $passengerPaymentStatus)
//         {
//             $subAmount = $subAmount . " -> ". $passengerPaymentStatus->getSubPaymentTotal();
//             $owingTotal = $owingTotal. " -> ".  $passengerPaymentStatus->getOwingBalance();
//         }
        foreach ($this->passengerPaymentStatus as $passengerPaymentStatus)
        {
            $passengerPaymentStatus->adjustStatus($calculatedPayment);
        }
//         throw new \Exception("HERE101 ".$calculatedPayment);
//         throw new \Exception("HERE7 ".($calculatedPayment * $noPassengersNonZero));
//         foreach ($this->passengerPaymentStatus as $passengerPaymentStatus)
//         {
//             $subAmount = $subAmount . " -> ". $passengerPaymentStatus->getSubPaymentTotal();
//             $owingTotal = $owingTotal. " -> ".  $passengerPaymentStatus->getOwingBalance();
//         }
        return ($calculatedPayment * $noPassengersNonZero);
    }
}
