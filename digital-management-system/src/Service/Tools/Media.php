<?php

namespace App\Service\Tools;

use Liip\ImagineBundle\Service\FilterService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Entity\MediaObject;
use Symfony\Component\Validator\Constraints\File;


class Media
{

	/**
     * @var ContainerInterface
     */
	private $container;

	/**
     * @var FilterService
     */
    private $imagine;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    private $assetsCachePrefix;


    public function __construct(
    	FilterService $imagine
        , CacheManager $cacheManager
        , Filesystem $filesystem
        , ContainerInterface $container
    ){
    	$this->imagine      = $imagine;
        $this->cacheManager = $cacheManager;
        $this->filesystem   = $filesystem;
        $this->container   = $container;
        $this->assetsCachePrefix = $this->container->getParameter('assets.cache_prefix');
    }

    public function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1000));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1000, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function compressOriginalFile($contextParameters, $fileParameters)
    {
        $sourceFileFolder = $contextParameters['assetsMediaFolder'] . DIRECTORY_SEPARATOR . $fileParameters['filename'];

        $fileOriginalCachePath = $contextParameters['assetsRootPath'] . DIRECTORY_SEPARATOR;
        $fileOriginalCachePath.= $this->assetsCachePrefix . DIRECTORY_SEPARATOR . 'original';
        $fileOriginalCachePath.= DIRECTORY_SEPARATOR .  $sourceFileFolder;
		$filter = 'original';
		// if($fileParameters['bytes'] > 1000) {
		// 	$fileOriginalCachePath = $contextParameters['assetsRootPath'] . DIRECTORY_SEPARATOR;
	    //     $fileOriginalCachePath.= $this->assetsCachePrefix . DIRECTORY_SEPARATOR . 'original_low';
	    //     $fileOriginalCachePath.= DIRECTORY_SEPARATOR .  $sourceFileFolder;
		// 	$filter = 'original_low';
		// }
        $this->imagine->getUrlOfFilteredImage(
            $sourceFileFolder
            , $filter
        );

		$bytes = round(filesize($fileOriginalCachePath) / 1024);
		// if($bytes > $fileParameters['bytes'] && 'original' === $filter) {
		// 	$this->filesystem->remove($fileOriginalCachePath);
		// 	$fileOriginalCachePath = $contextParameters['assetsRootPath'] . DIRECTORY_SEPARATOR;
	    //     $fileOriginalCachePath.= $this->assetsCachePrefix . DIRECTORY_SEPARATOR . 'original_low';
	    //     $fileOriginalCachePath.= DIRECTORY_SEPARATOR .  $sourceFileFolder;
		// 	$filter = 'original_low';
		// 	$this->imagine->getUrlOfFilteredImage(
	    //         $sourceFileFolder
	    //         , $filter
	    //     );
		// }

        $this->filesystem->copy(
        	$fileOriginalCachePath
        	, $fileParameters['fileOriginalSourcePath']
        );
		$this->filesystem->remove($fileOriginalCachePath);
    }


    public function compressFileInWebpFormat($sourceFilePath, $targetFilePath)
    {
		$quality = '70';
        $cmd = [
            '/usr/bin/cwebp',
            '-q',
            $quality,
            $sourceFilePath,
            '-o',
            $targetFilePath
        ];
        $process = new Process($cmd);
        $process->setTimeout(900);
        $process->mustRun();
    }

    public function getMimetypesRules()
    {
        $annotationEntityService = $this->container->get(
            'app.export.annotation_entity'
        );
        $constraints = $annotationEntityService->getContraintsByField(
            MediaObject::class
            , 'file'
        );

        $mimeTypes = [];
        foreach($constraints as $constraint)
        {
            if ($constraint instanceof File) {
                $mimeTypes = $constraint->mimeTypes;
            }
        }

        return $mimeTypes;
    }

    public function generateMultiFormat($contextParameters, $fileName, $webpFilename)
    {
        $filter_sets = $this->container->getParameter(
            'liip_imagine.filter_sets'
        );

		$fileCachePath = $contextParameters['assetsMediaFolder'] . DIRECTORY_SEPARATOR . $fileName;
		$webpFileCachePath = $contextParameters['assetsMediaFolder'] . DIRECTORY_SEPARATOR . $webpFilename;
        foreach($filter_sets as $key => $filter) {
            /**
            * Skip Always Original Format
            */
            if('original' !== $key) {
                $cachePath = $contextParameters['assetsRootPath'] . DIRECTORY_SEPARATOR;
                $cachePath.= $this->assetsCachePrefix . DIRECTORY_SEPARATOR . $key . DIRECTORY_SEPARATOR;

				$fileCacheFullPath = $cachePath . DIRECTORY_SEPARATOR .  $fileCachePath;
                if($this->filesystem->exists($fileCacheFullPath)) {
                    $this->cacheManager->remove($fileCachePath, $key);
                }
				$this->imagine->getUrlOfFilteredImage($fileCachePath, $key);
				// attention runtime creer des dossiers cryptés
				// $this->imagine->getUrlOfFilteredImageWithRuntimeFilters($sourceFileFolder, $key, $runtimeConfig);

				$webpFileCacheFullPath = $cachePath . DIRECTORY_SEPARATOR .  $webpFileCachePath;
				if($this->filesystem->exists($webpFileCachePath)) {
                    $this->cacheManager->remove($webpFileCacheFullPath, $key);
                }
				$this->compressFileInWebpFormat($fileCacheFullPath, $webpFileCacheFullPath);
            }
        }

    }

		public function defineSEOAltBalise($media)
		{
				$siteTitle = $this->container->getParameter('siteweb.title');

				return $media->getName() . ' - ' . $siteTitle;
		}

		public function defineName($media)
		{
				if(empty($media->getName())) {
						$slugify = new Slugify();
						$name = pathinfo($media->getFilename(),  PATHINFO_FILENAME);
						$name = $slugify->slugify($name);

						return $name;
				}

				return $media->getName();
		}

		public function defineUrl($media)
		{
				$cdnHost = $this->container->getParameter('cdn.host');
				$assetsMediaFolder = $this->container->getParameter(
						'assets.media.folder'
				);

				$url = $media->getUrl();
				if($media->getEncodingFormat() !== 'video/youtube') {

						return $cdnHost . DIRECTORY_SEPARATOR .  $assetsMediaFolder . DIRECTORY_SEPARATOR . $media->getFilename();
				}

				return $url;
		}
}
