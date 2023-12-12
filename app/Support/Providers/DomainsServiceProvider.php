<?php

namespace Support\Providers;

use Domain\CircuitReservations\Providers\CircuitReservationsDomainServiceProvider;
use Domain\Clients\Providers\ClientsDomainServiceProvider;
use Domain\Companies\Providers\CompaniesDomainServiceProvider;
use Domain\Discounts\Providers\DiscountsDomainServiceProvider;
use Domain\Employees\Providers\EmployeesDomainServiceProvider;
use Domain\Festives\Providers\FestivesDomainServiceProvider;
use Domain\Localities\Providers\LocalitiesDomainServiceProvider;
use Domain\Orders\Providers\OrdersDomainServiceProvider;
use Domain\Payments\Providers\PaymentsDomainServiceProvider;
use Domain\Gyms\Providers\GymsDomainServiceProvider;
use Domain\Invoices\Providers\InvoicesDomainServiceProvider;
use Domain\Products\Providers\ProductsDomainServiceProvider;
use Domain\SaleSessions\Providers\SaleSessionsDomainServiceProvider;
use Domain\TreatmentReservations\Providers\TreatmentReservationsDomainServiceProvider;
use Domain\TreatmentScheduleNotes\Providers\TreatmentScheduleNotesDomainServiceProvider;
use Domain\Users\Providers\UsersDomainServiceProvider;
use Illuminate\Support\ServiceProvider;

class DomainsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(CircuitReservationsDomainServiceProvider::class);
        $this->app->register(ClientsDomainServiceProvider::class);
        $this->app->register(DiscountsDomainServiceProvider::class);
        $this->app->register(CompaniesDomainServiceProvider::class);
        $this->app->register(EmployeesDomainServiceProvider::class);
        $this->app->register(FestivesDomainServiceProvider::class);
        $this->app->register(LocalitiesDomainServiceProvider::class);
        $this->app->register(OrdersDomainServiceProvider::class);
        $this->app->register(PaymentsDomainServiceProvider::class);
        $this->app->register(GymsDomainServiceProvider::class);
        $this->app->register(InvoicesDomainServiceProvider::class);
        $this->app->register(ProductsDomainServiceProvider::class);
        $this->app->register(SaleSessionsDomainServiceProvider::class);
        $this->app->register(TreatmentReservationsDomainServiceProvider::class);
        $this->app->register(UsersDomainServiceProvider::class);
        $this->app->register(TreatmentScheduleNotesDomainServiceProvider::class);
    }
}
