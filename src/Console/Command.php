<?php

namespace Ximdex\Console;

use Illuminate\Console\Parser;
use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

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

    protected function warning($message)
    {
        $style = new OutputFormatterStyle('yellow', null, ['bold']);
        $this->output->getFormatter()->setStyle('warning', $style);
        $this->line("<warning>{$message}</warning>");
    }

    protected function log($message, $type = 'info')
    {
        if ($this->option('log')) {
            \Log::channel($this->logChannel)->$type($message);
        }
    }
}
