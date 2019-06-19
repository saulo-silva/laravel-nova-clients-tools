<?php

namespace Xtrategie\Clients\Nova;

use Xtrategie\Clients\Nova\Actions\Active;
use Xtrategie\Clients\Nova\Filters\IndStatusFilter;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Stonkeep\CpfCnpj\CpfCnpj;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Select;
use Xtrategie\Clients\Models\Client as ClientModel;
use Laravel\Nova\Panel;
use App\Nova\Resource;
use Xtrategie\Clients\Nova\Metrics\NewClients;

class Client extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Xtrategie\Clients\Models\Client';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'email', 'document'
    ];

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string|null
     */
    public function subtitle()
    {
        return 'Client';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $panel_primary = new Panel('Informações Básicas', [
            ID::make()->sortable(),

            Text::make('Nome', 'name')
                ->sortable()
                ->rules('required'),

            CpfCnpj::make('CPF/CNPJ', 'document')
                ->sortable()
                ->rules('required', 'max:254')
                ->creationRules('unique:clients,document')
                ->updateRules('unique:clients,document,{{resourceId}}'),

            Text::make('E-mail', 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254'),
        ]);
        $panel_primary->withToolbar();

        $panel_secondary = new Panel('Outras Informações', [
            Text::make('Telefone', 'phone')
                ->sortable()
                ->nullable()
                ->hideFromIndex(),
            Badge::make('Status', function () {
                return ClientModel::statuses[$this->ind_status];
            })->label(function () {
                return ClientModel::statusesLabel[$this->ind_status];
            }),
            Select::make('Situação', 'ind_status')
                ->options(ClientModel::statusesLabel)
                ->onlyOnForms(),

            DateTime::make('Criado em', 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),

            KeyValue::make('Metadata')
                ->rules('json')
                ->canSeeWhen('viewMetadata', $this)
        ]);

        return [

            $panel_primary,

            $panel_secondary

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
            (new NewClients)->canSee(function ($request) {
                return $request->user()->email == 'admin@admin.com';
            }),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new IndStatusFilter()
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new Active()
        ];
    }
}
