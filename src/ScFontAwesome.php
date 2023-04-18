<?php

namespace Squareconcepts\ScFontAwesome;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Livewire\Wireable;

class ScFontAwesome implements Wireable
{
    private ?string $accessToken;
    private ?Carbon $expiresAt;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (empty(config('sc-fontawesome.api_token'))) {
            throw new Exception('Please provide font awesome api token');
        }
    }

    private function authenticate(): bool
    {
        $response = Http::withToken(config('sc-fontawesome.api_token'))->post('https://api.fontawesome.com/token');

        if ($response->failed()) {
            return false;
        }

        $json = $response->json();

        if (!empty($json)) {
            if (!empty($json['access_token']) && !empty($json['expires_in'])) {
                $this->accessToken = $json['access_token'];
                $this->expiresAt = now()->addSeconds($json['expires_in']);
                return true;
            }
        }

        return false;
    }

    public function searchIcon($search): array
    {
        if (empty($this->expiresAt) || now()->isAfter($this->expiresAt)) {
            if (!$this->authenticate()) {
                return ["success" => false, "message" => "Something went wrong while trying to authenticate"];
            }
        }

        $response = Http::withToken($this->accessToken)
            ->post('https://api.fontawesome.com', ['query' => "{ search (version: \"6.0.0\", query: \"$search\", first: 5) { id }}"]);

        $json = $response->json();

        if (!empty($json) && !empty($json['data']) && isset($json['data']['search'])) {
            return ["success" => true, "data" => array_column($json['data']['search'], 'id')];
        }

        return ["success" => false, "message" => "Something went wrong while trying to search for icons"];
    }

    public static function getStyles(): array
    {
        return [
            ["name" => "Font Awesome Solid", "value" => "fa-solid"],
            ["name" => "Font Awesome Brands", "value" => "fa-brands"],
            ["name" => "Font Awesome Regular", "value" => "fa-regular"],
            ["name" => "Font Awesome Light", "value" => "fa-light"],
            ["name" => "Font Awesome Thin", "value" => "fa-thin"],
            ["name" => "Font Awesome Duotone", "value" => "fa-duotone"],
            ["name" => "Font Awesome Sharp Solid", "value" => "fa-sharp fa-solid"],
        ];
    }

    public static function fromLivewire($value)
    {
        $service = new static();
        $service->accessToken = $value['accessToken'] ?? null;
        $service->expiresAt = !empty($value['expiresAt']) ? Carbon::parse($value['expiresAt']) : null;
        return $service;
    }

    public function toLivewire()
    {
        return ['accessToken' => $this->accessToken ?? null, 'expiresAt' => $this->expiresAt ?? null];
    }
}
