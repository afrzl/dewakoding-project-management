<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
use Filament\Facades\Filament;
use App\Support\ColorPalette;

class FilamentUserSettings
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $userId = auth()->id();
            
            $savedColor = $this->getSavedColor($userId);
            FilamentColor::register([
                'primary' => $this->getColorConstant($savedColor),
            ]);

            // Store navigation style in request for view to use
            // Don't modify panel directly as it's shared in Octane
            $navigationStyle = $this->getNavigationStyle($userId);
            $request->attributes->set('filament_navigation_style', $navigationStyle);
            
            // Also store in view share for blade templates
            view()->share('filamentNavigationStyle', $navigationStyle);
        }

        return $next($request);
    }

    private function getSavedColor($userId): string
    {
        try {
            return Setting::getUserValue('filament_primary_color', 'blue', $userId);
        } catch (\Exception $e) {
            return 'blue';
        }
    }

    private function getNavigationStyle($userId): string
    {
        try {
            return Setting::getUserValue('filament_navigation_style', 'sidebar', $userId);
        } catch (\Exception $e) {
            return 'sidebar';
        }
    }

    private function getColorConstant(string $colorName)
    {
        return ColorPalette::constantFor($colorName);
    }
}