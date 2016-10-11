<?php
/**
 * Created by PhpStorm.
 * User: red
 * Date: 10/11/16
 * Time: 2:22 PM
 */
namespace core\frame;
class mark_frame
{
    public $project_folder='';      //项目文件夹
    public $project_main='';        //入口文件
    function __construct($prjName)
    {
        $this->project_folder=getcwd().'/'.$prjName;
        $this->project_main=$this->project_folder.'/index.php';
    }
    function run()
    {
        !(file_exists($this->project_folder)&&is_dir($this->project_folder)) && mkdir($this->project_folder, 0777);
        extract(get_object_vars($this));
        ob_start();
        include(dirname(__FILE__).'/template/index.tpl');
        $cnt=ob_get_contents();
        ob_end_clean();
        !file_exists($this->project_main) && file_put_contents($this->project_main,$cnt);
        echo 'PHP Server has started!'.PHP_EOL;
        system('/usr/local/php/bin/php -S localhost:8081 -t /home/red/test/markpro/9/zhu');
        return 'dir catallog has been established!';
    }
    function compile()
    {
        $_files=scandir($this->project_folder.'/code');
        foreach($_files as $_file){
            if(preg_match("/[\w]+\.var\.php$/i",$_file))
            {
                //全部变量引入后，重复变量将自动只保留最后一个。
                require($this->project_folder.'/code/'.$_file);
                unset($_file);
            }
        }
        unset($_files);
//        var_export(get_defined_vars());
        $result='<?php '.PHP_EOL
            .'extract('.var_export(get_defined_vars(),1).');';
        file_put_contents($this->project_folder.'/vars.php',$result);
    }
}