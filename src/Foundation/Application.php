<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Phplrt\Io\Readable;
use Psr\Container\ContainerInterface as PSRContainer;
use Psr\SimpleCache\CacheInterface as PsrCacheInterface;
use Railt\Container\Container;
use Railt\Container\ContainerInterface;
use Railt\Container\Exception\ContainerInvocationException;
use Railt\Container\Exception\ContainerResolutionException;
use Railt\Container\Exception\ParameterResolutionException;
use Railt\Foundation\Application\CompilerExtension;
use Railt\Foundation\Application\Environment;
use Railt\Foundation\Application\EnvironmentInterface;
use Railt\Foundation\Application\HasConsoleApplication;
use Railt\Foundation\Application\ProvidesExtensions;
use Railt\Foundation\Config\DiscoveryRepository;
use Railt\Foundation\Config\Repository as ConfigRepository;
use Railt\Foundation\Config\RepositoryInterface as ConfigRepositoryInterface;
use Railt\Foundation\Event\EventsExtension;
use Railt\Foundation\Extension\Repository as ExtensionRepository;
use Railt\Foundation\Extension\RepositoryInterface as ExtensionRepositoryInterface;
use Railt\Foundation\Webonyx\WebonyxExtension;
use Symfony\Component\Cache\Psr16Cache;

/**
 * Class Application
 */
class Application extends Container implements ApplicationInterface
{
    use HasConsoleApplication;

    /**
     * @var string
     */
    public const VERSION = '1.4-dev';

    /**
     * @var string[]
     */
    private const KERNEL_EXTENSIONS = [
        EventsExtension::class,
        CompilerExtension::class,
        WebonyxExtension::class,
    ];

    /**
     * @var bool
     */
    private $booted = false;

    /**
     * @var PsrCacheInterface|null
     */
    private ?PsrCacheInterface $cache;

    /**
     * Application constructor.
     *
     * @param bool              $debug
     * @param PSRContainer|null $container
     * @param PsrCacheInterface $cache
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     * @throws \ReflectionException
     */
    public function __construct(bool $debug = false, PSRContainer $container = null, PsrCacheInterface $cache = null)
    {
        parent::__construct($container);
        $this->cache = $cache;

        $this->registerCache($debug);
        $this->registerBaseBindings($debug);
    }

    /**
     * @param string $extension
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     * @return ApplicationInterface|$this
     */
    public function extend(string $extension): ProvidesExtensions
    {
        $extensions = $this->make(ExtensionRepositoryInterface::class);
        $extensions->add($extension);

        return $this;
    }

    /**
     * @param Readable $schema
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     * @return ConnectionInterface
     */
    public function connect(Readable $schema): ConnectionInterface
    {
        $this->bootExtensions($this->make(ExtensionRepositoryInterface::class));

        return new Connection($this, $schema);
    }

    /**
     * @param bool $debug
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     * @throws \ReflectionException
     * @return void
     */
    private function registerBaseBindings(bool $debug): void
    {
        $this->registerEnvironment();
        $this->registerConfigsRepository($debug);
        $this->registerApplicationBindings();
        $this->registerExtensionsRepository();
    }

    /**
     * @return void
     */
    private function registerEnvironment(): void
    {
        $this->instance(EnvironmentInterface::class, new Environment());
        $this->alias(EnvironmentInterface::class, Environment::class);
    }

    /**
     * @param bool $debug
     * @throws \ReflectionException
     * @return void
     */
    private function registerConfigsRepository(bool $debug): void
    {
        $configs = new ConfigRepository(['debug' => $debug]);
        $configs->mergeWith(new DiscoveryRepository());

        $this->instance(ConfigRepositoryInterface::class, $configs);
        $this->alias(ConfigRepositoryInterface::class, ConfigRepository::class);
    }

    /**
     * @return void
     */
    private function registerApplicationBindings(): void
    {
        $this->instance(ContainerInterface::class, $this);
        $this->instance(ApplicationInterface::class, $this);
    }

    /**
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     * @return void
     */
    private function registerExtensionsRepository(): void
    {
        $this->instance(ExtensionRepositoryInterface::class, new ExtensionRepository($this));
        $this->alias(ExtensionRepositoryInterface::class, ExtensionRepository::class);

        $this->loadExtensions($this->make(ExtensionRepositoryInterface::class));
    }

    /**
     * @param ExtensionRepositoryInterface $extensions
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    private function loadExtensions(ExtensionRepositoryInterface $extensions): void
    {
        $configs = $this->make(ConfigRepositoryInterface::class);

        foreach (self::KERNEL_EXTENSIONS as $extension) {
            $extensions->add($extension);
        }

        foreach ((array)$configs->get(ConfigRepositoryInterface::KEY_EXTENSIONS) as $extension) {
            $extensions->add($extension);
        }
    }

    /**
     * @param ExtensionRepositoryInterface $extensions
     * @throws ContainerInvocationException
     * @throws ContainerResolutionException
     * @throws ParameterResolutionException
     */
    private function bootExtensions(ExtensionRepositoryInterface $extensions): void
    {
        $this->loadExtensions($extensions);

        $extensions->boot();
    }

    private function registerCache(bool $isDebug): void
    {
        if (!$isDebug && $this->cache) {
            $this->instance(PsrCacheInterface::class, $this->cache);
        }
    }
}
