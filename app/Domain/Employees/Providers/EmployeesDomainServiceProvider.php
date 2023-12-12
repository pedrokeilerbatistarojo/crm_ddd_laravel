<?php

namespace Domain\Employees\Providers;

use Domain\Employees\Contracts\Repositories\EmployeesRepository as EmployeesRepositoryInterface;
use Domain\Employees\Contracts\Repositories\EmployeeTimeOffRepository as EmployeeTimeOffRepositoryInterface;
use Domain\Employees\Contracts\Repositories\EmployeeOrderRepository as EmployeeOrderRepositoryInterface;
use Domain\Employees\Contracts\Services\EmployeesService as EmployeesServiceInterface;
use Domain\Employees\Repositories\EmployeeOrderRepository;
use Domain\Employees\Repositories\EmployeesRepository;
use Domain\Employees\Repositories\EmployeeTimeOffRepository;
use Domain\Employees\Services\EmployeesService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class EmployeesDomainServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            // Repositories
            EmployeesRepositoryInterface::class,
            EmployeeTimeOffRepositoryInterface::class,
            EmployeeOrderRepositoryInterface::class,

            // Services
            EmployeesServiceInterface::class,
        ];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(EmployeesRepositoryInterface::class, EmployeesRepository::class);
        $this->app->bind(EmployeeTimeOffRepositoryInterface::class, EmployeeTimeOffRepository::class);
        $this->app->bind(EmployeeOrderRepositoryInterface::class, EmployeeOrderRepository::class);

        // Services
        $this->app->bind(EmployeesServiceInterface::class, EmployeesService::class);
    }
}
