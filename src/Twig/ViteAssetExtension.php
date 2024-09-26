<?php

namespace App\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private string $manifestPath;
    private ?array $manifest = null;

    public function __construct(KernelInterface $kernel)
    {
        // Point to the manifest.json file in /public/build/.vite/manifest.json
        $this->manifestPath = $kernel->getProjectDir().'/public/build/manifest.json';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'getViteAssetPath']),
            new TwigFunction('vite_css_assets', [$this, 'getViteCssAssets']),
        ];
    }

    /**
     * Get the path to a JS asset in the manifest.
     */
    public function getViteAssetPath(string $asset): string
    {
        $this->loadManifest();

        // Check if the asset exists in the manifest
        if (!isset($this->manifest[$asset])) {
            throw new \RuntimeException(sprintf('Unable to find asset "%s" in manifest.json.', $asset));
        }

        // Return the path to the JS file (e.g., 'assets/main-D2Zdwx3B.js')
        return '/heroes-app/build/'.$this->manifest[$asset]['file'];
    }

    /**
     * Get the paths to any CSS assets associated with a JS entry in the manifest.
     */
    public function getViteCssAssets(string $asset): array
    {
        $this->loadManifest();

        // Check if the asset exists in the manifest and has CSS files
        if (!isset($this->manifest[$asset])) {
            throw new \RuntimeException(sprintf('Unable to find asset "%s" in manifest.json.', $asset));
        }

        return $this->manifest[$asset]['css'] ?? [];
    }

    /**
     * Load and cache the manifest.json file.
     */
    private function loadManifest(): void
    {
        if (null === $this->manifest) {
            if (!file_exists($this->manifestPath)) {
                throw new \RuntimeException('The manifest.json file does not exist. Run `npm run build` to generate it.');
            }

            $this->manifest = json_decode(file_get_contents($this->manifestPath), true);
        }
    }
}
