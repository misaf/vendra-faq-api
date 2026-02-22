<?php

declare(strict_types=1);

namespace Misaf\VendraFaqApi;

use Illuminate\Foundation\Console\AboutCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class FaqApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('vendra-faq-api');
    }

    public function packageBooted(): void
    {
        AboutCommand::add('Vendra Faq API', fn() => ['Version' => 'dev-master']);
    }
}
