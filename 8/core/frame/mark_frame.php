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



        return 'dir catallog has been established!';
    }
}