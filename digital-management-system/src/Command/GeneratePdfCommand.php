<?php

namespace App\Command;

use Twig\Environment;
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
use Knp\Snappy\Pdf;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Accommodation;


class GeneratePdfCommand extends Command
{
    use LockableTrait;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:generate-pdf';

	private $container;

    private $em;

    private $translator;

    private $templating;

    private $assetsPdfPath;

    public function __construct(ContainerInterface $container, Environment $templating, Pdf $knpSnappy, TranslatorInterface $translator)
    {
        $this->container = $container;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->em = $this->container->get('doctrine')->getEntityManager();
        $this->knpSnappy = $knpSnappy;
        $this->assetsPdfPath = $this->container->getParameter('assets.pdf.path');
        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Generate all pdfs.')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to generate all pdfs...')
        ->addArgument('reference', InputArgument::OPTIONAL, ' ?')
        ->addOption('source-folder', null, InputOption::VALUE_OPTIONAL, 'Source folder ?', false)
        // ->addOption('write-only', null, InputOption::VALUE_OPTIONAL, 'Write only ?', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->lock()) {
            $start = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => ' . $start);

            $repository = $this->em->getRepository(Accommodation::class);
            $sourceFolder = $input->getOption('source-folder');

            if (false !== $sourceFolder) {

                /**
                * Get référence into json file
                **/
                $path = $this->container->getParameter('view.update.pdf_files.path');
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
                    foreach ($finder as $file) {

                        $absoluteFilePath = $file->getRealPath();
                        $filePath = $file->getPath();
                        $fileNameWithExtension = $file->getRelativePathname();
                        $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                        $file = basename($fileNameWithExtension);
                        $filename = basename($fileNameWithExtension, ".".$ext);
                        $jsonFile = json_decode(file_get_contents($absoluteFilePath), true);

                        $slug = $jsonFile['slug'];
                        $entity = $repository->findOneBy([ 'slug' => $slug ]);

                        $filename = $this->createPdf($entity, 'fr');
                        $output->writeln($filename);
                        $filename = $this->createPdf($entity, 'en');
                        $output->writeln($filename);
                        $filesystem->remove($absoluteFilePath);
                    }
                }

            } else {
                $reference = $input->getArgument('reference');
                if ($reference) {
                    $entity = $repository->findOneBy([ 'reference' => $reference ]);
                    $filename = $this->createPdf($entity, 'fr');
                    $output->writeln($filename);
                    $filename = $this->createPdf($entity, 'en');
                    $output->writeln($filename);
                } else {
                    $results = $repository->findBy([ 'isActive' => true ]);
                    foreach ($results as $key => $entity) {
                        $filename = $this->createPdf($entity, 'fr');
                        $output->writeln($filename);
                        $filename = $this->createPdf($entity, 'en');
                        $output->writeln($filename);
                        // break;
                    }
                }
            }


            $end = date('Y-m-d\ H:i:s.u');
            $output->writeln('start to => ' . $start);
            $output->writeln('end to => ' . $end);
        }

        return 0;//Command::SUCCESS;
    }

    private function createPdf($entity, $locale)
    {
        $this->knpSnappy->setTimeout(300);

        $assetsPath = $this->container->getParameter('assets.path');
        $assetsDocumentFolder = $this->container->getParameter('assets.document.folder');
        $assetsCachePrefix = $this->container->getParameter('assets.cache_prefix');
        $assetsMediaFolder = $this->container->getParameter('assets.media.folder');
        $assetsMediaPath = $assetsPath . DIRECTORY_SEPARATOR . $assetsCachePrefix . DIRECTORY_SEPARATOR . 'grid_nostamp' . DIRECTORY_SEPARATOR . $assetsMediaFolder;
        $cdnHost = $this->container->getParameter('cdn.host');

        $name = $this->translator->trans('accommodation.' . $entity->getSlug(), [], 'accommodations-slug', $locale);

        $filesystem = new Filesystem();
        if($filesystem->exists($this->assetsPdfPath . DIRECTORY_SEPARATOR . $name . '.pdf')
        ) {
            $filesystem->remove($this->assetsPdfPath . DIRECTORY_SEPARATOR  . $name . '.pdf');
        }

        if($filesystem->exists($this->assetsPdfPath . DIRECTORY_SEPARATOR  . $locale . '-'  . $name . '.pdf')
        ) {
            $filesystem->remove($this->assetsPdfPath . DIRECTORY_SEPARATOR  . $locale . '-'  . $name . '.pdf');
        }

        $host = $assetsPath . DIRECTORY_SEPARATOR . $assetsMediaFolder . DIRECTORY_SEPARATOR;
        $logo = $host  . 'logo_bg_primary_new.png';
        $plan = false;
        foreach($entity->getPdfs() as $pdf) {
            if('plan' === $pdf->getType()->getSlug()) {
                $plan = $cdnHost . DIRECTORY_SEPARATOR . $assetsMediaFolder . DIRECTORY_SEPARATOR . $assetsDocumentFolder . $pdf->getFilename();
            }
        }

        $images = [];
        $host = $assetsPath . DIRECTORY_SEPARATOR . $assetsCachePrefix . DIRECTORY_SEPARATOR . 'grid_nostamp' . DIRECTORY_SEPARATOR . $assetsMediaFolder . DIRECTORY_SEPARATOR;
        if(null !== $entity->getGallery()){
            foreach($entity->getGallery()->getImageGalleries() as $value) {
                // $image = $cdnHost . DIRECTORY_SEPARATOR . $assetsCachePrefix . DIRECTORY_SEPARATOR . 'grid_nostamp' . DIRECTORY_SEPARATOR . $assetsMediaFolder . DIRECTORY_SEPARATOR . $value->getImage()->getFilename();
                $image =  $host . $value->getImage()->getFilename();
                $images[] = $image;

            }
        }

        $html = $this->templating->render(
            'components/technical-card.html.twig',
            array(
                'accommodation' => $entity,
                'logo' => $logo,
                'host' => $host,
                'plan' => $plan,
                'locale' => $locale,
                'cdnHost' => $cdnHost,
                'images' => $images
            )
        );

        $filename = $this->assetsPdfPath . DIRECTORY_SEPARATOR  . $locale . '-'  . $name . '.pdf';
        $this->knpSnappy->generateFromHtml(
            $html,
            $filename
        );

        return $filename;
    }

    private function convertWebpToPng($sourceFilePath, $targetFilePath)
    {
         $cmd = [
            '/usr/bin/dwebp',
            $sourceFilePath,
            '-o',
            $targetFilePath
        ];

        $process = new Process($cmd);
        $process->setTimeout(900);
        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
    }
}
