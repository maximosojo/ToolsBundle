<?php

namespace Maximosojo\ToolsBundle\Service\ObjectManager\HistoryManager\Adapter;

use Maximosojo\ToolsBundle\Service\ObjectManager\HistoryManager\HistoryInterface;
use Doctrine\ORM\EntityManager;
use Pagerfanta\Adapter\DoctrineORMAdapter as Adapter;
use Maximosojo\ToolsBundle\Model\Paginator\Paginator;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;

/**
 * Adaptador de doctrine2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements HistoryAdapterInterface
{
    use \Maximosojo\ToolsBundle\Service\ObjectManager\TraitConfigure;
    
    /**
     * @var string
     */
    private $className;
    
    /**
     * @var EntityManager
     */
    private $em;
    
    public function __construct($className,EntityManager $em)
    {
        $this->className = $className;
        $this->em = $em;
    }
    
    public function create(array $options = [])
    {
        $browser = new Browser();
        $os = new Os();

        $entity = new $this->className;
        $entity->setEventName($options["eventName"]);
        $entity->setType($options["type"]);
        $entity->setExtra($options["extra"]);
        $entity->setDescription($options["description"]);
        $entity->setObjectId($options["objectId"]);
        $entity->setObjectType($options["objectType"]);
        $entity->setUser($options["user"]);
        $entity->setUserAgent($this->getUserAgent());
        $entity->setBrowser($browser->getName());
        $entity->setOs($os->getName());
        $entity->setMobile(true);
        $entity->setBrowserVersion($browser->getVersion());
        $entity->setCreatedAt(new \DateTime());
        $this->em->persist($entity);
        return $this->em->flush();
    }
    
    public function delete(HistoryInterface $entity)
    {
        $this->em->remove($entity);
        return $this->em->flush();
    }
    
    public function find($id)
    {
        return $this->em->find($id);
    }
    
    /**
     * @param array $criteria
     * @param array $sortBy
     * @return Paginator
     */
    public function getPaginator(array $criteria = [],array $sortBy = [])
    {
        $repository = $this->em->getRepository($this->className);
        $qb = $repository->createQueryBuilder("e");
        $qb
            ->orderBy("e.createdAt","DESC")
            ;

        if (isset($criteria["objectId"])) {
            $qb
                ->andWhere("e.objectId = :objectId")
                ->setParameter("objectId",$criteria["objectId"])
            ;
        }

        if (isset($criteria["objectType"])) {
            $qb
                ->andWhere("e.objectType = :objectType")
                ->setParameter("objectType",$criteria["objectType"])
            ;
        }

        if (isset($criteria["user"])) {
            $qb
                ->andWhere("e.user = :user")
                ->setParameter("user",$criteria["user"])
            ;
        }

        $pagerfanta = new Paginator(new Adapter($qb));
        $pagerfanta->setDefaultFormat(Paginator::FORMAT_ARRAY_STANDARD);
        return $pagerfanta;
    }

    /**
     * Obtener el UserAgent
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return UserAgent
     */
    public function getUserAgent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        // } elseif (\Maxtoan\Common\Util\AppUtil::isCommandLineInterface()) {
            // $userAgent = "cli-user-agent";
        }

        if (empty($userAgent)) {
            $userAgent = "unknown";
        }

        return $userAgent;
    }
}
