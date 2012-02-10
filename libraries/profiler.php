<?php

class Profiler {

	public static $logs = array();
	public static $logs_count = 0;
	public static $memory_logs = 0;
	public static $speed_logs = 0;
	public static $error_logs = 0;
	

	public static $query_duplicates = 0;
	public static $query_total_time = 0;
	public static $queries = array();

	public static $files = array();
	public static $files_total_size = 0;
	public static $files_largest;

	public function __construct()
	{
		DB::query('SELECT * FROM forums');
		DB::query('SELECT * FROM posts');

		$path  = realpath(__DIR__ . DS . '../');
		$path .= DS.'pqp'.DS;

		include $path.'classes'.DS.'PhpQuickProfiler.php';

		$this->pqp = new PhpQuickProfiler(LARAVEL_START, $path);
	}

	public static function log($message)
	{
		static::$logs[] = array(
			'type'    => 'log',
			'message' => $message,
		);

		static::$logs_count++;
	}

	public static function log_memory($name = FALSE, $value = FALSE)
	{
		if($name !== FALSE)
		{
			static::$logs[] = array(
				'type'    => 'memory',
				'message' => gettype($value) . ': ' . $name,
				'data'    => static::readable_file_size(strlen(serialize($value))),
			);
		}

		else
		{
			static::$logs[] = array(
				'type'    => 'memory',
				'message' => 'Current memory used',
				'data'    => static::readable_file_size(memory_get_usage()),
			);
		}

		static::$memory_logs++;
	}

	public static function log_speed($message = 'Benchmark')
	{
		if($message == 'Benchmark')
		{
			$message .= ' #' . (static::$speed_logs + 1);
		}

		static::$logs[] = array(
			'type'    => 'speed',
			'message' => $message,
			'data'    => static::load_time() * 1000 . ' ms'
		);

		static::$speed_logs++;
	}

	public static function log_error($exception, $message = 'error')
	{
		$message  = 'Line ' . $exception->getLine() . ' ' . $message . '<br>';
		$message .= $exception->getFile();

		static::$logs[] = array(
			'type'    => 'error', 
			'message' => $message,
		);

		static::$error_logs++;
	}

	public static function load_time($decimals = 5)
	{
		return number_format(microtime(TRUE) - LARAVEL_START, $decimals);
	}

	public static function memory()
	{
		return static::readable_file_size(memory_get_peak_usage());
	}

	public static function compile_file_data()
	{
		$files = get_included_files();

		foreach($files as $file)
		{
			$size = filesize($file);

			static::$files[] = array(
				'path' => $file,
				'size' => static::readable_file_size($size),
			);

			static::$files_total_size += $size;

			if($size > static::$files_largest)
			{
				static::$files_largest = $size;
			}
		}

		// Now that we've gathered the data we can prepare it
		static::$files_total_size = static::readable_file_size(static::$files_total_size);
		static::$files_largest    = static::readable_file_size(static::$files_largest);	

		return array(
			'files'            => static::$files,
			'files_total_size' => static::$files_total_size,
			'files_largest'    => static::$files_largest,
		);
	}

	private static function readable_file_size($size, $format = null)
	{
		// adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
		$sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

		if(is_null($format))
		{
			$format = '%01.2f %s';
		}

		$lastsizestring = end($sizes);

		foreach ($sizes as $sizestring)
		{
			if ($size < 1024)
			{
				break;
			}

			if ($sizestring != $lastsizestring)
			{
				$size /= 1024;
			}
		}

		// Bytes aren't normally fractional
		if($sizestring == $sizes[0])
		{
			$format = '%01d %s';
		}

		return sprintf($format, $size, $sizestring);
	}

	public function __destruct()
	{
		$var = 'x';

		Profiler::log('test');
		Profiler::log_memory('var', $var);
		Profiler::log_memory();
		Profiler::log_speed();
		Profiler::log_error(new Exception, 'Remove this :P');

		Console::log('test');
		Console::logMemory($var, 'var');
		Console::logSpeed();
		Console::logError(new Exception, 'test');
		Console::logMemory();
		//$this->pqp->display();
	}
}

new Profiler;