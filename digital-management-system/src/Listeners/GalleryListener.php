<?php

namespace App\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use App\Entity\Gallery;
use App\Entity\ImageGallery;
use App\Entity\WebPage;
use Cocur\Slugify\Slugify;
use Symfony\Component\Filesystem\Filesystem;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Service\FilterService;


/**
 * GalleryListener
 */
class GalleryListener
{
    private $orm;
    private $container;
    private $imagine;
    private $cacheManager;
    private $assetsMediaFolder;

    public function __construct(ContainerInterface $container, EntityManagerInterface $orm, FilterService $imagine, CacheManager $cacheManager)
    {
        $this->orm = $orm;
        $this->container = $container;
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;
        $this->assetsMediaFolder = $this->container->getParameter('assets.media.folder');
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Gallery) {
            return;
        }

        $entity = $this->orm->getRepository(WebPage::class)->findOneBy(array('slug' => 'accueil'));
        if (php_sapi_name() !== "cli") {

            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);  
        }
        
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Gallery) {
            return;
        }

        $entity = $this->orm->getRepository(WebPage::class)->findOneBy(array('slug' => 'accueil'));
        if (php_sapi_name() !== "cli") {

            $nuxtJsRouter = $this->container->get('app.nuxtjs.router');
            $nuxtJsRouter->generate($entity, ['route' => 'web_pages']);  
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Gallery) {
            return;
        }
        
        // dump($_POST);
        // dump($entity);
        // die;
    }
    
    private function syncGallery($entity, $em, $uow)
    {   
        
        $slugify = new Slugify();
        $currentImages = [];
        $i = 0;
        foreach($entity->getImages() as $image) {
            $i++;

            $imageGallery = $em->getRepository(ImageGallery::class)
            ->findOneBy(
                [
                    'gallery' => $entity->getId(),
                    'image' => $image->getId()
                ]
            );
            // dump($image->getFilename());
            if(null === $imageGallery) {
                $imageGallery = new ImageGallery();
                $imageGallery->setPosition($i);
                $imageGallery->setImage($image);
                $imageGallery->setGallery($entity);
                
                $em->persist($imageGallery);
                // Instead of $em->flush() because we are already in flush process
                $uow->computeChangeSet($em->getClassMetadata(get_class($imageGallery)), $imageGallery);
            }
            $slug = $slugify->slugify($image->getFilename());
            $currentImages[$slug] = true;
        }

        foreach($entity->getImageGalleries() as $img) {
            $slug = $slugify->slugify($img->getImage()->getFilename());
            if (!isset($currentImages[$slug])) {
                $em->remove($img);
            }
            $uow->computeChangeSet($em->getClassMetadata(get_class($img)), $img);
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Gallery) {
                
                $this->syncGallery($entity, $em, $uow);
            }
        }
// dump('syncGallery');die;
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof Gallery) {
                
                $this->syncGallery($entity, $em, $uow);
            }
        }
    }
}
