<?php

namespace atk4\ui\FormField;

use atk4\core\HookTrait;
use ATK4HereXYZ\API\Suggest;
use Throwable;

class HERESuggest extends AutoComplete
{
    use HookTrait;

    public $api_options = [];

    public $label = ['icon' => 'search'];

    /**
     * override getData.
     */
    public function getData(): void
    {
        $data  = [];
        $query = $_GET['q'] ?? '';

        // if query string not empty call API
        if (!empty($query)) {
            $data = $this->getSuggestData($query);
        }

        $this->app->outputResponseJSON(
            [
                'success' => true,
                'results' => $data,
            ]
        );

        $this->app->terminate();
    }

    /**
     * Get results from Rest Suggest.
     *
     * @param string $search
     *
     * @return array
     */
    private function getSuggestData(string $search): array
    {
        $data = [];

        try {
            $api  = new Suggest($this->api_options);
            $data = $api->getSuggest($search);
            foreach ($data->suggestions as $location) {
                $hook_value = $this->hook('onSuggestValue', [$location]);
                $hook_label = $this->hook('onSuggestLabel', [$location]);
                $data[]     = [
                    'id'   => $hook_value ?? $location->locationId,
                    'name' => $hook_label ?? $location->label,
                ];
            }
        } catch (Throwable $e) {
            $this->app->outputResponseJSON(
                [
                    'success' => false,
                    'results' => [
                        ['' => $e->getMessage()],
                    ],
                ]
            );
            $this->app->terminate();
        }

        return $data;
    }
}