<?php
namespace Zoe\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
class AssetManagerServiceProvider extends ServiceProvider{
    public function register()
    {
       // $this->mergeConfigFrom($configPath, 'asset-manager');
        $this->app->singleton('asset-manager', function () {;
            return new \Zoe\AssetManager([]);
        });
    }
    public function boot(){
        Blade::directive('css', function ($expression) {
            $expression = $this->parseExpression($expression);
            return "<?php echo app('asset-manager')->css($expression) ?>";
        });
        Blade::directive('js', function ($expression) {
            $expression = $this->parseExpression($expression);
            return "<?php echo app('asset-manager')->js($expression) ?>";
        });
    }
    protected function parseExpression($expression)
    {
        if (starts_with($expression, '(')) {
            $expression = substr($expression, 1, -1);
        }
        //$expression = str_replace(["'"], '', $expression);
        return $expression;
    }
}