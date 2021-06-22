<?php
namespace Benjamin\Log;
//以下为日志
interface ILogHandler
{
	public function write($msg);
}

class CLogFileHandler implements ILogHandler
{
	private $handle = null;
	
	public function __construct($file = '')
	{
        $vendorDir = dirname(dirname(__FILE__));
        $baseDir = dirname($vendorDir);
	    if(dirname($file) == "."){//文件
            $filename = date('Y-m-d')."_".$file;
            $file = $vendorDir . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . $filename;
        }elseif(empty(dirname($file))){
            $filename = date('Y-m-d') . '.log';
            $file = $vendorDir . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . $filename;
        }else{//带相对路径的文件
//	        $file =
        }

//        empty($file) && $file = $vendorDir . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . $filename;
		$this->handle = fopen($file,'a');
		if($this->handle === false)
		{
		    throw new Exception("生成文件".$file."失败");
		}
	}
	
	public function write($msg)
	{
		if(fwrite($this->handle, $msg, 409600) === false)
		{
		    throw new Exception("写入".$msg."失败");
		}
	}
	
	public function __destruct()
	{
		if(fclose($this->handle) === false)
		{
		    throw new Exception("关闭".$this->handle."失败");
		}
	}
}