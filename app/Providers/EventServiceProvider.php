<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\EmployeeExport;
use App\Listeners\EmployeeExportNotification;

use App\Events\PriceFileExport;
use App\Events\PriceFileImport;
use App\Listeners\PriceFileExportNotification;
use App\Listeners\PriceFileImportNotification;

use App\Events\ProductExport;
use App\Listeners\ProductExportNotification;

use App\Events\RtsCodesExport;
use App\Listeners\RtsCodesExportNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EmployeeExport::class => [
            EmployeeExportNotification::class,
        ],
        PriceFileExport::class => [
            PriceFileExportNotification::class,
        ],
        PriceFileImport::class => [
            PriceFileImportNotification::class,
        ],
        RtsCodesExport::class => [
            RtsCodesExportNotification::class,
        ],
        ProductExport::class => [
            ProductExportNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
