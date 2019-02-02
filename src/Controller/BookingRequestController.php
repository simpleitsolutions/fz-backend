<?php
namespace App\Controller;

use App\Entity\BookingRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/booking/request")
 */
class BookingRequestController extends AbstractController
{

    /**
     * @Route("/add", name="booking_request_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');
        $bookingRequest = $serializer->deserialize($request->getContent(), BookingRequest::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($bookingRequest);
        $em->flush();

        return $this->json($bookingRequest);
    }

}