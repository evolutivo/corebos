<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;

class composerperspectiveCommand extends Command {


	protected $templates_path = __DIR__ . "/templates/";
	protected $root_path = __DIR__ . "/../../";
	protected $replace = [];

	protected function configure() {

		$this
			// the name of the command (the part after "bin/console")
			->setName('composer:create')

			// the short description shown while running "php bin/console list"
			->setDescription('Create a composer perspective')

			// the full command description shown when running the command with
			// the "--help" option
			->setHelp('This command allows you to create a composer perspective...')

			// configure an argument
			->addArgument('files', InputArgument::REQUIRED, 'list of repos')
                        
                        // configure an argument
			->addArgument('name', InputArgument::REQUIRED, 'name of composer')
			;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
                
                $files = explode(",",$input->getArgument("files"));
                $name = $input->getArgument("name");
		$class_path = $this->templates_path . "composerfile.php";
		$class_content = file_get_contents($class_path);

		$cmp_path = $this->root_path ."perspectives/composer$name.json";
                foreach ($files as $file){
                    $repo.= '{"type": "vcs","url": "'.$file.'"},';
                    $rel = explode("/",$file);
                    $rl = $rel[3].'/'.$rel[4];
                    $reporel = '"'.$rl.'": "dev-master"';
                }
                $reporel2 = $reporel.implode(",");
		$this->replace['REPO'] = $repo;
                $this->replace['RELPATH'] = $reporel2;
		$new_content = str_replace(array_keys($this->replace), array_values($this->replace), $class_content);

		file_put_contents($cmp_path, $new_content);
                $output->writeln("<info>Created Sucessfuly</info>");

		
	}
}
