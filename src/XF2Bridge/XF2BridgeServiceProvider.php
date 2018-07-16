<?php namespace culv3r\XF2Bridge;

use Illuminate\Support\ServiceProvider;

class XF2BridgeServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
    {
        $this->app->singleton(XF2Bridge::class, function($app) {
            //Set Bridge loaded to true
            $app['XF2Bridge.loaded'] = true;

            $xenforoDir = config('xf2bridge.xenforo_directory_path');
            $xenforoBaseUrl = config('xf2bridge.xenforo_base_url_path');

            return new XF2Bridge($xenforoDir, $xenforoBaseUrl);
        });
    }
        
    public function boot()
    {
    	$configPath = __DIR__ .'/../config/xf2bridge.php';
    	$this->publishes([$configPath => config_path('xf2bridge.php')], 'config');


    	if(config('xf2bridge.use_xenforo_auth') === true)
        {
            \Auth::extend('xf2bridge',function($app) {

                return new XF2Guard($app->make(XF2Bridge::class));
            });
        }
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('xf2bridge', XF2Bridge::class);
	}

}