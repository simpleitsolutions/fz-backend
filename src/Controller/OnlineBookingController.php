<?php
namespace App\Controller;
 
use App\Entity\Booking;
use App\Entity\BookingOwner;
use App\Entity\FlightScheduleTime;
use App\Entity\MeetingLocation;
use App\Entity\Passenger;
use App\Entity\Payment;
use App\Entity\PaymentType;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\BookingType;
use App\Form\BPPassengerType;
use App\Form\DateSelectorType;
use App\Form\PurchaseType;
use App\Helper\BookingDailySchedule;
use App\Helper\BookingPaymentHelper;
use App\Helper\PaymentViewHelper;
use App\Repository\PaymentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
//use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Choice;


/**
* @Route("/onlinebooking")
*/
class OnlineBookingController extends AbstractController
{
    /**
     * @Route(
     *     name="init",
     *     path="/api/init",
     *     methods={"GET"},
     *     defaults={
     *       "_controller"="\App\Controller\OnlineBookingController::init",
     *       "_api_item_operation_name"="init"
     *     }
     *   )
     */
    public function init()
    {
//        {
//        "max-pilots": "7",
//        "book-days-from-today": "2",
//        "book-future-months": "12",
//        "video-cost": "40"
//        }
        return $this->json([
            'max-pilots' => '7',
            'book-days-from-today' => '2',
            'book-future-months'  => '12',
            'video-cost' => '40',
        ]);
    }

    /**
     * @Route(
     *     name="flightOptions",
     *     path="/api/flightoptions/{flightDate}",
     *     methods={"GET"},
     *     defaults={
     *       "_controller"="\App\Controller\OnlineBookingController::flightOptions",
     *       "_api_item_operation_name"="flightoptions"
     *     }
     *   )
     */
    public function flightOptions(\DateTime $flightDate)
    {
//        {
//        "classic": "Classic High",
//        "scenic": "Scenic",
//        "elite": "Elite (Classic)"
//        }
        return $this->json([
            'classic' => 'Classic High',
            'scenic' => 'Scenic',
            'elite' => 'Elite (Classic)',
        ]);
    }
}
