<?php

namespace Xtrategie\Clients;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Xtrategie\Clients\Nova\Client;

class Clients extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('clients', __DIR__.'/../dist/js/tool.js');
        Nova::style('clients', __DIR__.'/../dist/css/tool.css');

        Nova::resources([
            Client::class
        ]);
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('clients::navigation');
    }
}
