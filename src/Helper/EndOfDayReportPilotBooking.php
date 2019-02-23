<?php
namespace App\Helper;

use App\Entity\PurchaseItem;
use App\Entity\Booking;
use App\Entity\Passenger;

class EndOfDayReportPilotBooking
{
    private $booking;
    
    private $passenger;
    
    private $flightPurchaseItem;

    private $photoPurchaseItem;
    
    private $creditCardPurchaseItem;

    private $cardPayment;
    
    private $invoicePayment;
    
    private $commission;

    public function __construct(Booking $booking, Passenger $passenger, PurchaseItem $flightPurchaseItem=null, PurchaseItem $photoPurchaseItem=null, PurchaseItem $creditCardFeePurchaseItem=null, $cardPayment, $invoicePayment, $voucherPayment, $commission)
    {
        $this->booking = $booking;
        $this->passenger = $passenger;
        $this->flightPurchaseItem = $flightPurchaseItem;
        $this->photoPurchaseItem = $photoPurchaseItem;
        $this->creditCardPurchaseItem =$creditCardFeePurchaseItem;
        $this->cardPayment = $cardPayment;
        $this->invoicePayment = $invoicePayment;
        $this->voucherPayment = $voucherPayment;
        $this->commission = $commission;
    }
    /**
     *
     * @return the Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     *
     * @return the PurchaseItem
     */
    public function getFlightPurchaseItem()
    {
        return $this->flightPurchaseItem;
    }

    /**
     *
     * @return the PurchaseItem
     */
    public function getPhotoPurchaseItem()
    {
        return $this->photoPurchaseItem;
    }

    /**
     *
     * @return the decimal
     */
    public function getCommission()
    {
        return $this->commission;
    }



    public function getBookingNo()
    {
        return $this->booking->getId();
    }

    public function getBookingTime()
    {
        return $this->booking->getFlightdate()->format('H:i');
    }
    
    public function getPassengerName()
    {
        return $this->passenger->getName();
    }

    public function getFlightName()
    {
        if($this->flightPurchaseItem === null)
        {
            return '';
        }
        return $this->flightPurchaseItem->getProduct()->getDescription();
    }
    public function getFlightPurchaseAmount()
    {
        if(!$this->passenger->paidInFull())
        {
            return 'OPEN';
        }
        $flightPurchaseAmount = 0.0;
        if($this->flightPurchaseItem != null)
        {
            $flightPurchaseAmount = $this->flightPurchaseItem->getAmount();
        }
        return $flightPurchaseAmount;
    }

    public function getPhotoPurchaseAmount()
    {
        if(!$this->passenger->paidInFull())
        {
            return 'OPEN';
        }
        $photoPurchaseAmount = 0.0;
        if($this->photoPurchaseItem != null)
        {
            $photoPurchaseAmount = $this->photoPurchaseItem->getAmount();
        }
        return $photoPurchaseAmount;
    }
    
    public function getCreditCardPurchaseAmount()
    {
        $creditCardPurchaseAmount = 0.0;
        if($this->creditCardPurchaseItem != null)
        {
            $creditCardPurchaseAmount = $this->creditCardPurchaseItem->getAmount();
        }
        return $creditCardPurchaseAmount;
    }
    
    public function getTotalPilotBookingPayment()
    {
        if(!$this->passenger->paidInFull())
        {
            return 0.0;
        }
        $totalPilotPayment = 0.0;
        if($this->photoPurchaseItem != null)
        {
            $totalPilotPayment = $totalPilotPayment + $this->photoPurchaseItem->getAmount();
        }
        if($this->flightPurchaseItem != null)
        {
            $totalPilotPayment = $totalPilotPayment + $this->flightPurchaseItem->getAmount();
        }
//         if($this->creditCardPurchaseAmount != null)
//         {
//             $totalPilotPayment = $totalPilotPayment - $this->creditCardPurchaseAmount->getAmount();
//         }
        $totalPilotPayment = $totalPilotPayment - $this->commission;
        return $totalPilotPayment;
    }

    public function isCardPayment()
    {
        return $this->cardPayment;
    }

    public function isInvoicePayment()
    {
        return $this->invoicePayment;
    }

    public function isSumUpPayment()
    {
        $sumupPayment = false;
        if(is_null($this->passenger->getPurchase()))
        {
            return $sumupPayment;
        }
        foreach ($this->passenger->getPurchase()->getPayments() as $payment)
        {
            if(!$payment->getRefunded())
            {
                if ($payment->getPaymentType()->getName() == 'VISA' ||
                    $payment->getPaymentType()->getName() == 'M/C' ||
                    $payment->getPaymentType()->getName() == 'JCB' ||
                    $payment->getPaymentType()->getName() == 'AMEX' ||
                    $payment->getPaymentType()->getName() == 'MAESTRO' ||
                    $payment->getPaymentType()->getName() == 'MATTERHORN PARAGLIDING' ||
                    $payment->getPaymentType()->getName() == 'SKY GIRL' ||
                    $payment->getPaymentType()->getName() == 'FLOAT PARAGLIDING')
                {
                    $sumupPayment = true;
                }
            }
        }
    
        return $sumupPayment;
    }

    public function getSumUpPaymentFootnote()
    {
        $sumupPaymentFootnotes = array();
        foreach ($this->passenger->getPurchase()->getPayments() as $payment)
        {
            if(!$payment->getRefunded())
            {
                if ($payment->getPaymentType()->getName() == 'VISA' ||
                    $payment->getPaymentType()->getName() == 'M/C' ||
                    $payment->getPaymentType()->getName() == 'JCB' ||
                    $payment->getPaymentType()->getName() == 'AMEX' ||
                    $payment->getPaymentType()->getName() == 'MAESTRO')
                {
                    $sumupPaymentFootnotes[] = '1';
                }
                else if ($payment->getPaymentType()->getName() == 'INVOICE')
                {
                    $sumupPaymentFootnotes[] = '2';
                }
                else if ($payment->getPaymentType()->getName() == 'VOUCHER')
                {
                    $sumupPaymentFootnotes[] = '3';
                }
                else if ($payment->getPaymentType()->getName() == 'MATTERHORN PARAGLIDING')
                {
                    $sumupPaymentFootnotes[] = '4';
                }
                else if ($payment->getPaymentType()->getName() == 'SKY GIRL')
                {
                    $sumupPaymentFootnotes[] = '5';
                }
                else if ($payment->getPaymentType()->getName() == 'FLOAT PARAGLIDING')
                {
                    $sumupPaymentFootnotes[] = '6';
                }
            }
        }
        
        $sumupPaymentFootnotes = array_unique($sumupPaymentFootnotes);
        return $sumupPaymentFootnotes;
    }
    
}
