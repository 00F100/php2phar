# php2phar

[![Travis](https://travis-ci.org/00F100/php2phar.svg?branch=master)](https://travis-ci.org/00F100/php2phar/)  [![Code Climate](https://codeclimate.com/github/00F100/php2phar/badges/gpa.svg)](https://codeclimate.com/github/00F100/php2phar)

Create phar files from your source code

## Install

```
$ wget https://github.com/00F100/php2phar/raw/master/dist/php2phar.phar
```
or
[Download Phar file](https://github.com/00F100/php2phar/raw/master/dist/php2phar.phar)

## Usage

```
php php2phar.phar --dir-source <path/to/dir> --index-file </path/to/index.php> --output-file <path/to/file.phar>  
```

## Options

```
       -d,  --dir-source     Directory of the source code to be sent to the phar file
       -i,  --index-file     File "index.php" to start new instance of your code
       -o,  --output-file    File ".phar" to save your code
       -u,  --self-update    Upgrade to the latest version
       -v,  --version        Return the installed version of this package
       -h,  --help           Show this help
 ```