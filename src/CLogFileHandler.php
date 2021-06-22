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
	    empty($file) && $file = "../logs/".date('Y-m-d').'.log';
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