<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Yaml\Yaml;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\Console\Input\InputOption;
use App\Entity\MediaObject;
use App\Entity\RealEstateAgent;
use Liip\ImagineBundle\Service\FilterService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;


class CheckMediaObjectCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:check-media-object';

	private $container;

    private $em;

    private $imagine;

    private $cacheManager;

    
    public function __construct(ContainerInterface $container, FilterService $imagine, CacheManager $cacheManager)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getEntityManager();
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Check all media object')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to check all media object ...')
        // ->addOption('write-only', null, InputOption::VALUE_OPTIONAL, 'Write only ?', false)
        ; 
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $frontendPath = $this->container->getParameter('assets.path');
        $folderMediaAssets = $this->container->getParameter('assets.media.folder');
        $pathMediaAssets = $frontendPath . DIRECTORY_SEPARATOR . $folderMediaAssets . DIRECTORY_SEPARATOR;
        if ($this->lock()) {

            $start = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => ' . $start);

            $mediaObjectRepository = $this->em->getRepository(MediaObject::class);
            $results = $mediaObjectRepository->findAll();
            $filesystem = new Filesystem();
            $mediaService = $this->container->get('app.tools.media');

            /**
            * Each all medias
            **/
            $i = 0;
            $notFound = [];
                    $saveFound = [];
                     $empty = [];
            foreach ($results as $key => $media) {
                
               
                if(!empty($media->getFilename())) {
                    $sourceFilePath = $pathMediaAssets . $media->getFilename();
                    $output->writeln($sourceFilePath);
                    
                    
                    if($filesystem->exists($sourceFilePath)) {
                    $i++;
                    
                    // $output->writeln($media->getFilename());
                    // $output->writeln($media->getOriginalFilename());
                
               

                    //     $output->writeln('<info>' . $sourceFilePath . '</info>');
                    //     $dimensions = getimagesize($sourceFilePath);
                    //     $contentSize = filesize($sourceFilePath);
                    //     // $contentSize = $mediaService->formatBytes($contentSize);

                    //     $media->setContentSize([$contentSize]);
                    //     $media->setDimensions($dimensions);
                    //     $this->em->flush($media);
                    } else {
                        // dossier de sauvegarde
                        $savePathMediaAssets = $frontendPath . DIRECTORY_SEPARATOR . 'uploads_compress/media/files' . DIRECTORY_SEPARATOR;
                        $saveSourceFilePath = $savePathMediaAssets . $media->getFilename();
                        if($filesystem->exists($saveSourceFilePath)) {
                            $filesystem->copy(
                                $saveSourceFilePath
                                , $sourceFilePath
                            );
                            $saveFound[] = $saveSourceFilePath;
                        } else {
                            $notFound[] = $media->getFilename();
                        }
                        
                        
                    }
                } else {
                    $empty[] = $sourceFilePath;
                }
                
            }
            dump($folderMediaAssets);
            dump('total results : ', count($results));
            dump('found : ', $i);
            dump('save found : ', count($saveFound));
            dump('not found : ', count($notFound));
            dump('empty getFilename: ', count($empty));
            
            


             
            $end = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => ' . $start);
            $output->writeln('end to => ' . $end);
        }
    }
}