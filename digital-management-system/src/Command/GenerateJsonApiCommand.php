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
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;


class GenerateJsonApiCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:generate-json-api';

	private $container;

    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getEntityManager();

        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Generate Json Api.')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to generate json api...')
        ->addOption('full', null, InputOption::VALUE_OPTIONAL, ' ?', false)
        ->addOption('list', null, InputOption::VALUE_OPTIONAL, ' ?', false)
        ->addArgument('entity', InputArgument::OPTIONAL, ' ?')
        ->addArgument('slug', InputArgument::OPTIONAL, ' ?')
        ->addOption('source-folder', null, InputOption::VALUE_OPTIONAL, 'Source folder ?', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->lock()) {
            $start = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => ' . $start);

            $exportApiToJsonService = $this->container->get('app.export.api_to_json');
            $sourceFolder = $input->getOption('source-folder');

            if (false !== $sourceFolder) {
                $this->generateJsonData();
            } else {
                $option = $input->getOption('full');
                if(false !== $option) {
                    $exportApiToJsonService->generate('full');
                    // $exportApiToJson->generate('full');    
                } else {

                    $entity = $input->getArgument('entity');
                    $slug = $input->getArgument('slug');

                    $options = [
                        'query' => [
                            'pagination' => false
                        ]
                    ];
                    if(null !== $slug) {
                        $options['query']['slug'] = $slug;
                    }

                    $option = $input->getOption('list');
                    if(false !== $option) {
                        $exportApiToJsonService->generateList($entity, $options);
                    } else {
                        $exportApiToJsonService->generateOne($entity, $options);
                    }
                }
            }

            $end = date('Y-m-d\ H:i:s.u');
            $output->writeln('end to => ' . $end);
        }

        return 0;//Command::SUCCESS;
    }

    private function generateJsonData()
    {
        $exportApiToJsonService = $this->container->get('app.export.api_to_json');
        $viewHost = $this->container->getParameter('view.host');
        $path = $this->container->getParameter(
            'view.update.json_data.path'
        ) . DIRECTORY_SEPARATOR;

        $finder = new Finder();
        $finder->depth('== 0');
        $filesystem = new Filesystem();
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            $filesystem = new Filesystem();
            if(!$filesystem->exists(
                $path . DIRECTORY_SEPARATOR . 'tmp'
            )) {
                $filesystem->mkdir(
                    $path . DIRECTORY_SEPARATOR . 'tmp'
                );
            }
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filePath = $file->getPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                $file = basename($fileNameWithExtension);
                $filename = basename($fileNameWithExtension, ".".$ext);
                $filesystem->rename($absoluteFilePath, $path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $filename);
            }
        }

        $finder = new Finder();
        $finder->depth('== 0');
        $filesystem = new Filesystem();
        $finder->files()->in($path . DIRECTORY_SEPARATOR . 'tmp');
        if ($finder->hasResults()) {
            // si une les données ont déja été récupérés on ne refait pas le call api
            $apiCalls = [];
            $logs = [];
            $routesLog = [];
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $routeConfig = json_decode($file->getContents(), true);
                if(!isset($apiCalls[$routeConfig['route']])) {
                    $exportApiToJsonService->generateList($routeConfig['route']);
                    $apiCalls[$routeConfig['route']] = true;
                    $filesystem->remove($absoluteFilePath);
                }
            }
        } else {

            return false;
        }

        return true;
    }
}
