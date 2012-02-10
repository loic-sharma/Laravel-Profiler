# Profiler Bundle

## Installation

To download the bundle run:

	php artisan bundle:install profiler

Once the bundle has been downloaded you will need to add the bundle to the **auto** array in the application config file.

## Using Profiler

There are several ways you can load the profiler onto your site. For example you can simply load the profiler's view:

	echo View::make('profiler::display');

If you want, you can simply nest the view withing your own. Or, you can simply use the profiler filter:

	public function __construct()
	{
		$this->filter('after', 'profiler');
	}