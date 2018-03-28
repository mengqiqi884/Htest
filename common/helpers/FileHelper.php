<?php
/**
 * Created by PhpStorm.
 * User: BF
 * Date: 2017/8/15
 * Time: 16:40
 */

namespace common\helpers;

class FileHelper
{
    private $_dir;
    const EXT = ".txt"; //文件后缀
    public function __construct()
    {
        $this->_dir = dirname(__FILE__).'/files/';
    }

    /**
     * 生成缓存、读取缓存、删除缓存
     * $this->cacheData('input_mk',['id'=>1,'name'=>'muke']);   * 生成缓存
     * $this->cacheData('input_mk');         * 读取缓存
     * $this->cacheData('input_mk',null);    * 删除缓存
     * @param string $key  文件名称
     * @param string $value 数据
     * @param string $path 路径
     * @return int/boolean/array
     */
    public function cacheData($key, $value = '', $path = '')
    {
        $filename = $this->_dir . $path . $key . self::EXT;
        if($value!=''){ //将$value值写入文件
            if(is_null($value)){
                return @unlink($filename);  //删除缓存 【成功返回true ,失败or 文件不存在 返回false】
            }
            $dir = dirname($filename);
            if(!is_dir($dir)){
                mkdir($dir,077);  //创建文件
            }
            return file_put_contents($filename,json_encode($value)); //写入缓存 【成功，则返回写入的字节数，若失败，则返回 false】
        }

        if(!$filename){
            return false;
        }else{
            return json_decode(file_get_contents($filename),true);   //读取缓存的文件内容
        }
    }
}