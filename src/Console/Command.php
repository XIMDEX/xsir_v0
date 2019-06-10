<?php

namespace Ximdex\Console;

use Illuminate\Console\Parser;
use Illuminate\Console\Command as BaseCommand;

class Command extends BaseCommand
{
    protected $logChannel = 'commands';

    private $defaultParams = '{ --l|log : Display output in log file }';

    function __construct()
    {
        parent::__construct();

        $this->addDefaultParams();
    }

    protected function addDefaultParams()
    {
        [$name, $arguments, $options] = Parser::parse("{$this->name} {$this->defaultParams}");
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }

    protected function message($message, $type = 'info')
    {
        if ($this->option('verbose')) {
            $this->$type($message);
        }

        $this->log($message, $type);
    }

    protected function log($message, $type = 'info')
    {
        if ($this->option('log')) {
            \Log::channel($this->logChannel)->$type($message);
        }
    }
}
