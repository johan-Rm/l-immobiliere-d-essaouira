<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Cocur\Slugify\Slugify;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use App\Entity\MediaObject;
use App\Entity\Accommodation;
use Symfony\Contracts\Translation\TranslatorInterface;
use Knp\Snappy\Pdf;


class AdminController extends AbstractController
{
    private $knpSnappy;
    private $mailer;
    private $translator;

    public function __construct(Pdf $knpSnappy, \Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        $this->knpSnappy = $knpSnappy;
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    /**
     * @Route("/phpinfo", name="easyadmin_phpinfo")
     */
    public function phpInfoAction(): Response
    {
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }
        ob_start();
        phpinfo();
        $str = ob_get_contents();
        ob_get_clean();

        return new Response($str);
    }

    /**
     * Allows applications to modify the entity associated with the item being
     * created while persisting it.
     *
     * @param object $entity
     */
    protected function updateTranslationEntity($entity)
    {
        if(false == $entity->getIsHtml()) {
            $value = strip_tags($entity->getValueRight());
            $entity->setValueRight($value);
        }

        $this->em->flush();

        $translator = $this->container->get('app.translator');
        $translator->write();
    }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listBuildRouteAction()
    {
        $nuxtProjectDir = $this->container->getParameter('view.project_dir');
        // dump($nuxtProjectDir);
        $host = $this->container->getParameter('view.host');
        // $filesystem = new Filesystem();
        $finder = new Finder();
        $path = $this->container->getParameter('view.update.nuxt_routes.path');
        $results = []; $i = 0;
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filePath = $file->getPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                $file = basename($fileNameWithExtension);
                $filename = basename($fileNameWithExtension, ".".$ext);
                $jsonFile = json_decode(file_get_contents($absoluteFilePath), true);
                // $slug = $slugify->slugify($file);
                // dump($absoluteFilePath);
                // dump($file);
                // dump($jsonFile);
                $results[$i]['id'] = $i;
                $results[$i]['name'] = $host . $jsonFile['baseUrl'] . $jsonFile['slug'];
                $i++;
                // die;
            }
        }

        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        // dump($paginator);
        // die;
        // $paginator['currentPageResults'] = $results;
        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'currentPageResults' => $results,
            'headline' => 'Building list pages',
            'paginator' => $paginator,
            'fields' => $fields,
            '_has_batch_actions' => false,
            'delete_form_template' => $this->createDeleteForm(
                $this->entity['name']
                , '__id__'
            )->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', 'pages/building_list.html.twig', $parameters]);
    }

     /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listPdfCheckAction()
    {
        $nuxtProjectDir = $this->container->getParameter('view.project_dir');
        $host = $this->container->getParameter('view.host');
        // dump($nuxtProjectDir);
        $repository = $this->em->getRepository(Accommodation::class);
        $mediaService = $this->container->get('app.tools.media');
        $slugify = new Slugify();

        // $filesystem = new Filesystem();
        $finder = new Finder();
        $path = $this->container->getParameter('assets.pdf.path');
        $results = []; $i = 0;
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filePath = $file->getPath();
                $fileNameWithExtension = $file->getRelativePathname();
                $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                $file = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                $filename = basename($fileNameWithExtension, ".".$ext);
                // $jsonFile = json_decode(file_get_contents($absoluteFilePath), true);
                $slug = $slugify->slugify($file);

                $pos = strpos($slug, "-");
                if ($pos !== false) {
                    $locale = substr($slug, 0, $pos);
                    $slug = substr($slug, $pos+1);

                }




                if('fr' === $locale) {
                    $slug = $this->translator->trans('accommodation.' .  $slug, [], 'accommodations-slug', $locale);
                    $result = $repository->findOneBy([ 'slug' => $slug, 'isActive' => true ]);

                    if($result) {
                        $bytes = round(filesize($absoluteFilePath) / 1024);
                        if($bytes < 100) {
                            $results[$i]['id'] = $i;
                            $results[$i]['name'] = $filename;
                            $bytes = $mediaService->formatBytes(filesize($absoluteFilePath));
                            $results[$i]['taille'] = $bytes;
                            $results[$i]['reference'] = $result->getReference();
                            $i++;
                        }
                    }
                }
                // die;
            }
        }

        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        // dump($paginator);
        // die;
        // $paginator['currentPageResults'] = $results;
        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'currentPageResults' => $results,
            'headline' => 'Pdf Size Check List',
            'paginator' => $paginator,
            'fields' => $fields,
            '_has_batch_actions' => false,
            'delete_form_template' => $this->createDeleteForm(
                $this->entity['name']
                , '__id__'
            )->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', 'pages/pdf_check.html.twig', $parameters]);
    }

    /**
    * The method that is executed when the user performs a 'list' action on an entity.
    *
    * @return Response
    */
   protected function listImagesCheckAction()
   {
       $nuxtProjectDir = $this->container->getParameter('view.project_dir');
       $mediaService = $this->container->get('app.tools.media');
       $slugify = new Slugify();
       $finder = new Finder();
       $filesystem = new Filesystem();
       $path = $this->container->getParameter('assets.images.path');
       $resourcesFolder = $this->container->getParameter('assets.path');
       $cachePrefix = $this->container->getParameter('assets.cache_prefix');
       $filter = "large";
       $mediaFolder = $this->container->getParameter('assets.media.folder');

       $results = []; $i = 0;
       $finder->files()->in($path);
       if ($finder->hasResults()) {
           foreach ($finder as $file) {
               $absoluteFilePath = $file->getRealPath();

               $filePath = $file->getPath();
               $fileNameWithExtension = $file->getRelativePathname();
               $extension = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
               $file = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
               $filename = basename($fileNameWithExtension, "." . $extension);



               if('webp' !== $extension
                // && $filesystem->exists($fileLargeCachePath)
                ) {

                   $fileCachePath = $resourcesFolder . DIRECTORY_SEPARATOR;
                   $fileCachePath.= $cachePrefix . DIRECTORY_SEPARATOR;
                   $fileCachePath.= $filter . DIRECTORY_SEPARATOR;
                   $fileCachePath.= $mediaFolder . DIRECTORY_SEPARATOR;
                   $fileLargeCachePath = $fileCachePath . $filename . ".webp";
                   $bytes = round(filesize($absoluteFilePath) / 1024);
                   if($filesystem->exists($fileLargeCachePath)) {
                       $bytes = round(filesize($fileLargeCachePath) / 1024);

                       $dimensions = getimagesize($absoluteFilePath);
                       // $forCheckDimensions = $fileCachePath . $filename . '.jpg';
                       // if($filesystem->exists($forCheckDimensions)) {
                       //     $dimensions = getimagesize($forCheckDimensions);
                       // }
                       // $forCheckDimensions = $fileCachePath . $filename . '.jpeg';
                       // if($filesystem->exists($forCheckDimensions)) {
                       //     $dimensions = getimagesize($forCheckDimensions);
                       // }
                       $createdAt = filemtime($absoluteFilePath);

                       if($bytes > 500) {
                           // $results[$i]['id'] = $i;
                           $results[$i]['filename'] = $fileNameWithExtension;
                           $results[$i]['bytes'] = $bytes;
                           $results[$i]['dimensions'] = $dimensions[0] . 'x' . $dimensions[1];
                           $results[$i]['filemtime'] = $createdAt;
                           $results[$i]['createdAt'] = date("Y-m-d H:i:s", $createdAt);
                           $results[$i]['format'] = $extension;
                           $results[$i]['original_taille'] = $mediaService->formatBytes(filesize($absoluteFilePath));
                           $results[$i]['webp_taille'] = $mediaService->formatBytes(filesize($fileLargeCachePath));
                            if($filename == 'IMG_1404') {
                                // dump($results[$i]);die;

                            }
                           $i++;
                       }
                   }
               }
           }
       }

       usort($results, function($a, $b) {
           // return $a['bytes'] < $b['bytes'];
           return strtotime($a["createdAt"]) < strtotime($b["createdAt"]);
           // return strtotime($a["filemtime"]) - strtotime($b["filemtime"]);
       });

       $this->dispatch(EasyAdminEvents::PRE_LIST);

       $fields = $this->entity['list']['fields'];
       $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

       // dump($paginator);
       // die;
       // $paginator['currentPageResults'] = $results;
       $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

       $parameters = [
           'total' => $i,
           'currentPageResults' => array_slice($results, 0, 20),
           'headline' => 'Images Size Check List',
           'paginator' => $paginator,
           'fields' => $fields,
           '_has_batch_actions' => false,
           'delete_form_template' => $this->createDeleteForm(
               $this->entity['name']
               , '__id__'
           )->createView(),
       ];

       return $this->executeDynamicMethod('render<EntityName>Template', ['list', 'pages/images_check.html.twig', $parameters]);
   }

    /**
     * The method that is executed when the user performs a 'list' action on an entity.
     *
     * @return Response
     */
    protected function listMediaAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_LIST);

        $fields = $this->entity['list']['fields'];
        $paginator = $this->findAll($this->entity['class'], $this->request->query->get('page', 1), $this->entity['list']['max_results'], $this->request->query->get('sortField'), $this->request->query->get('sortDirection'), $this->entity['list']['dql_filter']);

        $this->dispatch(EasyAdminEvents::POST_LIST, ['paginator' => $paginator]);

        $parameters = [
            'paginator' => $paginator,
            'fields' => $fields,
            'delete_form_template' => $this->createDeleteForm($this->entity['name'], '__id__')->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['list', 'pages/list.html.twig', $parameters]);
    }

    /**
    * @Route("/dashboard", name="dashboard")
    *
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function dashboardAction(Request $request)
    {
        $parameters['headline'] = 'Dashboard Coming Soon';

        return $this->render('pages/dashboard.html.twig', $parameters);
    }

    /**
     * @Route("/coming_soon", name="coming_soon")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws ForbiddenActionException
     */
    public function comingSoonAction(Request $request)
    {
       $parameters['headline'] = 'Coming soon';
       $parameters['about'] = 'The false text is, in print, a text without meaning, whose sole purpose is to calibrate the content ...';

       return $this->render('pages/coming_soon.html.twig', $parameters);
    }

    public function downloadAction()
    {
        throw new \RuntimeException('Action for download an entity not defined');
    }

    /**
    *
    * @ Method({"GET", "POST"})
    * @Route("/ajax/media/download", name="ajax_media_download")
    */
   public function ajaxMediaDownloadAction(Request $request)
   {

        $em = $this->container->get("doctrine.orm.default_entity_manager");
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        $filename = pathinfo($uploadedFile->getClientOriginalName(),  PATHINFO_FILENAME);
        $assetsMediaPath = $this->container->getParameter('assets.path') . DIRECTORY_SEPARATOR . $this->container->getParameter('assets.media.folder');

        $filesystem = new Filesystem();
        if(!$filesystem->exists($assetsMediaPath . DIRECTORY_SEPARATOR . $filename . '.jpg')) {
            if(null !== $uploadedFile) {
                $media = new MediaObject();
                $media->setName($uploadedFile->getClientOriginalName());
                $media->setUrl($uploadedFile->getPath());
                $media->setDimensions([1920,1281]);
                $media->setOriginalFilename($uploadedFile->getClientOriginalName());
                $media->setFilename($uploadedFile->getClientOriginalName());
                $media->setEncodingFormat($uploadedFile->getMimeType());
                // $media->setContentSize([$uploadedFile->getSize()]);
                $media->setFile($uploadedFile);
                $em->persist($media);
                $em->flush();

                return new JsonResponse(array('success' => true));
            }
        }

        return new JsonResponse(array('success' => false));
   }

}
