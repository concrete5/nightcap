<?php
namespace Concrete\Nightcap;

use Concrete\Nightcap\Service\Description\DescriptionInterface;
use Concrete\Nightcap\Service\ServiceCollection;
use Concrete\Nightcap\Service\ServiceDescriptionFactory;
use GuzzleHttp\Command\Guzzle\Description;

class Client
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var ServiceClientFactory
     */
    protected $serviceClientFactory;

    /**
     * @var ServiceCollection
     */
    protected $serviceCollection;

    /**
     * @var ServiceDescriptionFactory
     */
    protected $serviceDescriptionFactory;


    /**
     * @var DescriptionInterface[]
     */
    protected $descriptions = [];

    public function __construct(
        \GuzzleHttp\Client $httpClient,
        ServiceCollection $serviceCollection,
        ServiceClientFactory $serviceClientFactory,
        ServiceDescriptionFactory $serviceDescriptionFactory
    )
    {
        $this->httpClient = $httpClient;
        $this->serviceCollection = $serviceCollection;
        $this->serviceClientFactory = $serviceClientFactory;
        $this->serviceDescriptionFactory = $serviceDescriptionFactory;
    }

    public function getWebServiceClient($name)
    {
        return $this->serviceClientFactory->createServiceClient(
            $this->httpClient,
            $this->getServiceDescription($name)
        );
    }

    public function addServiceDescription(DescriptionInterface $description)
    {
        $this->serviceCollection->add($description);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient(): \GuzzleHttp\Client
    {
        return $this->httpClient;
    }

    /**
     * @return DescriptionInterface[]
     */
    public function getServiceDescriptions()
    {
        return $this->serviceCollection->toArray();
    }

    protected function getServiceDescription($namespace)
    {
        /**
         * @var DescriptionInterface $description
         */
        $description = $this->serviceCollection->get($namespace);
        return $this->serviceDescriptionFactory->createServiceDescription($this->httpClient, $description);
    }

    public function system()
    {
        return $this->getWebServiceClient('system');
    }

    public function site()
    {
        return $this->getWebServiceClient('site');
    }

    public function account()
    {
        return $this->getWebServiceClient('account');
    }

    public function __call($name, $arguments)
    {
        // Handles dynamic parsing of the service client, allowing packages
        // to add to this via config.
        return $this->getWebServiceClient($name);
    }


}