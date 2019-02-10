<?php
/**
 * Created by PhpStorm.
 * User: tomschneider
 * Date: 2019-02-08
 * Time: 15:53
 */

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BookingRequest;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class BookingRequestEmailSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * BookingRequestEmailSubscriber constructor.
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['emailGuest', EventPriorities::PRE_WRITE],
            KernelEvents::VIEW => ['emailOffice', EventPriorities::PRE_WRITE]
        ];
    }

    public function emailGuest(GetResponseForControllerResultEvent $event)
    {
        $bookingRequest = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$bookingRequest instanceof  BookingRequest || Request::METHOD_POST !== $method)
        {
            return;
        }
        $message = (new \Swift_Message('Booking Request - '.$bookingRequest->getName()))
            ->setFrom('tom.schneider@simpleitsolutions.ch')
            ->setTo('tom.schneider@simpleitsolutions.ch')
            ->setBody(
                $this->twig->render(
                    'bookingrequest/guest.email.html.twig',
                    ['bookingRequest' => $bookingRequest]
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;
        $this->mailer->send($message);

    }
    public function emailOffice(GetResponseForControllerResultEvent $event)
    {
        $bookingRequest = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$bookingRequest instanceof  BookingRequest || Request::METHOD_POST !== $method)
        {
            return;
        }

        $message = (new \Swift_Message('Booking Request - '.$bookingRequest->getName()))
            ->setFrom('tom.schneider@simpleitsolutions.ch')
            ->setTo('support@simpleitsolutions.ch')
            ->setBody(
                $this->twig->render(
                    'bookingrequest/office.email.html.twig',
                    ['bookingRequest' => $bookingRequest]
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;
        $this->mailer->send($message);
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
}
