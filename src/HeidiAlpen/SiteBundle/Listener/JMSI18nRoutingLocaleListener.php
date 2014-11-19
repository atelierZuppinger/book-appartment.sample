<?php
namespace HeidiAlpen\SiteBundle\Listener;

use JMS\I18nRoutingBundle\Router\LocaleResolverInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContextAwareInterface;

class JMSI18nRoutingLocaleListener implements EventSubscriberInterface
{
    /**
     * @var RequestContextAwareInterface
     */
    private $router;

    /**
     * @var LocaleResolverInterface
     */
    private $localeResolver;

    /**
     * Constructor
     *
     * @param LocaleResolverInterface      $localeResolver
     * @param RequestContextAwareInterface $router
     */
    public function __construct(LocaleResolverInterface $localeResolver, RequestContextAwareInterface $router = null)
    {
        $this->localeResolver = $localeResolver;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $locale = $this->localeResolver->resolveLocale($request, array());

        $request->setDefaultLocale($locale);
        $request->setLocale($locale);
        $this->router->getContext()->setParameter('_locale', $locale);
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 35)),
        );
    }
}