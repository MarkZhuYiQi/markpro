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
        !file_exists($this->project_main) && file_put_contents($this->project_main, '<?php'.PHP_EOL.'//project name:'.$prjName.PHP_EOL.'//project author:'.$prjAuthor.PHP_EOL);
//            if (!is_dir($this->project_folder)) {
//                mkdir($this->project_folder, 0777);
//            }else{
//                return 'Error! '.$prjName.' dir already exists!';
//            }
//            if(file_exists($this->project_main))return 'Error! index.php already exists!';
//            file_put_contents($this->project_main,
//                '<?php'.PHP_EOL.'//project name:'.$prjName.PHP_EOL.'//project author:'.$obj->prj_author.PHP_EOL);
        return 'dir catallog has been established!';
    }
}