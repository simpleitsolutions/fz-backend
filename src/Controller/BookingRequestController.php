<?php
namespace App\Controller;

use App\Entity\BookingRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Psr\Log\LoggerInterface;
//use Psr\Log\LoggerAwareInterface;
//use Symfony\Bundle\MonologBundle\SwiftMailer;


/**
 * @Route("/booking/request")
 */
class BookingRequestController extends AbstractController
{

    /**
     * @Route("/add", name="booking_request_add", methods={"POST"})
     */
    public function add(Request $request, LoggerInterface $logger)
    {
        $logger->error("******************************** APP-ENV: ".$_SERVER['APP_ENV'], ["doctrine"]);

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');
        $bookingRequest = $serializer->deserialize($request->getContent(), BookingRequest::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($bookingRequest);
        $em->flush();

        return $this->json($bookingRequest);
    }

}
