# Profiler Bundle

## Installation

To download the bundle run:

	php artisan bundle:install profiler

To install it run the following command:

	php artisan bundle:publish

Once that is complete you will need to add the bundle to the **auto** array in the application config file.

## Using Profiler

There are several ways you can load the profiler onto your site. You can simply load Profiler through a view:

	echo View::make('profiler::display');

If you want, you can simply nest the view within your own. Or, you can simply use the profiler filter:

	public function __construct()
	{
		$this->filter('after', 'profiler');
	}
