<?php

namespace App\Listeners;

use Twig\Environment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Cocur\Slugify\Slugify;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Process\Process;
use Liip\ImagineBundle\Service\FilterService;


/**
 * AbstractListener
 */
class AbstractListener
{
    private $knpSnappy;
    private $orm;
    private $assetsPdfPath;
    private $templating;
    private $cdnHost;
    private $container;
    private $translator;
    private $imagine;
    private $assetsMediaFolder;
    private $cacheManager;


    public function __construct(Pdf $knpSnappy, EntityManagerInterface $orm, ContainerInterface $container, Environment $templating, TranslatorInterface $translator, FilterService $imagine, CacheManager $cacheManager)
    {
        $this->knpSnappy = $knpSnappy;
        $this->orm = $orm;
        $this->templating = $templating;
        $this->container = $container;
        $this->translator = $translator;
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;


        $this->assetsPdfPath = $this->container->getParameter('assets.pdf.path');
        $this->assetsMediaFolder = $this->container->getParameter('assets.media.folder');
        $this->cdnHost = $this->container->getParameter('cdn.host');
    }
}
