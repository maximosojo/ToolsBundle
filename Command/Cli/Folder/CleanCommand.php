<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/common
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Command\Cli\Folder;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Maxtoan\Common\Filesystem\Folder;

/**
 * CleanCommand
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class CleanCommand extends \Maxtoan\ToolsBundle\Component\Console\Command\Command
{
	/* @var InputInterface $output */
    protected $input;

    /* @var OutputInterface $output */
    protected $output;

	/**
     * @var array
     */
    protected $paths_to_remove;

	/**
     * Configuración
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    protected function configure()
    {
        $this
            ->setName('mtools:folder:clean')
            ->setDescription('Run clean folders project.')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setupConsole($input, $output);

        if ($this->getContainer()->getParameter("maxtoan_tools.command.folder_clean_clean_paths")) {
            $this->paths_to_remove = $this->getContainer()->getParameter("maxtoan_tools.command.folder_clean_clean_paths");
            $this->cleanPaths();
        }
    }

    private function cleanPaths()
    {
        $this->output->writeln('');
        $this->output->writeln('<red>DELETING</red>');
        $anything = false;
        foreach ($this->paths_to_remove as $path) {
        	$path = ROOT_DIR . $path;
            if (is_dir($path) && Folder::delete($path)) {
                $anything = true;
                $this->output->writeln('<red>dir:  </red>' . $path);
            } elseif (is_file($path) && @unlink($path)) {
                $anything = true;
                $this->output->writeln('<red>file: </red>' . $path);
            }
        }

        if (!$anything) {
            $this->output->writeln('');
            $this->output->writeln('<green>Nothing to clean...</green>');
        }
    }

    /**
     * Set colors style definition for the formatter.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function setupConsole(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->output->getFormatter()->setStyle('normal', new OutputFormatterStyle('white'));
        $this->output->getFormatter()->setStyle('yellow', new OutputFormatterStyle('yellow', null, ['bold']));
        $this->output->getFormatter()->setStyle('red', new OutputFormatterStyle('red', null, ['bold']));
        $this->output->getFormatter()->setStyle('cyan', new OutputFormatterStyle('cyan', null, ['bold']));
        $this->output->getFormatter()->setStyle('green', new OutputFormatterStyle('green', null, ['bold']));
        $this->output->getFormatter()->setStyle('magenta', new OutputFormatterStyle('magenta', null, ['bold']));
        $this->output->getFormatter()->setStyle('white', new OutputFormatterStyle('white', null, ['bold']));
    }    
}