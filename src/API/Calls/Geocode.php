<?php

declare(strict_types=1);

namespace ATK4HereXYZ\API\Calls;

use ATK4HereXYZ\API\APICall;
use Unirest\Request;

class Geocode extends APICall
{
    public $url = 'http://geocoder.api.here.com/6.2/geocode.json';

    /**
     * Get Location by previously retrieved Geocode API Location ID.
     *
     * @param string $location_id
     *
     * @throws \atk4\ui\Exception
     *
     * @return array
     */
    public function getLocationByID(string $location_id): array
    {
        $parameters = [
            'app_id'     => $this->app_id,
            'app_code'   => $this->app_code,
            'locationid' => $location_id,
        ];

        return $this->responseOrThrow(Request::get($this->url, $this->header, $parameters));
    }
}
