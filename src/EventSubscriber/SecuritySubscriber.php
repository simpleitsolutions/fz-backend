<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class SecuritySubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * SecuritySubscriber constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, AuthenticationUtils $authenticationUtils, LoggerInterface $logger, \Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->authenticationUtils = $authenticationUtils;
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'loginSuccess',
        ];
    }

    public function loginSuccess(InteractiveLoginEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $now = new \DateTime();
        $user->setLastLogin($now);
        $this->logger->info("USER LOGIN: ", [$user->getUsername(), $now->format('d.m.Y H:i:s')]);

        $this->entityManager->flush();
    }
}
