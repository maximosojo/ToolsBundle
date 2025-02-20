<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Command;

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
class DataBaseClearCommand extends \Maximosojo\ToolsBundle\Component\Console\Command\Command
{
    /**
     * Configuración
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Config
     */
    protected function configure()
    {
        $this
            ->setName('mtools:database:clear')
            ->setDescription('Run clear database for develop.')
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new \Symfony\Component\Console\Style\SymfonyStyle($input, $output);

        $force = $input->getOption("force");

        $this->io->caution("This command can not be execute in prod.");
        if(!$force){
            throw new RuntimeException('Use option --force for excecute the command.');
        }

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();

        if ($em->getFilters()->isEnabled('softdeleteable')) {
            $em->getFilters()->disable('softdeleteable');
        }

        // Truncate
        if ($this->getContainer()->getParameter("maximosojo_tools.command.db_clear_truncate_entities")) {
            $toTruncate = $this->getContainer()->getParameter("maximosojo_tools.command.db_clear_truncate_entities");
            foreach ($toTruncate as $tableName) {
                $this->truncate($tableName,$output,$dbPlatform,$connection);
            }
        }
        $this->io->newLine();
        
        // Delete
        if ($this->getContainer()->getParameter("maximosojo_tools.command.db_clear_delete_entities")) {
            $toDelete = $this->getContainer()->getParameter("maximosojo_tools.command.db_clear_delete_entities");
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

        return 0;
    }

    /**
     * Delete records
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  string
     * @param  DoctrineManager
     * @param  string
     * @return Records
     */
    private function deleteRecords($className,$em,$output) 
    {
        $query = $em->createQuery(sprintf('SELECT COUNT(u.id) FROM %s u',$className));
        $count = $query->getSingleScalarResult();
        $this->io->writeln(sprintf(" Clearing <info>'%s'</info> records in table entity <info>'%s'</info>...",$count,$className));
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
            $this->io->newLine();
        }
        $this->io->newLine();
    }

    /**
     * Truncate
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  string
     * @param  string
     * @param  \Doctrine\DBAL\Platforms\MySqlPlatform
     * @param  string
     */
    private function truncate($tableName,$output,\Doctrine\DBAL\Platforms\MySqlPlatform $dbPlatform,$connection) 
    {
        $this->io->text(sprintf("Clearing table <info>'%s'</info>...",$tableName));
        $q = $dbPlatform->getTruncateTableSql($tableName);
        $connection->executeUpdate($q);
    }

    /**
     * Drop
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  string
     * @param  string
     * @param  \Doctrine\DBAL\Platforms\MySqlPlatform
     * @param  string
     */
    private function drop($tableName,$output,\Doctrine\DBAL\Platforms\MySqlPlatform $dbPlatform,$connection) 
    {
        $this->io->text(sprintf("Drop table <info>'%s'</info>...",$tableName));
        $q = $dbPlatform->getDropTableSql($tableName);
        $connection->executeUpdate($q);
    }
}
