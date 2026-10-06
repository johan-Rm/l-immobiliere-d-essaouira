<?php

namespace App\Service\Export;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Util\Inflector;
use Doctrine\Common\Annotations\AnnotationReader;


class AnnotationEntity
{
    private $container;
    private $em;


    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function getAnnotationsSchema($classMetadata)
    {
      
        $annotationReader = new AnnotationReader();
        $annotations = array();
        foreach($classMetadata->fieldMappings as $field) {
          dump($field);
          $reflectionProperty = new \ReflectionProperty($classMetadata->name, $field['fieldName']);
          $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
          $indexName = Inflector::tableize($reflectionProperty->getName());
            $properties = array();

        
          foreach($propertyAnnotations as $property) {
              $reflectionClass = new \ReflectionClass($property);
              $properties[strtolower($reflectionClass->getShortName())] = $property;
          }

          $annotations[$indexName] = $properties;
        }
        return $annotations;
    }

    public function getContraintsByField($className, $field)
    {
        $metadata = $this->container
          ->get('validator')
          ->getMetadataFor($className);
        $propertyMetadata = $metadata->getPropertyMetadata($field);
        
        return $propertyMetadata[0]->constraints;
    }

}
