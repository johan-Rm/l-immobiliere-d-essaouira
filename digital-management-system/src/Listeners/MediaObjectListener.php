<?php

namespace App\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use App\Entity\MediaObject;
use App\Services\MediaObjectTransformer;
use Vich\UploaderBundle\Event\Event;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Cocur\Slugify\Slugify;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Filesystem\Filesystem;
// use Liip\ImagineBundle\Imagine\Cache\CacheManager;


/**
 * MediaObjectListener
 */
class MediaObjectListener
{
    private $contextParameters;
    private $cdnHost;
    private $orm;
    private $container;
    private $cacheManager;
    private $filesystem;
    private $mediaService;

    public function __construct(ContainerInterface $container, EntityManagerInterface $orm, CacheManager $cacheManager)
    {
        $this->orm = $orm;
        $this->container = $container;
        $this->cacheManager = $cacheManager;
        $this->cdnHost = $this->container->getParameter('cdn.host');
        $this->filesystem = new Filesystem();
        $this->mediaService = $this->container->get('app.tools.media');

        /************************************************************
        * Define context parameters
        *************************************************************/
        $assetsRootPath = $this->container->getParameter(
            'assets.path'
        );
        $assetsMediaFolder = $this->container->getParameter(
            'assets.media.folder'
        );
        $assetsMediaFolderPath = $assetsRootPath . DIRECTORY_SEPARATOR;
        $assetsMediaFolderPath.= $assetsMediaFolder . DIRECTORY_SEPARATOR;
        $this->contextParameters = [
            'assetsRootPath' => $assetsRootPath,
            'assetsMediaFolder' => $assetsMediaFolder,
            'assetsMediaFolderPath' => $assetsMediaFolderPath,
            'mimeTypes' => $this->mediaService->getMimetypesRules(),
        ];
    }

    public function onVichUploaderPreInject(Event $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof MediaObject) {
            return;
        }

        $filename = $entity->getFilename();

