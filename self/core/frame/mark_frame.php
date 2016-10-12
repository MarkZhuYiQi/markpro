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
}