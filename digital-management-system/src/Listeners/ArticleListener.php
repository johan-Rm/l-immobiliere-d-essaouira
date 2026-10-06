<?php

namespace App\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\Filesystem\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;


/**
 * WebPageListener
 */
class ArticleListener
{
    private $orm;
    private $token;
    private $viewHost;
    private $container;
    private $imagine;
    private $cacheManager;
    private $assetsMediaFolder;

    public function __construct(EntityManagerInterface $orm, TokenStorageInterface $token, ContainerInterface $container, FilterService $imagine, CacheManager $cacheManager)
    {
        $this->orm = $orm;
        $this->token = $token;
        $this->container = $container;
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;

        $this->assetsMediaFolder = $this->container->getParameter('assets.media.folder');
        $this->viewHost = $this->container->getParameter('view.host');
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Article) {
            return;
        }

        if (php_sapi_name() !== "cli") {

            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'full_articles']);  
            $nuxtJsRouter->generate($entity->getCategory(), ['route' => 'tags']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'full_articles']);  
            $nuxtJsExportJsonData->generate($entity->getCategory(), ['route' => 'tags']);
        }
       
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        if(empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

        if(empty($entity->getArticleResume())) {
            $resume = strip_tags($entity->getArticleBody());
            $resume = substr($resume, 0, 350);
            $resume = html_entity_decode($resume, ENT_QUOTES);
            $entity->setArticleResume(trim($resume));
        }
        
        $url = $this->setArticleUrl($entity->getSlug());
        $entity->setUrl($url);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }
        
        if(empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

        if(empty($entity->getArticleResume())) {
            $resume = strip_tags($entity->getArticleBody());
            $resume = substr($resume, 0, 350);
            $resume = html_entity_decode($resume, ENT_QUOTES);
            $entity->setArticleResume(trim($resume));
        }

        $url = $this->setArticleUrl($entity->getSlug());
        $entity->setUrl($url);
        // $this->orm->flush();
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Article) {
            return;
        }

        if (php_sapi_name() !== "cli") {

            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'full_articles']);  
            $nuxtJsRouter->generate($entity->getCategory(), ['route' => 'tags']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'full_articles']);  
            $nuxtJsExportJsonData->generate($entity->getCategory(), ['route' => 'tags']);
        }

    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Article) {
            return;
        }

        if (php_sapi_name() !== "cli") {
            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'full_articles']);

            $nuxtJsExportJsonData = $this->container->get('app.nuxtjs.export_json_data');
            $nuxtJsExportJsonData->generate($entity, ['route' => 'full_articles']);  
            $nuxtJsExportJsonData->generate($entity->getCategory(), ['route' => 'tags']);
        }
    }

    private function setArticleUrl($slug)
    {
        return $this->viewHost . '/actualite/' . $slug;
    }
}
