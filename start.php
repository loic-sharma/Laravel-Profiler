<?php

Event::listen('laravel: query', function($sql, $bindings, $time)
{
	if(in_array($sql, Profiler::$queries))
	{
		Profiler::$query_duplicates++;
	}

	Profiler::$query_total_time += (double)$time;
	Profiler::$queries[] = $sql;
});

Event::listen('laravel: done', function()
{
	Profiler::compile_file_data();

	echo View::make('profiler::display');
});

Autoloader::map(array(
	'Profiler' => __DIR__ . DS . 'libraries' . DS . 'profiler.php',
));