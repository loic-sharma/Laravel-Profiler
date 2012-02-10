# Profiler Bundle

## Installation

To download the bundle run:

	php artisan bundle:install profiler

Then run the following command:

	php artisan bundle:publish

Once that is complete you will need to add the bundle to the **auto** array in the application config file.

## Displaying Profiler

There are several ways you can load the profiler onto your site. You can simply load Profiler through a view:

	echo View::make('profiler::display');

If you want, you can simply nest the view within your own. Or, you can simply use the profiler filter:

	public function __construct()
	{
		$this->filter('after', 'profiler');
	}

## Logging

Profiler lets you debug your code easily. You can log a message by doing:

	Profiler::log('This is my message!');

Want to benchmark your code? Easy!

	Profiler::log_speed('Load time to reach this checkpoint');

Need to watch your memory usage? Just use the **log_memory** method to see the memory currently used:

	Profiler::log_memory('A message to keep track of where I am');

You can even keep track of the memory used by a variable:

	$somevariable = 'somevalue';

	Profiler::log_memory('my variable', $somevariable);

Of course, you can also log errors:

	Profiler::log_error(new Exception, 'Oops I did a mistake!');