<?php

namespace jzweb\sat\crbank\Lib;

use jzweb\sat\crbank\Exception\ServerException;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;

/**
 * 封装SFtp请求接口
 *
 * @user 刘松森 <liusongsen@gmail.com>
 * @date 2018/12/6
 */
class  SFtpRequest
{

    private $config;
    private $adapter;

    public function __construct($config)
    {
        $this->config = $config;
        $this->adapter = new SftpAdapter([
            'host' => $this->config['host'],
            'port' => $this->config['port'],
            'username' => $this->config['key'],
            'password' => $this->config['secret'],
        ]);
    }


    /**
     * 封装http post方法
     *
     * @param $url
     * @param $data
     * @return string
     */
    public function upload($file,$is_today=false)
    {
        try {
            if (!file_exists($file)) {
                throw  new ServerException("系统不存在这样的文件路径(" . $file . ")");
            }
            $path_parts = pathinfo($file);
            $filesystem = new Filesystem($this->adapter);
            $uploadDir =$is_today ? date("Ymd"): date("Ymd",strtotime('-1 days'));
            //第一步创建文件夹
            $filesystem->createDir($uploadDir);
            if ($filesystem->has($uploadDir . "/" . $path_parts['filename'] . ".ok")) {
                throw new ServerException("系统检测到重名文件".$uploadDir . "/" . $path_parts['filename'] . ".ok");
            }
            if ($filesystem->has($uploadDir . "/" . $path_parts['filename'] . ".tmp")) {
                throw new ServerException("系统检测到重名文件".$uploadDir . "/" . $path_parts['filename'] . ".tmp");
            }
            //第二步创建.tmp并完成文件上传,之后需要改名.ok后缀
            $filesystem->write($uploadDir . "/" . $path_parts['filename'] . ".tmp", file_get_contents($file));
            if($filesystem->has($uploadDir . "/" . $path_parts['filename'] . ".tmp")){
                $filesystem->rename($uploadDir . "/" . $path_parts['filename'] . ".tmp",$uploadDir . "/" . $path_parts['filename'] . ".ok");
            }else{
                throw new ServerException("文件写入未完成");
            }
            //返回数据
            return ['return_code' => "SUCCESS", 'return_msg' => "成功", 'data' => ['dir' => $uploadDir, 'file' => $path_parts['filename']]];
        } catch (\Exception $e) {
            return ['return_code' => "FAIL", 'return_msg' => $e->getMessage()];
        }
    }

    /**
     * 获取sftp处理结果
     * 针对处理成功的文件,银行服务会对服务器做定期清理
     * 我们不用主动删除这些资源
     *
     * @param string $dir
     * @param string $file
     * @param bool $is_bill 是否下载对账单
     * @return string
     */
    public function resp($dir, $file,$is_bill=false)
    {
        try {
            $filesystem = new Filesystem($this->adapter);
            //获取上传结果

            $file = $is_bill ? $file.".csv" :  str_replace("REQ", "RESP", $file) . ".ok";
            if ($filesystem->has($dir . "/" . $file)) {
                return ['return_code' => "SUCCESS", 'return_msg' => "处理完成", 'data' => $filesystem->read($dir . "/" . $file)];
            } else {
                throw new ServerException("该任务还在处理请稍等:".$dir . "/" . $file);
            }
        } catch (\Exception $e) {
            return ['return_code' => "FAIL", 'return_msg' => $e->getMessage()];
        }
    }

}