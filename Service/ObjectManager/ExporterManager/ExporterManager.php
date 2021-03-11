<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager\ExporterManager;

use RuntimeException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Maxtoan\ToolsBundle\Service\ObjectManager\ExporterManager\Adapter\ExporterAdapterInterface;
use Maxtoan\ToolsBundle\Service\ObjectManager\DocumentManager\DocumentManager;
use Maxtoan\ToolsBundle\Model\ObjectManager\ExporterManager\ChainModel;
use Maxtoan\ToolsBundle\Service\ObjectManager\TraitConfigure;
use Maxtoan\ToolsBundle\Form\ObjectManager\ExporterManager\DocumentsType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormInterface;
use Maxtoan\ToolsBundle\Service\ObjectManager\ConfigureInterface;

/**
 * Servicio para generar y exportar documentos PDF, XLS, DOC, TXT de los modulos
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ExporterManager implements ConfigureInterface
{
    use TraitConfigure;
    use ContainerAwareTrait;

    /**
     * Modelos disponibles para exportar
     * @var ChainModel ChainModel
     */
    private $chainModels;
    
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    /**
     * Adaptador para buscar en bases de datos
     * @var ExporterAdapterInterface
     */
    private $adapter;
    
    /**
     * @var DocumentManager 
     */
    private $documentManager;

    /**
     * Parametros que necesita la vista al renderizarse
     * @var array
     */
    private $parametersToView = [];
    
    public function __construct(DocumentManager $documentManager,array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "template" => "",
            "adapter" => "",
            "chaines" => []
        ]);
        $resolver->setRequired(["debug"]);
        $this->options = $resolver->resolve($options);
        $this->chainModels = [];
        $this->documentManager = $documentManager;
    }
    
    public function configure($objectId, $objectType,array $options = [])
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        $this->documentManager->configure($objectId, $objectType, $options);
        return $this;
    }
    
    /**
     * Establece el adaptador a usar para consultas
     * @param ExporterAdapterInterface $adapter
     * @return $this
     * @required
     */
    public function setAdapter(ExporterAdapterInterface $adapter) 
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * Retorna una opcion
     * @param type $name
     * @return type
     * @throws InvalidArgumentException
     */
    public function getOption($name)
    {
        if(!isset($this->options[$name])){
            throw new InvalidArgumentException(sprintf("The option name '%s' is invalid, available are %s.",$name, implode(",",array_keys($this->options))));
        }
        return $this->options[$name];
    }
    
    /**
     * Agrega un modelo de exportacion
     * @param ChainModel $chainModel
     * @throws InvalidArgumentException
     */
    public function addChainModel(array $chain = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(["chain","class","templates"]);
        $chain = $resolver->resolve($chain);
        
        $chainModel = new ChainModel($chain["chain"],$chain["class"]);
        $chainModel->setObjectId($this->objectId);
        $chainModel->addTemplates($chain["templates"]);
        if(isset($this->chainModels[$chainModel->getObjectType()])){
          throw new InvalidArgumentException(sprintf("The chain model to '%s' is already added, please add you model to tag '%s'",$chainModel->getClassName(),$chainModel->getClassName())); 
        }
        $this->chainModels[$chainModel->getObjectType()] = $chainModel;
    }
    
    /**
     * Retorna un modelo de exportacion
     * @param type $id
     * @return ChainModel
     * @throws InvalidArgumentException
     */
    protected function getChainModel($id)
    {
        if(!isset($this->chainModels[$id])){
           throw new InvalidArgumentException(sprintf("The chain model is not added or the id '%s' is invalid.",$id)); 
        }
        $this->chainModels[$id]->setObjectId($this->objectId);
        return $this->chainModels[$id];
    }
    
    /**
     * Genera un documento de un modulo
     * @param type $objectType
     * @param string $name Nombre del documento pre-definido
     * @param array $options
     * @return File El archivo generado
     * @throws RuntimeException
     */
    public function generate($name,array $options = [],$overwrite = false)
    {
        $chainModel = $this->resolveChainModel($options);
        
        $modelDocument = $chainModel->getModel($name);
        
        $fileName = $modelDocument->getFileName();
        if(isset($options["fileName"]) && !empty($options["fileName"])){
            $fileName = $options["fileName"];
            $fileName .= ".".$modelDocument->getFormat();
            unset($options["fileName"]);
        }

        $templateService = $this->container->get('maxtoan_tools.template_service');
        $pathFileOut = $templateService->compile($modelDocument->getId(),$options["data"]);
        
        if(empty($fileName)){
            $fileName = $pathFileOut->getFileName();
        }

        if(empty($fileName)){
            throw new RuntimeException(sprintf("The fileName can not be empty."));
        }

        if($pathFileOut === null){
            throw new RuntimeException(sprintf("Failed to generate document '%s' with name '%s'",$this->objectType,$name));
        }

        if(!is_readable($pathFileOut)){
            throw new RuntimeException(sprintf("Failed to generate document '%s' with name '%s'. File '%s' is not readable.",$this->objectType,$name,$pathFileOut));
        }

        $this->documentManager->folder("generated");
        $file = new File($pathFileOut);
        $file = $this->documentManager->upload($file,[
            "overwrite" => $overwrite,
            "name" => $fileName,
        ]);

        return $file;
    }
    
    /**
     * Genera un documento a partir de un id
     * @param type $id
     * @param type $objectType
     * @param type $name
     * @param array $options
     * @return File La ruta del archivo generado
     * @throws RuntimeException
     */
    public function generateWithSource($name,array $options = [],$overwrite = false)
    {
        if(!$this->adapter){
            throw new RuntimeException(sprintf("The adapter must be set for enable this feature."));
        }

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "fileName" => null,
            "request" => null
        ]);
        $options = $resolver->resolve($options);

        $chainModel = $this->getChainModel($this->objectType);
        $className = $chainModel->getClassName();
        $entity = $this->adapter->find($chainModel->getClassName(),$this->objectId);
        if(!$entity){
            throw new RuntimeException(sprintf("The source '%s' with '%s' not found.",$className,$this->objectId));
        }
        $options["data"]["variables"]["entity"] = $entity;
        $options["data"]["variables"]["request"] = $options["request"];
        
        return $this->generate($name,$options,$overwrite);
    }

    /**
     * Genera un documento a partir de un id
     * @param type $id
     * @param type $objectType
     * @param type $file
     * @param array $options
     * @return File La ruta del archivo generado
     * @throws RuntimeException
     */
    public function uploadWithSource($file,array $options = [],$overwrite = false)
    {
        $chainModel = $this->getChainModel($this->objectType);

        $fileName = $file->getClientOriginalName();
        
        $this->documentManager->folder("uploaded");
        $file = $this->documentManager->upload($file,[
            "overwrite" => $overwrite,
            "name" => $fileName,
        ]);
        return $file;
    }
    
    /**
     * Resuelve el modelo de exportacion y le establece los parametros
     * @param type $objectType
     * @param array $options
     * @return ChainModel
     */
    public function resolveChainModel(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "data" => [],
            "fileName" => null,
            "request" => null
        ]);
        $resolver->setAllowedTypes("data","array");
        $options = $resolver->resolve($options);
        $chainModel = $this->getChainModel($this->objectType);
        return $chainModel;
    }

    /**
     * Renderiza el modulo para generar archivos del moduloe
     * @param $entity
     * @param type $idChain
     * @return type
     */
    public function renderFiles($entity)
    {
        $chain = $this->getChainModel($this->objectType);
        $choices = [];
        $models = $chain->getModels();
        foreach ($models as $model) {
            $choices[$this->trans($model->getName(),[],"labels") . " [" . strtoupper($model->getFormat()) . "]"] = $model->getName();
        }

        $form = $this->createForm(DocumentsType::class, $choices);
        $this->parametersToView["parameters_to_route"]["_conf"]["folder"] = "generated";
        $this->parametersToView["parameters_to_route"]["_conf"]["objectId"] = $this->objectId;
        $this->parametersToView["parameters_to_route"]["_conf"]["objectType"] = $this->objectType;
        $this->parametersToView["parameters_to_route"]["_conf"]["returnUrl"] = "";
        return $this->container->get('templating')->render($this->options["template"],[
                'chain' => $chain,
                'entity' => $entity,
                'form' => $form->createView(),
                'parametersToView' => $this->parametersToView,
            ]);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @final
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * Traducciones
     *
     * @param   $id
     * @param   array  $parameters
     * @param   array  $domain
     */
    public function trans($id,array $parameters = array(), $domain = "")
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }
    
    /**
     * @return DocumentManager
     */
    public function documents($folder = "generated")
    {
        $this->documentManager->folder($folder);
        return $this->documentManager;
    }
}