<?php // src/Controller/DefaultController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Util\Inflector;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
// use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;

/**
 * @Route("/form")
 */
class SchemaController extends FOSRestController
{


     /**
     *
     * @Get("/{entity}", name="schema_form")
     * @Rest\View()
     * @Method({"GET","OPTIONS"})
     *
     **/
     public function getSchemaFormAction(Request $request)
     {

        $entityManager = $this->getDoctrine()->getManager();
        $annotationEntityService = $this->container->get('app.export.annotation_entity');

        /**
         * Premiere etape récupérer les metadatas et le repository
         */
        $entityName     =  ucfirst(Inflector::camelize($request->get('entity')));
        $classMetadata  = $entityManager->getClassMetadata('App:' . $entityName);
        $repository     = $entityManager->getRepository('App:' . $entityName);
        $annotations    = $annotationEntityService->getAnnotationsSchema($classMetadata);



        
        $schema = array();
        $schema['properties'] = $annotations;
        $schema['entityName'] = $entityName;


        if($request->get('id')) {
          $entityId = $request->get('id'); // pas nécéssaire si on récupere un enregistrement au hasard
          $entityNameFormType = 'App\Form\\' . $entityName . 'Type';
          $entity = $repository->findOneById($entityId);
          
          $form = $this->createForm($entityNameFormType, $entity);
    
          $formConfig = array();
          $formName = $form->getConfig();

          // ini_set('max_execution_time', 300);
          // $data = $form->getData();
          // $serializer = $this->container->get('jms_serializer');
          // $values = $serializer->toArray($data);
          // dump($values);

          $formOptions = $form->getConfig()->getOptions();
          foreach($form->getIterator() as $key => $item) {

            
            $formFieldOptions = $item->getConfig()->getOptions();
            // dump($formFieldOptions);
          }
          dump($formName);
          dump($formOptions);
          die;
        }
        
        
        
        
        // dump($metadata);

        


        /**
        * init JSON config files
        * formName
        * en
        */
        // $form = $this->createForm($entityNameFormType, $entity);
        // $formConfig = array();
        // $indexName = $form->getConfig()->getName();
        // $formConfig[$indexName] = [];
        // $formConfig[$indexName]['formName'] = $form->getConfig()->getOption('form_name');
        // $formConfig[$indexName]['entityName'] = $entityName;
        
        /**
         * get entity VALUES
         */
        // $values = $this->getFormConfigValues($entity);
        // $formConfig[$indexName]['values'] = $values;
        // $formConfig[$indexName]['values'] = null;
        

        /**
        * get params SCHEMA
        */
        // $schema = $this->getFormConfigSchema($form, $metadata, $repository);
        // $formConfig[$indexName]['schema'] = $schema;
        

        return $this->jsonRender($annotations, $status= true, $encode = true, $type = 'array');
    }

    private function getFormConfigValues($entity)
    {
      
        /**
        * get params VALUES
        */
        // if(!is_object($entity)) {
        //  $entity = array();
        //  foreach($schema as $key=>$value) {
        //      $entity[$key] = null;
        //  }
        // } else {
        //  $serializer = $this->container->get('jms_serializer');
        //  $entity = $serializer->toArray($entity);
        //  if(isset($entity['password'])) {
        //      $entity['password'] = '';
        //  }
        // }

         $serializer = $this->container->get('jms_serializer');
         dump($entity);die;
         $entity = $serializer->toArray($entity);
         if(isset($entity['password'])) {
             $entity['password'] = '';
         }

        return $entity;
    }

    private function getFormConfigSchema($form, $metadata, $repository)
    {
        $annotationReader = new AnnotationReader();
        $annotations = array();
        foreach($metadata->fieldMappings as $field) {
          $reflectionProperty = new \ReflectionProperty($repository->getClassName(), $field['fieldName']);
          $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
          $indexName = Inflector::tableize($reflectionProperty->getName());
            $properties = array();

            if('article_body' == $indexName) {
          dump($reflectionProperty);  
          dump($propertyAnnotations);  
          die;
          }
          foreach($propertyAnnotations as $property) {
              $reflectionClass = new \ReflectionClass($property);
              $properties[strtolower($reflectionClass->getShortName())] = $property;
          }
        
          
          
          $annotations[$indexName] = $properties;
        }
 
        $schema = array();
        $schema['fields'] = array();
        $schema['orders'] = array();
        $schema['options'] = $form->getConfig()->getOptions();
        // dump($form);die;
        foreach($form->getIterator() as $key => $item) {

            $properties = array();
            $options = $item->getConfig()->getOptions();
            $type = $item->getConfig()->getType()->getInnerType();
            $reflectionClass = new \ReflectionClass($type);
            $index = Inflector::tableize($key);
          
            /**
            * SPECIF PROCESS BETWEEN WITH
            * MAPPED FIELD AND NON MAPPED FIELD
            */
            if(isset($annotations[$index]['column'])) {
                /**
                * MAPPED FIELD
                */
                // $annotations[$key]['column']->value = (isset($entity[$key])? $entity[$key]: null);
                // $annotations[$key]['column']->type = $reflectionClass->getShortName();

                $properties = $annotations[$index];
            } else {
                /**
                * NON MAPPED FIELD
                */
                $property = array();
                $property[$key]['column'] = new \stdClass();
                $property[$key]['column']->type = $reflectionClass->getShortName();
                if(!isset($property[$key]['column']->name)) {
                 $property[$key]['column']->name = $key;
                }
                $properties = $property[$key];
            }
            $schema['fields'][$key]['properties'] = $properties;
            $schema['fields'][$key]['options'] = $options;
        }
// die;

        return $schema;
    }

    public function jsonRender($content, $status= true, $encode = true, $type = 'array')
    {
        $jsonContent = $content;
        if ($encode) {
            if ($type == 'array') {
                $jsonContent = (array) $jsonContent;
            } elseif ($type == 'entities') {

                $jsonContent = $this->serializeEntities($jsonContent, true);
            }
        } else {
        	$jsonContent = json_decode($jsonContent);
        }

        $response = new JsonResponse();
        $response->setContent(json_encode($content));
        // $response->headers->replace($this->headers);

        return  $response;

    }

    protected function serializeEntities($entities, $array = false)
     {
     	$jsonContent = $this->serializer->serialize($entities, 'json');
     	if($array) {
     		$jsonContent = json_decode($jsonContent);
     	}

     	return $jsonContent;
     }
}
