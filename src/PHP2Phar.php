<?php

namespace PHP2Phar {

    use Phar;

    class PHP2Phar
    {
        const VERSION = '0.1.0';

        private $continue = true;
        private $dirSource = array();
        private $indexFIle = '';
        private $outputFile = '';

        public function __construct($args)
        {
            set_exception_handler(array($this, 'exceptionHandler'));
            unset($args[0]);
            if(!$this->configArgs($args)){
                $this->continue = false;
                return false;
            }
            return true;
        }

        public function execute()
        {
            $this->printText('Creating the file ' . $this->outputFile . "\n");
            if(!is_file($this->outputFile)){
                $this->printText("Creating the Phar file ... \n");
            }
            if(is_file($this->outputFile)){
                unlink($this->outputFile);
                $this->printText("Updating the Phar file ... \n");
                if(is_file($this->outputFile . '.gz')){
                    unlink($this->outputFile . '.gz');
                }
            }
            $nameFile = end(explode('/', $this->outputFile));
            $app = new Phar($this->outputFile, 0, $nameFile);
            $app->startBuffering();
            foreach($this->dirSource as $dir){
                $app->buildFromDirectory($dir, '/\.php|json$/');
            }
            $app->setStub($app->createDefaultStub($this->indexFIle));
            $app->compress(Phar::GZ);
            $app->stopBuffering();
            $this->printText("Finished! \n");
        }

        public function isEnable()
        {
            return $this->continue;
        }

        private function configArgs($args)
        {
            while ($value = current($args)) {
                switch ($value) {
                    case '-h':
                    case '--help':
                        return $this->helpOption();
                        break;
                    case '-v':
                    case '--version':
                        return $this->versionOption();
                        break;
                    case '-d':
                    case '--dir-source':
                        $this->dirSource[] = next($args);
                        break;
                    case '-o':
                    case '--output-file':
                        $this->outputFile = next($args);
                        break;
                    case '-i':
                    case '--index-file':
                        $this->indexFIle = next($args);
                        break;
                }
                next($args);
            }
            return $this->checkConfig();
        }

        private function checkConfig()
        {
            if(count($this->dirSource) == 0 || empty($this->indexFIle) || empty($this->outputFile)){
                return $this->helpOption();
            }
            return true;
        }

        private function versionOption()
        {
            $this->printText(self::VERSION);
            return false;
        }

        private function helpOption()
        {
            $this->printText("   PHP2Phar " . self::VERSION . " \n");
            $this->printText("   Usage:\n");
            $this->printText("     php php2phar.phar --dir-source <path/to/dir> --index-file </path/to/index.php> --output-file <path/to/file.phar>  \n\n");
            // $this->printText("     Self Update: \n");
            // $this->printText("     php php2phar.phar --self-update  \n\n");
            $this->printText("     Help: \n");
            $this->printText("     php php2phar.phar --help  \n\n");
            $this->printText("     Options:\n");
            $this->printText("       -d,  --dir-source     Directory of the source code to be sent to the phar file  \n");
            $this->printText("       -i,  --index-file     File \"index.php\" to start new instance of your code \n");
            $this->printText("       -o,  --output-file    File \".phar\" to save your code \n");
            // $this->printText("       -u,  --self-update    Upgrade to the latest version  \n");
            $this->printText("       -v,  --version        Return the installed version of this package  \n");
            $this->printText("       -h,  --help           Show this help  \n");
            return false;
        }

        public function printText($text)
        {
            echo $text;
        }

        public function exceptionHandler(Exception $exception)
        {
            $this->printText('[FLOG] ' . $exception->getMessage());
        }
    }
}