<?php
namespace App\Twig;

use Doctrine\ORM\EntityNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('renderAttribute', [$this, 'renderAttribute']),
        ];
    }

    public function renderAttribute($entity = null, $attribute = null)
    {
        try
        {
            return call_user_func(array($entity, 'get'.$attribute));
        }
        catch (EntityNotFoundException $e)
        {
            return "[id:".$entity->getId()."]";
        }

        return "FOUND";
    }

}
