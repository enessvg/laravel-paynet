<?php

namespace Paynet;

use Illuminate\Support\ServiceProvider;

class PaynetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Config dosyasını yayınla
        $this->publishes([
            __DIR__ . '/../config/paynet.php' => config_path('paynet.php'),
        ], 'paynet-config');

        // Config dosyasını birleştir
        $this->mergeConfigFrom(
            __DIR__ . '/../config/paynet.php',
            'paynet'
        );
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // PaynetClient singleton olarak kaydet
        $this->app->singleton(PaynetClient::class, function ($app) {
            $config = $app['config']['paynet'];

            if (empty($config['secret_key'])) {
                throw new \InvalidArgumentException(
                    'Paynet secret key yapılandırılmamış. Lütfen PAYNET_SECRET_KEY env değişkenini ayarlayın.'
                );
            }

            return new PaynetClient(
                secretKey: $config['secret_key'],
                isLive: (bool) ($config['is_live'] ?? false),
            );
        });

        // Alias
        $this->app->alias(PaynetClient::class, 'paynet');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            PaynetClient::class,
            'paynet',
        ];
    }
}
