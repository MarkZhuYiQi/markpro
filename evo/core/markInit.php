<?php
    namespace core;
    use core\frame\mark_frame;
    class markInit
    {
        static $v='mark version is 1.1';
        /**
         * 初始化项目名称和作者，存入json文件中
         * @return mixed
         */
        static function init()
        {
            echo 'please input your project name:'.PHP_EOL;
            $prj_name=fgets(STDIN);

            echo 'please input this project author name:'.PHP_EOL;
            $author_name=fgets(STDIN);
            return (TC(array('prj_name'=>$prj_name,'author_name'=>$author_name)));
        }
        static function ini()
        {
            $config=loadConfig();
            foreach($config as $key =>$value)
            {
                echo $key .'='. $value.PHP_EOL;
            }
        }
        static function start()
        {
            if(!file_exists(getcwd().'/mark.json'))return 'Error!Config json file is missing!';
            if(is_object($obj=loadConfig()))
            {
                $mf=new mark_frame($obj->prj_name);
                $mf->prj_author=$obj->author_name;
                $mf->prj_name=$obj->prj_name;
                return $mf->run();
            }
            else
            {
                return 'Error! Data Missing!';
            }
        }
        static function c()
        {
            $get_config=loadConfig();
            $mf=new mark_frame($get_config->prj_name);
            $mf->compile();
        }
        static function __callStatic($name, $arguments)
        {
            // TODO: Implement __callStatic() method.
            echo 'error!no function matched!';
        }
    }