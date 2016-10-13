<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/12/16
 * Time: 10:09 PM
 */
namespace core\frame;
class mark_frame
{
    public $project_folder='';
    public $project_main='';
    function __construct($prjName)
    {
        $this->project_folder=getcwd().'/'.$prjName;
        $this->project_main=$this->project_folder.'/index.php';
    }
    function run()
    {
        !file_exists($this->project_folder) && mkdir($this->project_folder,0777);
        extract(get_object_vars($this));
        ob_start();
        include(dirname(__FILE__).'/template/index.tpl');
        $content=ob_get_contents();
        ob_end_clean();
        !file_exists($this->project_main) && file_put_contents($this->project_main,$content);
    }
    function complie()
    {
        $_files=scandir($this->project_folder.'/code/');
        foreach($_files as $_file)
        {
            if(preg_match("/[\w]+?\.(var|func)\.php/",$_file))
            {
                require($this->project_folder.'/code/'.$_file);
                unset($_file);
            }
        }
        unset($_files);
        $result='<?php '.PHP_EOL.'extract('.var_export(get_defined_vars(),true).');';
        file_put_contents($this->project_folder.'/vars.php',$result);


        $getFunc=(get_defined_functions()['user']);
        $func_res='<?php'.PHP_EOL;
        foreach($getFunc as $key =>$_func)
        {
            if($key<6)
            {
                unset($getFunc[$key]);
            }
            else
            {
                $f=new \ReflectionFunction($_func);
                $detail=file($f->getFileName());
                $detail=implode(array_slice($detail,$f->getStartLine()-1,$f->getEndLine()-$f->getStartLine()+1));
                $func_res.=$detail;
            }
        }
        file_put_contents($this->project_folder.'/func.php',$func_res);
    }
}