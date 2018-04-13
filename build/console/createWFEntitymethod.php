<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateWFEntitymethodCommand extends Command
{

	protected $templates_path = __DIR__ . "/templates/";
	protected $root_path = __DIR__ . "/../../";
	protected $replace = [];

	protected function configure() {

		$this
			// the name of the command (the part after "bin/console")
			->setName('entitymethod:create')

			// the short description shown while running "php bin/console list"
			->setDescription('Creates a new WF Entity method')

			// the full command description shown when running the command with
			// the "--help" option
			->setHelp('This command allows you to create a workflow custom function...')

			// configure an argument
			->addArgument('name', InputArgument::REQUIRED, 'name of the method')

			// configure an argument
			->addArgument('module', InputArgument::REQUIRED, 'module name')
                        
                        // configure an argument
			->addArgument('function_name', InputArgument::REQUIRED, 'name of the function')
                        
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$name = $input->getArgument("name");
		$module_name = $input->getArgument("module");
                $function_name = $input->getArgument("function_name");

                //create custom function
		$class_path = $this->templates_path . "WFCustomFunction.php";
		$class_content = file_get_contents( $class_path );

		$wf_path = $this->root_path ."modules/{$module_name}/workflows/";
                $wf_pathcomplete = $wf_path.$function_name.'.php';

                $this->replace['dummyfunction'] = $function_name;
		$new_content = str_replace(array_keys($this->replace), array_values($this->replace), $class_content );

		if(!is_dir($wf_path)) {
			mkdir($wf_path, 0755);
		}

		file_put_contents($wf_pathcomplete, $new_content);
                //create entity method cbupdater
                $class_pathem = $this->templates_path . "WFEntityMethod.php";
		$class_contentem = file_get_contents( $class_pathem );

                $this->replace['MODULE'] = $module_name;
                $this->replace['DESC'] = $name;
                $this->replace['FUNCTION_NAME'] = $function_name;
                $this->replace['PATH'] = "modules/{$module_name}/workflows/{$function_name}.php";
                
		$new_contentem = str_replace(array_keys($this->replace), array_values($this->replace), $class_contentem );
		var_dump("$new_contentem");
                $output->writeln("<info>Created Sucessfuly</info>");

	}

}