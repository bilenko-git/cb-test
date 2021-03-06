<?php
namespace ParaTest\Runners\PHPUnit;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

abstract class ExecutableTest
{
    /**
     * The path to the test to run
     *
     * @var string
     */
    protected $path;

    /**
     * A path to the temp file created
     * for this test
     *
     * @var string
     */
    protected $temp;
    protected $fullyQualifiedClassName;
    protected $pipes = array();

    /**
     * Path where the coveragereport is stored
     * @var string
     */
    protected $coverageFileName;

    /**
     * @var Process
     */
    protected $process;

    /**
     * A unique token value for a given
     * process
     *
     * @var int
     */
    protected $token;

    public function __construct($path, $fullyQualifiedClassName = null)
    {
        $this->path = $path;
        $this->fullyQualifiedClassName = $fullyQualifiedClassName;
    }

    /**
     * Get the expected count of tests or testmethods
     * to be executed in this test
     *
     * @return int
     */
    abstract public function getTestMethodCount();

    /**
     * Get the path to the test being executed
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the path to this test's temp file.
     * If the temp file does not exist, it will be
     * created
     *
     * @return string
     */
    public function getTempFile()
    {
        if (is_null($this->temp)) {
            $this->temp = tempnam(sys_get_temp_dir(), "PT_");
        }

        return $this->temp;
    }

    /**
     * Return the test process' stderr contents
     *
     * @return string
     */
    public function getStderr()
    {
        return $this->process->getErrorOutput();
    }

    /**
     * Return any warnings that are in the test output, or false if there are none
     * @return mixed
     */
    public function getWarnings()
    {
        if (!$this->process) {
            return false;
        }

        // PHPUnit has a bug where by it doesn't include warnings in the junit
        // output, but still fails. This is a hacky, imperfect method for extracting them
        // see https://github.com/sebastianbergmann/phpunit/issues/1317
        preg_match_all(
            '/^\d+\) Warning\n(.+?)$/ms',
            $this->process->getOutput(),
            $matches
        );

        return isset($matches[1]) ? $matches[1] : false;
    }

    /**
     * Stop the process and return it's
     * exit code
     *
     * @return int
     */
    public function stop()
    {
        return $this->process->stop();
    }

    /**
     * Removes the test file
     */
    public function deleteFile()
    {
        $outputFile = $this->getTempFile();
        unlink($outputFile);
    }

    /**
     * Check if the process has terminated.
     *
     * @return bool
     */
    public function isDoneRunning()
    {
        return $this->process->isTerminated();
    }

    /**
     * Return the exit code of the process
     *
     * @return int
     */
    public function getExitCode()
    {
        return $this->process->getExitCode();
    }

    /**
     * Executes the test by creating a separate process
     *
     * @param $binary
     * @param array $options
     * @param array $environmentVariables
     * @return $this
     */
    public function run($binary, $options = array(), $environmentVariables = array())
    {
        $environmentVariables['PARATEST'] = 1;
        $this->handleEnvironmentVariables($environmentVariables);
        $command = $this->command($binary, $options);
        $this->process = new Process($command, null, $environmentVariables);
        $this->process->start();
        return $this;
    }

    /**
     * Returns the unique token for this test process
     *
     * @return int
     */
    public function getToken()
    {
        return $this->token;
    }

    public function command($binary, $options = array())
    {
        $options = array_merge($this->prepareOptions($options), array('log-junit' => $this->getTempFile()));
        $options = $this->redirectCoverageOption($options);
        return $this->getCommandString($binary, $options);
    }

    /**
     * A template method that can be overridden to add necessary options for a test
     *
     * @param  array $options the options that are passed to the run method
     * @return array $options the prepared options
     */
    protected function prepareOptions($options)
    {
        return $options;
    }

    /**
     * Returns the command string that will be executed
     * by proc_open
     *
     * @param $binary
     * @param array $options
     * @return mixed
     */
    protected function getCommandString($binary, $options = array())
    {
        $builder = new ProcessBuilder();
        $builder->setPrefix($binary);
        foreach ($options as $key => $value) {
            $builder->add("--$key");
            if ($value !== null) {
                $builder->add($value);
            }
        }

        $builder->add($this->fullyQualifiedClassName);
        $builder->add($this->getPath());

        $process = $builder->getProcess();

        return $process->getCommandLine();
    }

    /**
     * Checks environment variables for the presence of a TEST_TOKEN
     * variable and sets $this->token based on its value
     *
     * @param $environmentVariables
     */
    protected function handleEnvironmentVariables($environmentVariables)
    {
        if (isset($environmentVariables['TEST_TOKEN'])) {
            $this->token = $environmentVariables['TEST_TOKEN'];
        }
    }

    /**
     * Checks if the coverage-php option is set and redirects it to a unique temp file.
     * This will ensure, that multiple tests write to separate coverage-files.
     *
     * @param array $options
     * @return array $options
     */
    private function redirectCoverageOption($options)
    {
        if (isset($options['coverage-php'])) {
            $options['coverage-php'] = $this->getCoverageFileName();
        }

        unset($options['coverage-html']);
        unset($options['coverage-clover']);

        return $options;
    }

    /**
     * @return string
     */
    public function getCoverageFileName()
    {
        if ($this->coverageFileName === null) {
            $this->coverageFileName = tempnam(sys_get_temp_dir(), "CV_");
        }

        return $this->coverageFileName;
    }

    public function getStdout()
    {
        return $this->process->getOutput();
    }

    public function setTempFile($temp)
    {
        $this->temp = $temp;
    }
}
