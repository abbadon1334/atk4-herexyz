<?php

namespace ATK4HereXYZ\API;

use Unirest\Request;

class Suggest extends APICall
{
    public $url = 'http://autocomplete.geocoder.api.here.com/6.2/suggest.json';

    public $highlight = [
        'start' => '<b>',
        'end'   => '</b>',
    ];

    public $limit = 20;

    /**
     * Get suggested results from autocomplete API based on query.
     *
     * @param string $query
     *
     * @throws \atk4\ui\Exception
     * @return array
     */
    public function getSuggest(string $query): array
    {
        $parameters = [
            'app_id'         => $this->app_id,
            'app_code'       => $this->app_code,
            'query'          => $query,
            'beginHighlight' => $this->highlight['start'],
            'endHighlight'   => $this->highlight['end'],
            'max_results'    => $this->limit,
        ];

        return $this->responseOrThrow(Request::get($this->url, $this->header, $parameters));
    }
}