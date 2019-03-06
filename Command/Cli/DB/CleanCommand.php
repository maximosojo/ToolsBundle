<?php

/*
 * This file is part of the Grupo Farmaingenio C.A. - J406111090 package.
 * 
 * (c) www.tconin.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Command\Cli\DB;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Comando para limpieza de base de datos
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class CleanCommand extends \Maxtoan\ToolsBundle\Component\Console\Command\Command
{
    /**
     * Configuración
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Config
     */
    protected function configure()
    {
        $this
            ->setName('mtools:db:clean')
            ->setDescription('Run clean database for develop.')
            ->addOption(
                'force',
                    null,
                    InputOption::VALUE_NONE,
                    'Force execute the command.',
                    null
            )
            ->setHelp(
                <<<EOT
                    The <info>%command.name%</info>Clear database developer project.
<info>php %command.full_name%</info>
EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new \Symfony\Component\Console\Style\SymfonyStyle($input, $output);

        $force = $input->getOption("force");

        $this->io->caution("This command can not be execute in prod.");
        if(!$force){
            $this->io->comment("Use option --force for excecute the command.");
            return;
        }

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();

        if ($em->getFilters()->isEnabled('softdeleteable')) {
            $em->getFilters()->disable('softdeleteable');
        }

        // Truncate
        if ($this->getContainer()->getParameter("maxtoan_tools.command.db_clean_truncate_entities")) {
            $toTruncate = $this->getContainer()->getParameter("maxtoan_tools.command.db_clean_truncate_entities");
            foreach ($toTruncate as $tableName) {
                $this->truncate($tableName,$output,$dbPlatform,$connection);
            }
        }
        
        // Delete
        if ($this->getContainer()->getParameter("maxtoan_tools.command.db_clean_delete_entities")) {
            $toDelete = $this->getContainer()->getParameter("maxtoan_tools.command.db_clean_delete_entities");
            foreach ($toDelete as $className) {
                $this->deleteRecords($className,$em,$output);
            }
        }

        // Tablas de auditoria
        $tables = $connection->getSchemaManager()->listTables();
        foreach ($tables as $table) {
            if(preg_match("/_audit/",$table->getName())){
                $this->truncate($table->getName(),$output,$dbPlatform,$connection);
            }
        }
    }

    /**
     * Delete records
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  String
     * @param  DoctrineManager
     * @param  String
     * @return Records
     */
    private function deleteRecords($className,$em,$output) 
    {
        $query = $em->createQuery(sprintf('SELECT COUNT(u.id) FROM %s u',$className));
        $count = $query->getSingleScalarResult();
        $this->io->writeln(sprintf(" Cleaning <info>'%s'</info> records in table entity <info>'%s'</info>...",$count,$className));
        $total = $count;
        $deleted = 0;
        $this->io->progressStart($total);
        $limit = 200;
        while ($count > 0)
        {
            $ids = $em->createQuery(sprintf('SELECT u.id FROM %s u',$className))->setMaxResults($limit)->getScalarResult();
            $idsF = [];
            foreach ($ids as $id) {
                $idsF[] = "'".$id["id"]."'";
            }
            $em->createQuery(sprintf("DELETE FROM ".$className." e WHERE e.id IN (%s)",implode(",", $idsF)))->execute();
            $deleted = $limit;
            $query = $em->createQuery('SELECT COUNT(u.id) FROM '.$className.' u');
            $count = $query->getSingleScalarResult();
            $em->clear();
            $this->io->progressAdvance($deleted);
        }
    }

    /**
     * Truncate
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  String
     * @param  String
     * @param  \Doctrine\DBAL\Platforms\MySqlPlatform
     * @param  String
     */
    private function truncate($tableName,$output,\Doctrine\DBAL\Platforms\MySqlPlatform $dbPlatform,$connection) 
    {
        $this->io->text(sprintf("Cleaning table <info>'%s'</info>...",$tableName));
        $q = $dbPlatform->getTruncateTableSql($tableName);
        $connection->executeUpdate($q);
    }

    /**
     * Drop
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  String
     * @param  String
     * @param  \Doctrine\DBAL\Platforms\MySqlPlatform
     * @param  String
     */
    private function drop($tableName,$output,\Doctrine\DBAL\Platforms\MySqlPlatform $dbPlatform,$connection) 
    {
        $this->io->text(sprintf("Drop table <info>'%s'</info>...",$tableName));
        $q = $dbPlatform->getDropTableSql($tableName);
        $connection->executeUpdate($q);
    }
}
