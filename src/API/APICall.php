<?php

namespace ATK4HereXYZ\API;

use atk4\core\DIContainerTrait;
use atk4\ui\Exception;
use Unirest\Response;

class APICall
{
    use DIContainerTrait;

    /** @var string */
    protected $app_id;

    /** @var string */
    protected $app_code;

    protected $header = [];

    /** @var string refer domain need to be specified if whitelist feature is used */
    protected $referer_domain;

    public function __construct(array $defaults = [])
    {
        $this->setDefaults($defaults);

        if (empty($this->referer_domain))
        {
            $this->referer_domain = gethostname();
        }

        $this->header['referer'] = $this->referer_domain;

        if (empty($this->app_id) || empty($this->app_code)) {
            throw new Exception('Here.com API keys not set!');
        }
    }

    /**
     * @param Response $response
     *
     * @throws Exception
     * @return array
     */
    public function responseOrThrow(Response $response): array
    {
        if ($response === 200) {
            return $response->body;
        }

        throw new Exception($response->code . ' ' . $response->raw_body);
    }
}