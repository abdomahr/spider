<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $contact_number;
    public string $contact_email;
    public int $provider_min_images_count;
    public int $provider_max_images_count;
    public float $site_lat;
    public float $site_lng;
    public string $whatsapp_number;
    public string $facebook_link;
    public string $insta_link;
    public string $x_link;
    public string $tiktok_link;
    public string $vat_no;
    public string $com_no;
    public int $vat;
    // public int $myfatoorah_expiry_time;
    public bool $is_vat_active;

    // Locale-based settings
    public array $app_name;
    public string $client_google_play_url;
    public string $client_apple_store_url;
    public string $provider_google_play_url;
    public string $provider_apple_store_url;

    public array $contact_location;
    public array $commercial_name;
    public array $client_terms;
    public array $client_policy;
    public array $provider_terms;
    public array $provider_policy;
    // public array $about_title;
    public array $about_desc;

    public static function group(): string
    {
        return 'general';
    }
}
