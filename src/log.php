<?php
namespace Log;
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

class Log
{
	private $handler = null;
	private $level = 15;
	
	private static $instance = null;
	
	private function __construct(){}

	private function __clone(){}
	
	public static function Init($handler = null,$level = 15)
	{
		if(!self::$instance instanceof self)
		{
			self::$instance = new self();
			self::$instance->__setHandle($handler);
			self::$instance->__setLevel($level);
		}
		return self::$instance;
	}
	
	
	private function __setHandle($handler){
		$this->handler = $handler;
	}
	
	private function __setLevel($level)
	{
		$this->level = $level;
	}
	
	public static function DEBUG($msg)
	{
		self::$instance->write(1, $msg);
	}
	
	public static function WARN($msg)
	{
		self::$instance->write(4, $msg);
	}
	
	public static function ERROR($msg)
	{
		$debugInfo = debug_backtrace();
		$stack = "[";
		foreach($debugInfo as $key => $val){
			if(array_key_exists("file", $val)){
				$stack .= ",file:" . $val["file"];
			}
			if(array_key_exists("line", $val)){
				$stack .= ",line:" . $val["line"];
			}
			if(array_key_exists("function", $val)){
				$stack .= ",function:" . $val["function"];
			}
		}
		$stack .= "]";
		self::$instance->write(8, $stack . $msg);
	}
	
	public static function INFO($msg)
	{
		self::$instance->write(2, $msg);
	}
	
	private function getLevelStr($level)
	{
		switch ($level)
		{
		case 1:
			return 'debug';
		break;
		case 2:
			return 'info';	
		break;
		case 4:
			return 'warn';
		break;
		case 8:
			return 'error';
		break;
		default:
				
		}
	}
	
	protected function write($level,$msg)
	{
	    if(is_array($msg))
	    {
	       $msg = print_r($msg,true);    
	    }
	    
		if(($level & $this->level) == $level )
		{
			$msg = '['.date('Y-m-d H:i:s').']['.$this->getLevelStr($level).'] '.$msg."\n";
			$this->handler->write($msg);
		}
	}
}


// $logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
// $log = Log::Init($logHandler, 15);
// $log->DEBUG('begin debug');
// $a = 0;
// 1/$a;
// $log->ERROR("THIS IS ERROR");
// $log->INFO("INFO");
?>