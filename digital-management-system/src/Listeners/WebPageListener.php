<?php

namespace App\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\WebPage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\Filesystem\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;


/**
 * WebPageListener
 */
class WebPageListener
{
    private $orm;
    private $viewHost;
    private $container;
    private $imagine;
    private $cacheManager;
    private $assetsMediaFolder;

    public function __construct(EntityManagerInterface $orm, ContainerInterface $container, FilterService $imagine, CacheManager $cacheManager)
    {
        $this->orm = $orm;
        $this->container = $container;
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;

        $this->assetsMediaFolder = $this->container->getParameter('assets.media.folder');
        $this->viewHost = $this->container->getParameter('view.host');
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof WebPage) {
            return;
        }

        if (php_sapi_name() !== "cli") {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'web_pages']);
        }
       
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WebPage) {
            return;
        }

        if(empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

        $url = $this->setWebPageUrl($entity->getSlug());
        $entity->setUrl($url);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WebPage) {
            return;
        }

        if(empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }
        
        $url = $this->setWebPageUrl($entity->getSlug());
        $entity->setUrl($url);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WebPage) {
            return;
        }

        if (php_sapi_name() !== "cli") {

            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'web_pages']);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof WebPage) {
            return;
        }

        if (php_sapi_name() !== "cli") {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'web_pages']);
        }
       
    }

    private function setWebPageUrl($slug)
    {
        return $this->viewHost . '/' . $slug;
    }
}