        $entity->setTmpFile($filename);
        $this->orm->flush();
    }

    public function onVichUploaderPostUpload(Event $event)
    {
        $entity = $event->getObject();
        $mapping = $event->getMapping();

        if($this->filesystem->exists($entity->getFile()->getRealPath())) {

            $filename = pathinfo($entity->getFilename(),  PATHINFO_FILENAME);
            $originalFilename = $entity->getFilename();
            $ext = pathinfo($entity->getFilename(), PATHINFO_EXTENSION);
            $filePath = $entity->getFile()->getPath();
            $mimeType = $entity->getFile()->getMimeType();
            $dimensions = getimagesize($entity->getFile()->getPathname());


            // if (!$entity instanceof MediaObject) {
            //     $entity->setEncodingFormat($entity->getFile()->getExtension());
            //     $entity->setContentSize(null);
            //     $name = $this->setName($entity);
            //     $entity->setName($name);
            //     $entity->setFilename($filename);
            //     $entity->setOriginalFilename($filename);
            // } else {

                $entity->setEncodingFormat($mimeType);
                $entity->setContentSize([]);
                $entity->setDimensions($dimensions);
                $name = $this->setName($entity);
                $entity->setName($filename);
                $entity->setFilename($originalFilename);
                $entity->setOriginalFilename($originalFilename);
            // }
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof MediaObject) {
            return;
        }

        $changeSet = $args->getEntityManager()
        ->getUnitOfWork()->getEntityChangeSet($entity);
        // dump($changeSet);
        // die;
        // if(!array_key_exists("filename", $changeSet)){
        //     return;
        // }
        try {
            // $this->cacheManager->remove($this->assetsMediaFolder.'/'.$entity->getTmpFile());
            // $this->cacheManager->resolve($this->assetsMediaFolder.'/'.$entity->getFilename(), null);

        } catch (\Exception $e) {

        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof MediaObject) {
            return;
        }

// dump('remove');die;
        $mediaName = pathinfo($entity->getFilename(),  PATHINFO_FILENAME);
        $webpFilename = $mediaName . '.' . 'webp';
        $target = $this->contextParameters['assetsMediaFolder'].'/'.$entity->getFilename();
        $targetWebp = $this->contextParameters['assetsMediaFolder'].'/'.$webpFilename;
        $fileSourceWebp = $this->contextParameters['assetsMediaFolderPath'].'/'.$webpFilename;
        // dump($targetWebp);die;
        // try {
            $this->cacheManager->remove($target);
            $this->cacheManager->remove($targetWebp);
            $this->filesystem->remove($fileSourceWebp);
        // } catch (\Exception $e) {

        // }

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof MediaObject) {
            return;
        }

        if(true === $entity->getRegenerateFormat()) {
            $this->setFormats($entity);
        }

        $url = $this->setMediaUrl($entity);
        $entity->setUrl($url);
        $name = $this->setName($entity);
        $entity->setName($name);
        $entity->setAlt($name);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof MediaObject) {
            return;
        }

        $this->setFormats($entity);

        $url = $this->setMediaUrl($entity);
        $entity->setUrl($url);
        $name = $this->setName($entity);
        $entity->setName($name);
        $entity->setAlt($name);
    }

    private function setFormats($entity)
    {
        if($entity->getEncodingFormat() !== 'video/youtube') {
            $filename = $entity->getFilename();
            $mediaName = pathinfo($filename,  PATHINFO_FILENAME);
            $assetsMediaFolderPath = $this->contextParameters['assetsRootPath'] . DIRECTORY_SEPARATOR;
            $assetsMediaFolderPath.= $this->contextParameters['assetsMediaFolder'] . DIRECTORY_SEPARATOR;
            $fileOriginalSourcePath = $assetsMediaFolderPath . DIRECTORY_SEPARATOR . $filename;

            $webpFilename = $mediaName . '.' . 'webp';
            $webpFileOriginalSourcePath = $assetsMediaFolderPath . $webpFilename;
            $targetFilePath = $assetsMediaFolderPath . $webpFilename;
            $fileParameters = [
                'filename' => $filename,
                'mediaName' => $mediaName,
                'webpFilename' => $webpFilename,
                'fileExtension' => pathinfo($entity->getFilename(), PATHINFO_EXTENSION),
                'fileOriginalSourcePath' => $fileOriginalSourcePath,
                'webpFileOriginalSourcePath' => $webpFileOriginalSourcePath,
                'targetFilePath' => $targetFilePath,
                'bytes' => round(filesize($fileOriginalSourcePath) / 1024),
            ];

            /************************************************************
            * Compress original file
            *************************************************************/
            if($fileParameters['bytes'] > 500) {
               // $this->mediaService->compressOriginalFile($this->contextParameters, $fileParameters);
            }

            /************************************************************
            * Generate original formats
            *************************************************************/
            $this->mediaService->generateMultiFormat($this->contextParameters, $fileParameters['filename'], $fileParameters['webpFilename']);

            /************************************************************
            * Webp conversion
            *************************************************************/
            // $this->mediaService->compressFileInWebpFormat($this->contextParameters, $fileParameters);

            /************************************************************
            * Generate webp formats
            *************************************************************/
            // $this->mediaService->generateMultiFormat($this->contextParameters, $fileParameters['webpFilename']);
        }

        $bytes = round(filesize($fileOriginalSourcePath) / 1024);
        $entity->setContentSize([$bytes]);
    }

    private function setMediaUrl($entity)
    {
        $url = $entity->getUrl();
        if($entity->getEncodingFormat() !== 'video/youtube') {

            return $this->cdnHost . DIRECTORY_SEPARATOR . $this->contextParameters['assetsMediaFolder'] . DIRECTORY_SEPARATOR . $entity->getFilename();
        }

        return $url;
    }

    private function setName($entity)
    {
        if(empty($entity->getName())) {
            $slugify = new Slugify();
            $name = pathinfo($entity->getFilename(),  PATHINFO_FILENAME);
            $name = $slugify->slugify($name);

            return $name;
        }

        return $entity->getName();
    }

    private function setFilename($entity)
    {
 		if(!empty($entity->getName())) {
			$ext = pathinfo($entity->getFilename(), PATHINFO_EXTENSION);
			$entity->setFilename($entity->getName() . '.' . $ext);
        }
    }





}
