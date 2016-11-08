<?php
    namespace core\frame;
    use core\frame\mark_mvc;
    class mark_frame{
        public $project_folder='';
        public $project_main='';
        function __construct($prj_name)
        {
            $this->project_folder=getcwd().'/'.$prj_name;
            $this->project_main=$this->project_folder.'/index.php';
        }
        function run()
        {
            extract(get_object_vars($this));
            !(file_exists($this->project_folder) && is_dir($this->project_folder))
            && mkdir($this->project_folder,0777);
            ob_start();
            include(dirname(__FILE__).'/template/index.tpl');
            $content=ob_get_contents();
            ob_end_clean();
            !file_exists($this->project_main) && file_put_contents($this->project_main,$content);
            echo 'PHP Server has started!'.PHP_EOL;
            system('/usr/local/php/bin/php -S localhost:8081 -t /home/red/test/markpro/evo/zhu');
            return 'Dir Catalog has been established';

        }
        function compile()
        {
            $_files=scandir($this->project_folder.'/code');
            foreach($_files as $_file)
            {
                if(preg_match('/\w+\.(var|func|class)\.php$/i',$_file))
                {
                    require($this->project_folder.'/code/'.$_file);
                }
                unset($_file);
            }
            unset($_files);
            $content='<?php'.PHP_EOL.
                '/**'.PHP_EOL.
                ' * Compiled By Mark'.PHP_EOL.
                ' * '.date('Y-m-d H:i:s').PHP_EOL.
                ' */'.PHP_EOL.
                'extract('.var_export(get_defined_vars(),1).');'.PHP_EOL;
            file_put_contents($this->project_folder.'/vars.php',$content);
            $funcs=get_defined_functions()['user'];
            $funcs=array_slice($funcs,4);
            $content='<?php'.PHP_EOL.
                '/**'.PHP_EOL.
                ' * Compiled By Mark'.PHP_EOL.
                ' * '.date('Y-m-d H:i:s').PHP_EOL.
                ' */'.PHP_EOL;
            foreach($funcs as $func)
            {
                $f=new \ReflectionFunction($func);
                $detail=file($f->getFileName());
                $content.=implode(array_slice($detail,$f->getStartLine()-1,$f->getEndLine()-$f->getStartLine()+1)).PHP_EOL;
            }
            file_put_contents($this->project_folder.'/func.php',$content);

            $classes=get_declared_classes();
            //获得了用户类
            $classes=array_slice($classes,array_search(__CLASS__,$classes)+1);
            $result=array();
            foreach($classes as $class)
            {
                $mvc=new mark_mvc($class);
                if($mvc->isController())
                {
                    $result=array_merge($result,$mvc->getRequestMapping());
                }
            }
//            var_export($result);
            file_put_contents($this->project_folder.'/request_route','<?php'.PHP_EOL.'return '.var_export($result,1).';');
        }
    }