<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MediaObject;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Commande pour gérer les images inutilisées dans le système
 *
 * Cas d'usage:
 * 
 * - Lister toutes les images non utilisées dans le système:
 *   php bin/console app:manage-unused-images --list
 * 
 * - Vérifier l'existence des fichiers physiques:
 *   php bin/console app:manage-unused-images --check
 * 
 * - Supprimer les images inutilisées (avec confirmation):
 *   php bin/console app:manage-unused-images --delete
 * 
 * - Supprimer les images inutilisées sans confirmation:
 *   php bin/console app:manage-unused-images --delete --force
 * 
 * - Combiner les options:
 *   php bin/console app:manage-unused-images --list --check
 */
class ManageUnusedImagesCommand extends Command
{
    protected static $defaultName = 'app:manage-unused-images';
    
    private $em;
    private $container;
    private $filesystem;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container, Filesystem $filesystem)
    {
        $this->em = $em;
        $this->container = $container;
        $this->filesystem = $filesystem;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Gère les images inutilisées dans le système')
            ->setHelp('Cette commande permet de lister, vérifier et supprimer les images inutilisées')
            ->addOption(
                'list',
                'l',
                InputOption::VALUE_NONE,
                'Liste toutes les images inutilisées'
            )
            ->addOption(
                'check',
                'c',
                InputOption::VALUE_NONE,
                'Vérifie si les fichiers physiques existent pour les images inutilisées'
            )
            ->addOption(
                'delete',
                'd',
                InputOption::VALUE_NONE,
                'Supprime les images inutilisées (avec confirmation)'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force la suppression sans confirmation (à utiliser avec --delete)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Gestion des images inutilisées');

        // Options
        $listOption = $input->getOption('list');
        $checkOption = $input->getOption('check');
        $deleteOption = $input->getOption('delete');
        $forceOption = $input->getOption('force');

        // Si aucune option n'est spécifiée, afficher l'aide
        if (!$listOption && !$checkOption && !$deleteOption) {
            $io->warning('Aucune option spécifiée. Utilisez --list, --check ou --delete.');
            $io->section('Options disponibles:');
            $io->listing([
                '--list (-l): Liste toutes les images inutilisées',
                '--check (-c): Vérifie si les fichiers physiques existent pour les images inutilisées',
                '--delete (-d): Supprime les images inutilisées (avec confirmation)',
                '--force (-f): Force la suppression sans confirmation (à utiliser avec --delete)'
            ]);
            return 0; // 0 = success
        }

        // Récupérer les images utilisées et non utilisées
        [$allImages, $usedImages, $unusedImages] = $this->getImagesStatus();
        $totalImages = count($allImages);
        $usedImagesCount = count($usedImages);
        $unusedImagesCount = count($unusedImages);
        
        // Option: Lister les images inutilisées
        if ($listOption) {
            $this->listUnusedImages($io, $unusedImages);
        }
        
        // Afficher les statistiques (toujours en dernier)
        $io->section('Statistiques');
        $io->table(
            ['Type', 'Nombre'],
            [
                ['Images totales', $totalImages],
                ['Images utilisées', $usedImagesCount],
                ['Images non utilisées', $unusedImagesCount]
            ]
        );

        // Option: Vérifier les fichiers physiques
        if ($checkOption) {
            $this->checkPhysicalFiles($io, $unusedImages);
        }

        // Option: Supprimer les images inutilisées
        if ($deleteOption) {
            $this->deleteUnusedImages($io, $unusedImages, $forceOption);
        }

        return 0; // 0 = success
    }

    /**
     * Récupère le statut des images (utilisées/non utilisées)
     */
    private function getImagesStatus(): array
    {
        // 1. Récupérer toutes les images
        $allImages = $this->em->getRepository(MediaObject::class)->findAll();
        
        // 2. Requête pour trouver les images utilisées comme primaryImage ou secondaryImage dans les hébergements
        $qb = $this->em->createQueryBuilder();
        $qb->select('DISTINCT m.id')
           ->from('App:MediaObject', 'm')
           ->leftJoin('App:Accommodation', 'a', 'WITH', 'a.primaryImage = m.id OR a.secondaryImage = m.id');
        
        $usedImagesIds = array_column($qb->getQuery()->getScalarResult(), 'id');
        
        // 3. Trouver les images utilisées et non utilisées
        $usedImages = [];
        $unusedImages = [];
        
        foreach ($allImages as $image) {
            if (in_array($image->getId(), $usedImagesIds)) {
                $usedImages[] = $image;
            } else {
                $unusedImages[] = $image;
            }
        }
        
        return [$allImages, $usedImages, $unusedImages];
    }

    /**
     * Liste les images inutilisées
     */
    private function listUnusedImages(SymfonyStyle $io, array $unusedImages): void
    {
        $io->section('Images non utilisées');
        
        if (empty($unusedImages)) {
            $io->success('Aucune image inutilisée trouvée.');
            return;
        }
        
        $unusedImagesTable = [];
        foreach ($unusedImages as $image) {
            $unusedImagesTable[] = [
                $image->getId(),
                $image->getFilename(),
                $image->getOriginalFilename(),
                $image->getDateModified() ? $image->getDateModified()->format('Y-m-d H:i:s') : 'N/A'
            ];
        }
        
        $io->table(['ID', 'Nom fichier', 'Nom original', 'Dernière modification'], $unusedImagesTable);
    }

    /**
     * Vérifie si les fichiers physiques existent pour les images inutilisées
     */
    private function checkPhysicalFiles(SymfonyStyle $io, array $unusedImages): void
    {
        $io->section('Vérification des fichiers physiques');
        
        if (empty($unusedImages)) {
            $io->success('Aucune image inutilisée à vérifier.');
            return;
        }
        
        $assetsMediaFolder = $this->container->getParameter('assets.media.folder');
        $directoryResources = $this->container->getParameter('DIRECTORY_RESOURCES');
        $mediaPath = $directoryResources . '/' . $assetsMediaFolder;
        
        $existingFiles = [];
        $missingFiles = [];
        
        foreach ($unusedImages as $image) {
            $filename = $image->getFilename();
            $filePath = $mediaPath . '/' . $filename;
            
            if ($this->filesystem->exists($filePath)) {
                $fileSize = filesize($filePath);
                $existingFiles[] = [
                    $image->getId(),
                    $filename,
                    $this->formatBytes($fileSize),
                    $filePath
                ];
            } else {
                $missingFiles[] = [
                    $image->getId(),
                    $filename,
                    'N/A',
                    $filePath
                ];
            }
        }
        
        $io->text('Fichiers physiques existants: ' . count($existingFiles));
        if (!empty($existingFiles)) {
            $io->table(['ID', 'Nom fichier', 'Taille', 'Chemin'], $existingFiles);
        }
        
        $io->text('Fichiers physiques manquants: ' . count($missingFiles));
        if (!empty($missingFiles)) {
            $io->table(['ID', 'Nom fichier', 'Taille', 'Chemin attendu'], $missingFiles);
        }
    }

    /**
     * Supprime les images inutilisées
     */
    private function deleteUnusedImages(SymfonyStyle $io, array $unusedImages, bool $force): void
    {
        $io->section('Suppression des images inutilisées');
        
        if (empty($unusedImages)) {
            $io->success('Aucune image inutilisée à supprimer.');
            return;
        }
        
        $assetsMediaFolder = $this->container->getParameter('assets.media.folder');
        $directoryResources = $this->container->getParameter('DIRECTORY_RESOURCES');
        $mediaPath = $directoryResources . '/' . $assetsMediaFolder;
        
        // Confirmation si --force n'est pas utilisé
        if (!$force) {
            $confirmation = $io->confirm(
                sprintf('Êtes-vous sûr de vouloir supprimer %d images inutilisées ?', count($unusedImages)),
                false
            );
            
            if (!$confirmation) {
                $io->warning('Opération annulée.');
                return;
            }
        }
        
        $deletedFiles = 0;
        $deletedEntities = 0;
        $errors = [];
        
        // Supprimer les images
        foreach ($unusedImages as $image) {
            $filename = $image->getFilename();
            $filePath = $mediaPath . '/' . $filename;
            
            // Supprimer le fichier physique s'il existe
            if ($this->filesystem->exists($filePath)) {
                try {
                    $this->filesystem->remove($filePath);
                    $deletedFiles++;
                } catch (\Exception $e) {
                    $errors[] = sprintf('Erreur lors de la suppression du fichier %s: %s', $filePath, $e->getMessage());
                }
            }
            
            // Supprimer l'entité de la base de données
            try {
                $this->em->remove($image);
                $deletedEntities++;
            } catch (\Exception $e) {
                $errors[] = sprintf('Erreur lors de la suppression de l\'entité ID %d: %s', $image->getId(), $e->getMessage());
            }
        }
        
        // Persister les changements
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            $errors[] = sprintf('Erreur lors de la persistance des changements: %s', $e->getMessage());
        }
        
        // Afficher les résultats
        $io->success(sprintf('%d fichiers physiques supprimés', $deletedFiles));
        $io->success(sprintf('%d entités supprimées de la base de données', $deletedEntities));
        
        if (!empty($errors)) {
            $io->error('Des erreurs sont survenues:');
            $io->listing($errors);
        }
    }

    /**
     * Formate la taille en bytes en format lisible
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
