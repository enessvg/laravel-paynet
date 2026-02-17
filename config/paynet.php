<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paynet API Anahtarı
    |--------------------------------------------------------------------------
    |
    | Paynet panelinden aldığınız Secret Key burada tanımlanır.
    |
    */
    'secret_key' => env('PAYNET_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Paynet Public Key
    |--------------------------------------------------------------------------
    |
    | Paynet JS widget için kullanılan Public Key.
    |
    */
    'public_key' => env('PAYNET_PUBLIC_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Canlı Mod
    |--------------------------------------------------------------------------
    |
    | true: Canlı ortam (api.paynet.com.tr)
    | false: Test ortamı (pts-api.paynet.com.tr)
    |
    */
    'is_live' => env('PAYNET_IS_LIVE', false),

    /*
    |--------------------------------------------------------------------------
    | Varsayılan POS Tipi
    |--------------------------------------------------------------------------
    |
    | 5: Sanal POS (varsayılan)
    |
    */
    'default_pos_type' => env('PAYNET_DEFAULT_POS_TYPE', 5),

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Taksit Sayısı
    |--------------------------------------------------------------------------
    |
    | 0: Tek çekim (varsayılan)
    |
    */
    'default_instalment' => env('PAYNET_DEFAULT_INSTALMENT', 0),

    /*
    |--------------------------------------------------------------------------
    | 3D Secure Dönüş URL'i
    |--------------------------------------------------------------------------
    |
    | 3D Secure işlemleri için dönüş URL'i.
    |
    */
    'tds_return_url' => env('PAYNET_TDS_RETURN_URL', ''),
];
