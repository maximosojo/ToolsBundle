<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager\ExporterManager;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Maxtoan\ToolsBundle\Model\ObjectManager\ExporterManager\TemplateInterface;
use RuntimeException;

/**
 * Servicio que renderiza y compila plantillas para generar documentos
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TemplateService
{
    /**
     * Motores disponibles
     * @var EngineInterface
     */
    private $engines;

    /**
     * Adaptador para buscar las plantillas
     * @var AdapterInterface
     */
    private $adapters;
    
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $resolver->setRequired([
            "env", 
        ]
        );
        $this->adapters = [];
    }

    /**
     * Retorna todos los moteres
     * @return array
     */
    public function getDefinedEngines()
    {
        $engines = [];
        foreach ($this->engines as $engine) {
            $engines[sprintf("[%s] %s - %s", $engine->getExtension(), $engine->getLanguage(), $engine->getDescription())] = $engine->getId();
        }
        return $engines;
    }
    
    /**
     * Añade un adaptador
     * @param \Maxtoan\ToolsBundle\Service\ObjectManager\ExporterManager\AdapterInterface $adapter
     * @throws RuntimeException
     */
    public function addAdapter(AdapterInterface $adapter)
    {
        if(isset($this->adapters[$adapter->getExtension()])){
            throw new RuntimeException(sprintf("The adapter with extension '%s' is already added.",$adapter->getExtension()));
        }
        $this->adapters[$adapter->getExtension()] = $adapter;
    }

    /**
     * Añade un motor
     * @param \Tecnoready\Common\Service\Template\EngineInterface $adapter
     * @throws RuntimeException
     */
    public function addEngine(EngineInterface $adapter)
    {
        if (isset($this->engines[$adapter->getId()])) {
            throw new RuntimeException(sprintf("The adapter with name '%s' is already added.", $adapter->getExtension()));
        }
        $this->engines[$adapter->getId()] = $adapter;

        return $this;
    }

    /**
     * Busca un motor
     * @param string $name Nombre del motor
     * @return EngineInterface
     * @throws RuntimeException
     */
    public function getEngine($name)
    {
        if (!isset($this->engines[$name])) {
            throw new RuntimeException(sprintf("The adapter with extension '%s' is not added.", $name));
        }
        return $this->engines[$name];
    }
    
    /**
     * Metodo que renderiza el template y retona el string
     * @param type $template
     * @param array $variables
     * @return string
     */
    public function render(TemplateInterface $template, array $variables)
    {
        $adapter = $this->getEngine($template->getTypeTemplate());
        return $adapter->render($template, $variables);
    }

    /**
     * Busca la plantilla en la base de datos u otra fuente y la compila
     * @param type $id
     * @param type $filename
     * @param array $variables
     * @param array $parameters
     * @return File
     */
    public function compile($id,string $filename = null, array $variables = [], array $parameters = [])
    {
        $template = $this->adapter->find($id);
        return $this->compileTemplate($template, $filename, $variables, $parameters);
    }

    /**
     * Toma el string generado y crea el archivo PDF,TXT
     * @param type $string
     * @param array $parameters
     * @return File
     */
    public function compileTemplate(TemplateInterface $template, string $path = null, array $variables = [], array $parameters = []) :?File
    {
        $adapter = $this->getEngine($template->getTypeTemplate());

        //Todas las variables son requeridas
        $variablesToCheck = $template->getVariables();
       
        if (count($variablesToCheck) > 0) {
            $diff = [];
            foreach ($variablesToCheck as $variable) {
                $key = $variable->getName();
                if (!array_key_exists($key, $variables)) {
                    $diff[] = $key;
                }
            }
            if (count($diff) > 0) {
                die;
                throw new RuntimeException(sprintf("Las variables '%s' son requeridos.", implode(", ", $diff)));
            }
        }

        //Verificar los parametros obligatorios
        $parametersToCheck = $template->getParameters();
        if (count($parametersToCheck) > 0) {
            $diff = [];
            foreach ($parametersToCheck as $parameter) {
                if($parameter->getRequired() === false){
                    continue;
                }
                $key = $parameter->getName();
                if (!array_key_exists($key, $parameters)) {
                    $diff[] = $key;
                }
            }
            if (count($diff) > 0) {
                throw new RuntimeException(sprintf("Los parametros '%s' son requeridos.", implode(", ", $diff)));
            }
        }
        $string = $this->render($template, $variables);
        
        if($path === null){
            $path = tempnam(null,"tpl");
        }
        $fileNameCalculated = $adapter->getFileName();
        //Si durante la renderizacion se calculo el nombre entonces se toma ese como final
        if(!empty($fileNameCalculated)){
            $path = dirname($path).DIRECTORY_SEPARATOR.$fileNameCalculated;
        }
        $adapter->compile($path, $string, $parameters);
        $file = null;
        if(is_file($path)){
            $file = new File($path);
        }
        return $file;
    }
}
