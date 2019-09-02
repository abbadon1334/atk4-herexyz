<?php

declare(strict_types=1);

namespace atk4\ui\FormField;

use atk4\core\Exception;
use atk4\core\HookTrait;
use atk4\ui\Exception\ExitApplicationException;
use ATK4HereXYZ\API\Calls\Suggest;
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
     * Get results from REST Suggest.
     *
     * @param string $search
     *
     * @throws Exception
     * @throws ExitApplicationException
     *
     * @return array
     */
    private function getSuggestData(string $search): array
    {
        $data = [];

        try {
            $api  = new Suggest($this->api_options);
            foreach ($api->getSuggest($search) as $location) {
                $hook_value = $this->hook('onSuggestValue', [$location]);
                $hook_label = $this->hook('onSuggestLabel', [$location]);
                $data[]     = [
                    'id'   => empty($hook_value) ? $location->locationId : $hook_value,
                    'name' => empty($hook_label) ? $location->label : $hook_label,
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
