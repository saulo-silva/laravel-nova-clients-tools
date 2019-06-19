<?php

namespace Xtrategie\Clients\Nova\Actions;

use Xtrategie\Clients\Models\Client as ClientModel;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;
//use Laravel\Nova\Fields\Text;

class Active extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //
        foreach ($models as $model) {
            $model->update([
               'ind_status' =>  $fields->ind_status
            ]);
        }

        return Action::message('Registros atualizados!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Situação', 'ind_status')
                ->options(ClientModel::statusesLabel)
                ->rules('required')
        ];
    }
}
