<?php
/**
 * Provide updated assets from cache
 * 
 * 
 */
namespace services;
use core\App;

use core\AService;

class Assets extends AService
{
	const CSS_TYPE = 'css';
	const JS_TYPE = 'js';
	
	protected function _indexAction()
	{
		App::throw404();
	}
	
	protected function _cssAction()
	{
		$this->_returnFile($this->_request->path[1], self::CSS_TYPE);
	}
	
	protected function _jsAction()
	{
		$this->_returnFile($this->_request->path[1], self::JS_TYPE);

	}
	
	protected function _returnFile($aFile, $aFileType)
	{
		switch ($aFileType) {
			case "css":
				$contentType = "text/css";
				break;
			case "js":
				$contentType = "text/javascript";
				break;
		}
		
		header("Content-Type: ".$contentType."; charset=UTF-8");
		if(file_exists(CM_CACHE . $aFileType . DS . $aFile)) {
			if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
				ini_set("zlib.output_compression", 1);
				header("Content-Encoding: gzip");
				header("Content-Length: ".filesize($gzFileName));
			}
			header('Cache-Control: max-age=32850000');
			header("File-Name: ".$fileName);
			readfile(CM_CACHE . $aFileType . DS . $aFile);
		} else {
			echo 'wtf';
			//App::throw404();
		}
	}
}