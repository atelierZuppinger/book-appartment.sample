<?php

namespace HeidiAlpen\SiteBundle\Menu;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader as YamlFileLoaderI18n;

use Symfony\Component\Routing\Loader\YamlFileLoader as YamlFileLoader;

use Knp\Menu\FactoryInterface;

use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\Voter\UriVoter;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

class MenuBuilder extends ContainerAware
{
    private $factory;
    private $security;

    /**
     * @param FactoryInterface $factory
     * @param SecurityContextInterface $security
     */
     
    public function __construct(FactoryInterface $factory, SecurityContextInterface $security)
    {
        $this->factory = $factory;
        $this->security = $security;
    }

    public function createMainMenu(Request $request)
    {
        $basePath = $request->getBaseUrl() . '/' . $request->getLocale();
        $t = new Translator($request->getLocale());
        
        $locator = new FileLocator(array( __DIR__ . '/../Resources/config/'));
        $loader = new YamlFileLoader($locator);
        $collection = $loader->load('routing.yml', 'en');

        $t->addLoader('yaml', new YamlFileLoaderI18n());

        $t->addResource('yaml', __DIR__ . '/../Resources/translations/messages.en.yml', 'en');
        $t->addResource('yaml', __DIR__ . '/../Resources/translations/messages.de.yml', 'de');
        $t->addResource('yaml', __DIR__ . '/../Resources/translations/messages.ru.yml', 'ru');
        $t->addResource('yaml', __DIR__ . '/../Resources/translations/messages.jp.yml', 'jp');

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'mainNav center-content');
            
        $menu->addChild('story', array('route' => 'heidi_alpen_the_story'));;
        $menu['story']
            ->setLabel($t->trans('global.topNav.theStory'))
            ->addChild(nl2br($t->trans('theStory.land.title')), array('uri' => $basePath . $collection->get('heidi_alpen_the_story')->getPattern() . '#land', 'extras' => array('safe_label' => true)))
        ;
        $menu['story']->addChild(nl2br($t->trans('theStory.aop.title')), array('uri' => $basePath . $collection->get('heidi_alpen_the_story')->getPattern() . '#aop', 'extras' => array('safe_label' => true)));
        
        $menu->addChild('expertise', array('route' => 'heidi_alpen_the_expertise'));
        $menu['expertise']
            ->setLabel($t->trans('global.topNav.theExpertise'))
            ->addChild(nl2br($t->trans('theExpertise.methods.title')), array('uri' => $basePath . $collection->get('heidi_alpen_the_expertise')->getPattern() . '#methods', 'extras' => array('safe_label' => true)))
        ;
        $menu->addChild('cheeses', array('route' => 'heidi_alpen_the_cheeses'));
        $menu['cheeses']
            ->setLabel($t->trans('global.topNav.theCheeses'))
            ->addChild(nl2br($t->trans('theCheeses.discover.title')), array('uri' => $basePath . $collection->get('heidi_alpen_the_cheeses')->getPattern() . '#cheeseMap', 'extras' => array('safe_label' => true)))
        ;
        $menu->addChild('receipts', array('route' => 'heidi_alpen_receipts'));
        $menu['receipts']
            ->setLabel($t->trans('global.topNav.theReceipts'));
        //$menu->addChild($t->trans('global.topMenu.contact'), array('route' => 'heidi_alpen_contact'));
        
        
        return $menu;
    }

    public function createMobileMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri());
        $menu->setChildrenAttribute('class', 'mobile-nav');

        return $menu;
    }
}