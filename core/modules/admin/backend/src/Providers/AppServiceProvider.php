<?php
namespace Admin\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {

    }
    public function boot()
    {
        Blade::component('backend::layout.component.button-option', 'btn_option');
        Blade::component('backend::layout.component.breadcrumb', 'breadcrumb');
        \Form::component('btn_save', 'backend::layout.form.btn-save', ['name', 'value' => null,'label'=>null, 'attributes' => []]);
    }
}
