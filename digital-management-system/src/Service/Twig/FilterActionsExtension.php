<?php
namespace App\Service\Twig;

use HelperBundle\Tool\Shortcuts as Sf;
use HelperBundle\Tool\Store;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;


class FilterActionsExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'filter_admin_actions',
                [$this, 'filterActions']
            )
        ];
    }

    public function filterActions(array $itemActions, $item)
    {
        if (method_exists($item, 'filterActions')) {
            $itemActions = $item->filterActions($itemActions, $item);
        }
        return array_diff_key($itemActions, array_flip(
            $GLOBALS['kernel']->getContainer()->getParameter('global_actions')
        ));
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('isBuild', [$this, 'isUnderBuild']),
        ];
    }

    public function isUnderBuild()
    {
        return false;

        $path = $GLOBALS['kernel']->getContainer()->getParameter('view.update.json_data.path');
        $filesystem = new Filesystem();
        if(!$filesystem->exists(
            $path
        )) {
            $filesystem->mkdir(
                $path
            );
        }
        $finder = new Finder();
        $finder->files()->in($path);
        if ($finder->hasResults()) {

            return true;
        }

        $path = $GLOBALS['kernel']->getContainer()->getParameter('view.update.media_images.path');
        $filesystem = new Filesystem();
        if(!$filesystem->exists(
            $path
        )) {
            $filesystem->mkdir(
                $path
            );
        }
        $finder = new Finder();
        $finder->files()->in($path);
        if ($finder->hasResults()) {

            return true;
        }

        $path = $GLOBALS['kernel']->getContainer()->getParameter('view.update.pdf_files.path');
        $filesystem = new Filesystem();
        if(!$filesystem->exists(
            $path
        )) {
            $filesystem->mkdir(
                $path
            );
        }
        $finder = new Finder();
        $finder->files()->in($path);
        if ($finder->hasResults()) {

            return true;
        }

        $path = $GLOBALS['kernel']->getContainer()->getParameter('view.update.nuxt_routes.path');
        $filesystem = new Filesystem();
        if(!$filesystem->exists(
            $path
        )) {
            $filesystem->mkdir(
                $path
            );
        }
        $finder = new Finder();
        $finder->files()->in($path);
        if (!$finder->hasResults()) {

            return true;
        }

        return false;
    }
}
