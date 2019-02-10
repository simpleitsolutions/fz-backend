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
        $logger->error("******************************************* HERE00", ["doctrine"]);
        $logger->debug("******************************************* HERE01");
        $logger->info("******************************************* HERE02");
        $excep = new \Exception("TOM HERE: ".$logger->error("DEBUG LOGGER"));
        throw $excep;
        return $excep;

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');
        $bookingRequest = $serializer->deserialize($request->getContent(), BookingRequest::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($bookingRequest);
        $em->flush();


//        $message = \Swift_Message::newInstance()
//            ->setSubject('Fly Zermatt Booking Request')
//            ->setFrom('info@flyzermatt.com')
//            // ->setFrom('tom.schneider@simpleitsolutions.ch')
//            ->setTo($bookingRequest->getEmail())
//            ->setBody(
//                $this->renderView(
//                    'AazpBookingBundle:BookingRequest:guest.email.html.twig',
//                    array('bookingRequest' => $bookingRequest)
//                ),
//                'text/html'
//            )
//        ;
//        $this->get('mailer')->send($message);
        $logger->debug("******************************************* HERE1");

//        $message = (new \Swift_Message('Booking Request - '.$bookingRequest->getName()))
//            ->setFrom($bookingRequest->getEmail())
//            ->setTo('tom.schneider@simpleitsolutions.ch')
//            ->setBody(
//                "<h1>TOM<\h1>",
////                $this->renderView(
////                    'bookingrequest/admin.email.html.twig',
////                    ['bookingRequest' => $bookingRequest]
////                ),
//                'text/html'
//            )
//            /*
//             * If you also want to include a plaintext version of the message
//            ->addPart(
//                $this->renderView(
//                    'emails/registration.txt.twig',
//                    ['name' => $name]
//                ),
//                'text/plain'
//            )
//            */
//        ;
//        $mailer->send($message);

//        throw new \Exception("HERE");
        $logger->error("******************************************* HERE2");

        return $this->json($bookingRequest);
    }

}
