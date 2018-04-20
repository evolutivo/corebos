<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;

class composerperspectiveeditCommand extends Command {


	protected $templates_path = __DIR__ . "/templates/";
	protected $root_path = __DIR__ . "/../../";
	protected $replace = [];

	protected function configure() {

		$this
			// the name of the command (the part after "bin/console")
			->setName('composer:edit')

			// the short description shown while running "php bin/console list"
			->setDescription('Edit a composer perspective')

			// the full command description shown when running the command with
			// the "--help" option
			->setHelp('This command allows you to edit a composer perspective...')

			// configure an argument
			->addArgument('files', InputArgument::REQUIRED, 'list of repos')
                        
                        // configure an argument
			->addArgument('name', InputArgument::REQUIRED, 'name of composer')
			;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
                
                $files = explode(",",$input->getArgument("files"));
                $name = $input->getArgument("name");

                $cmp_path = $this->root_path ."perspectives/composer$name.json";

                $filecontent = file_get_contents( $cmp_path );

                foreach ($files as $file){
                    $repo.= '{"type": "vcs","url": "'.$file.'"},';
                    $rel = explode("/",$file);
                    $rl.= $rel[3].'/'.$rel[4].': "dev-master",';
                }
                $reporel2 = $reporel.implode(",");

		$catch = "{
            \"packagist\": false
  }";
		$replace = "$repo {
            \"packagist\": false
  }";
                
                $catch2 = ' "require": {';
                $replace2 = ' "require": { '.$rl.'';
		$new_comp = str_replace($catch,$replace, $filecontent);
		file_put_contents($cmp_path, $new_comp);
               
                $filecontent2 = file_get_contents( $cmp_path );
                $new_comp2 = str_replace($catch2,$replace2, $filecontent2);
		file_put_contents($cmp_path, $new_comp2);
                $output->writeln("<info>Created Sucessfuly</info>");

		
	}
}
