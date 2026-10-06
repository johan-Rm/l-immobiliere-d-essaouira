<?php

namespace App\Service\Tools;

use Twig\Environment;
use Liip\ImagineBundle\Service\FilterService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Validator\Constraints\File;
use Knp\Snappy\Pdf;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Accommodation;


class PdfBuilder
{

    private $container;

    private $em;

    private $translator;

    private $templating;

    private $assetsPdfPath;


    public function __construct(
        ContainerInterface $container,
        Environment $templating,
        Pdf $knpSnappy,
        TranslatorInterface $translator
    ) {
        $this->container = $container;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->em = $this->container->get('doctrine')->getEntityManager();
        $this->knpSnappy = $knpSnappy;
        $this->assetsPdfPath = $this->container->getParameter('assets.pdf.path');
    }

    public function generatePdfFormSourceFolder()
    {

        $repository = $this->em->getRepository(Accommodation::class);

        /**
         * Get référence into json file
         **/
        $path = $this->container->getParameter('view.update.pdf_files.path');
        $filesystem = new Filesystem();
        if (!$filesystem->exists(
            $path . DIRECTORY_SEPARATOR . 'tmp'
        )) {
            $filesystem->mkdir(
                $path . DIRECTORY_SEPARATOR . 'tmp'
            );
        } else {
            $finder = new Finder();
            $finder->depth('== 0');
            $filesystem = new Filesystem();
            $finder->files()->in($path . DIRECTORY_SEPARATOR . 'tmp');
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $filesystem->remove($absoluteFilePath);
                }
            }
        }
        $finder = new Finder();
        $finder->depth('== 0');
        $filesystem = new Filesystem();
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {

                $absoluteFilePath = $file->getRealPath();
                $filePath = $file->getPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                $file = basename($fileNameWithExtension);
                $filename = basename($fileNameWithExtension, "." . $ext);
                $targetPath = $path . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $filename;
                if ($filesystem->exists($targetPath)) {
                    $filesystem->remove($targetPath);
                }
                $filesystem->rename($absoluteFilePath, $targetPath);
                $filesystem->remove($absoluteFilePath);
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
                // $filename = basename($fileNameWithExtension, ".".$ext);

                $jsonFile = json_decode(file_get_contents($absoluteFilePath), true);
                $slug = $jsonFile['slug'];
                $entity = $repository->findOneBy(['slug' => $slug]);
         
                if(null !== $entity) {
                    $filename = $this->createPdf($entity, 'fr');
                    $filename = $this->createPdf($entity, 'en');
                }
               
                $filesystem->remove($absoluteFilePath);
            }
        }
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

        $source = 'accommodation.' . $entity->getSlug();
        $name = $this->translator->trans($source, [], 'accommodations-slug', $locale);
        if($source === $name) {
            $name = $entity->getSlug();
        }
        $filesystem = new Filesystem();
        if ($filesystem->exists($this->assetsPdfPath . DIRECTORY_SEPARATOR . $name . '.pdf')) {
            $filesystem->remove($this->assetsPdfPath . DIRECTORY_SEPARATOR  . $name . '.pdf');
        }

        if ($filesystem->exists($this->assetsPdfPath . DIRECTORY_SEPARATOR  . $locale . '-'  . $name . '.pdf')) {
            $filesystem->remove($this->assetsPdfPath . DIRECTORY_SEPARATOR  . $locale . '-'  . $name . '.pdf');
        }


        $host = $assetsPath . DIRECTORY_SEPARATOR . $assetsMediaFolder . DIRECTORY_SEPARATOR;
        $logo = $host  . 'logo_bg_primary_new.png';
        $plan = false;
        foreach ($entity->getPdfs() as $pdf) {
            if ('plan' === $pdf->getType()->getSlug()) {
                $plan = $cdnHost . DIRECTORY_SEPARATOR . $assetsMediaFolder . DIRECTORY_SEPARATOR . $assetsDocumentFolder . $pdf->getFilename();
            }
        }

        $images = [];
        $host = $assetsPath . DIRECTORY_SEPARATOR . $assetsCachePrefix . DIRECTORY_SEPARATOR . 'grid_nostamp' . DIRECTORY_SEPARATOR . $assetsMediaFolder . DIRECTORY_SEPARATOR;
        if (null !== $entity->getGallery()) {
            foreach ($entity->getGallery()->getImageGalleries() as $value) {
                // $fileNameWithExtension = $value->getImage()->getFilename();
                // $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                // $filename = pathinfo($fileNameWithExtension,  PATHINFO_FILENAME);
                // $file = $filename . "." . $ext;
                // $image =  $host . $file;
                $image =  $host . $value->getImage()->getFilename();
                $images[] = $image;
            }
        }

        $html = $this->templating->render(
            'components/technical-card.html.twig',
            array(
                'accommodation' => $entity,
                'logo'          => $logo,
                'host'          => $host,
                'plan'          => $plan,
                'locale'        => $locale,
                'cdnHost'       => $cdnHost,
                'images'        => $images
            )
        );

        $filename = $this->assetsPdfPath . DIRECTORY_SEPARATOR  . $locale . '-'  . $name . '.pdf';

        $this->knpSnappy->generateFromHtml(
            $html,
            $filename
        );

        return $filename;
    }
}
