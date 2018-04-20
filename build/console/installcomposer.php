<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;

class installcomposerCommand extends Command {


	protected $templates_path = __DIR__ . "/templates/";
	protected $root_path = __DIR__ . "/../../";
	protected $replace = [];

	protected function configure() {

		$this
			// the name of the command (the part after "bin/console")
			->setName('composer:install')

			// the short description shown while running "php bin/console list"
			->setDescription('Install composer')

			// the full command description shown when running the command with
			// the "--help" option
			->setHelp('This command allows you to install composer...')

						 // configure an argument
			->addArgument('name', InputArgument::REQUIRED, 'name of composer file')
			;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$name = $input->getArgument("name");
                $composer = $this->root_path .'perspectives/composer'.$name.'.json';
                $maincomp = $this->root_path .'composer.json';
		if (file_exists($composer)) {
                                unlink($this->root_path .'vendor');
                                unlink($this->root_path .'composer.lock');
                                copy("$composer $maincomp");
                		$val = shell_exec("composer install");
				echo $val;
		} else {
			$output->writeln("<info>File does not exist</info>");
		}
	}
}