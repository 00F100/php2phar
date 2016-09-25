<?php

namespace PHP2Phar {

    use Phar;
    use PHPUPhar\PHPUPhar;

    class PHP2Phar
    {
        const VERSION = '0.3.0';

        private $continue = true;
        private $dirSource = array();
        private $indexFIle = '';
        private $outputFile = '';
        private $urlVersion = array(
            'base' => 'https://raw.githubusercontent.com',
            'path' => '/00F100/php2phar/master/VERSION',
        );
        private $urlDownload = 'https://github.com/00F100/php2phar/raw/master/dist/php2phar.phar';

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
            $paths = explode('/', $this->outputFile);
            $nameFile = end($paths);
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
                    case '-u':
                    case '--self-update':
                        return $this->selfUpdateOption();
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

        private function selfUpdateOption()
        {
            $this->printText("Versão atual: " . self::VERSION . " \n");
            $selfUpdate = new PHPUPhar($this->urlVersion, false, self::VERSION, $this->urlDownload, 'php2phar.phar');
            $this->printText("Versão em 00F100/php2phar: " . $selfUpdate->getVersion() . " \n");
            if(self::VERSION == $selfUpdate->getVersion()){
                $this->printText("A sua versão esta atualizada! \n");
            }
            if (self::VERSION != $selfUpdate->getVersion() && $selfUpdate->update()) {
                $this->printText("A sua versão foi atualizada com sucesso! \n");
            }
        }

        private function helpOption()
        {
            $this->printText("   PHP2Phar " . self::VERSION . " \n");
            $this->printText("   Usage:\n");
            $this->printText("     php php2phar.phar --dir-source <path/to/dir> --index-file </path/to/index.php> --output-file <path/to/file.phar>  \n\n");
            $this->printText("     Self Update: \n");
            $this->printText("     php php2phar.phar --self-update  \n\n");
            $this->printText("     Help: \n");
            $this->printText("     php php2phar.phar --help  \n\n");
            $this->printText("     Options:\n");
            $this->printText("       -d,  --dir-source     Directory of the source code to be sent to the phar file  \n");
            $this->printText("       -i,  --index-file     File \"index.php\" to start new instance of your code \n");
            $this->printText("       -o,  --output-file    File \".phar\" to save your code \n");
            $this->printText("       -u,  --self-update    Upgrade to the latest version  \n");
            $this->printText("       -v,  --version        Return the installed version of this package  \n");
            $this->printText("       -h,  --help           Show this help  \n");
            return false;
        }

        public function printText($text)
        {
            echo $text;
        }

        public function exceptionHandler($exception)
        {
            $this->printText('[FLOG] ' . $exception->getMessage());
        }
    }
}