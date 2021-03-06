<?php
namespace Concrete\Nightcap\OAuth2\Configuration;

interface ConfigurationInterface
{

    public function getClientId();

    public function getBaseUrl();

    /**
     * @return string[]
     */
    public function getScopes();

}