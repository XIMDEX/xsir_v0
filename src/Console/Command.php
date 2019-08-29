<?php

namespace Ximdex\Console;

use Illuminate\Console\Parser;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Base command class to handle generic methods in projects commands
 */
class Command extends BaseCommand
{
    /**
     * Log chanel to save command log output write with log() function
     *
     * @var string
     */
    protected $logChannel = 'commands';

    /**
     * Default params to commands
     *
     * @var string
     */
    private $defaultParams = '{ --l|log : Display output in log file }';

    function __construct()
    {
        parent::__construct();

        $this->addDefaultParams();
    }

    /**
     * Set the default params describe in $defaultParams property
     * and enable to use in cli
     *
     * @return void
     */
    protected function addDefaultParams()
    {
        // Parse the command name and params and return an rray with [name, arguments, options]
        [, $arguments, $options] = Parser::parse("{$this->name} {$this->defaultParams}");

        // Add arguments and options to current command
        $this->getDefinition()->addArguments($arguments);
        $this->getDefinition()->addOptions($options);
    }


    /**
     * Print a message in console if option verbose is true in command or if $force params is true
     *
     * @param string $message
     * @param string $type
     * @param bool $force
     * 
     * @return void
     */
    protected function message(string $message, string $type = 'info', bool $force = false)
    {
        $method =  method_exists($this, $type) ? $type : 'info';

        if ($this->option('verbose') || $force) {
            $this->{$method}($message);
        }

        $this->log($message, $type, $force);
    }

    /**
     * Print Warning message in command line
     *
     * @param string $message
     * @return void
     */
    protected function warning(string $message)
    {
        $style = new OutputFormatterStyle('yellow', null, ['bold']);
        $this->output->getFormatter()->setStyle('warning', $style);
        $this->line("<warning>{$message}</warning>");
    }

    /**
     * Print a message in Log file if option verbose is true in command or if $force params is true
     *
     * @param string $message
     * @param string $type
     * @param boolean $force
     * 
     * @return void
     */
    protected function log(string $message, string $type = 'info', bool $force = false)
    {
        if ($this->option('log')) {
            $logger = Log::channel($this->logChannel);
            $method = method_exists($logger, $type) ? $type : 'info';

            $logger->{$method}($message);
        }
    }
}
