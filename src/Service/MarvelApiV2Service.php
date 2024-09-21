<?php

namespace App\Service;

use App\Repository\HeroRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MarvelApiV2Service
{
    private HeroRepository $heroRepository;

    public function __construct(HeroRepository $heroRepository)
    {
        $this->heroRepository = $heroRepository;
    }

    /**
     * Merges character JSON data from the database based on provided IDs and returns the merged array.
     *
     * @throws NotFoundHttpException if any character's data is not found
     */
    public function mergeCharacterJsonData(array $characterIds, int $limit): array
    {
        $characterIds = array_slice($characterIds, 0, $limit); // Apply limit

        $mergedData = [
            'code' => 200,
            'status' => 'Ok',
            'copyright' => '© 2024 MARVEL',
            'attributionText' => 'Data provided by Marvel. © 2024 MARVEL',
            'attributionHTML' => '<a href="http://marvel.com">Data provided by Marvel. © 2024 MARVEL</a>',
            'etag' => 'merged-etag',
            'data' => [
                'offset' => 0,
                'limit' => $limit,
                'total' => count($characterIds),
                'count' => count($characterIds),
                'results' => [],
            ],
        ];

        $processedIds = []; // To keep track of processed IDs and avoid duplicates

        foreach ($characterIds as $id) {
            if (in_array($id, $processedIds)) {
                continue; // Skip if this ID has already been processed
            }

            $hero = $this->heroRepository->findOneBy(['characterId' => $id]);

            if (!$hero) {
                throw new NotFoundHttpException("Character with ID $id not found");
            }
            $characterData = $hero->getData();
            $mergedData['data']['results'][] = $characterData;
            $processedIds[] = $id;
        }

        return $mergedData;
    }

    /**
     * Fetches characters whose names start with the given string and merges their data into an array.
     */
    public function mergeCharactersByNameStartsWith(string $nameStartsWith, int $limit): array
    {
        $heroes = $this->heroRepository->findByNameStartsWith($nameStartsWith, $limit); // Pass limit to the repository

        $mergedData = [
            'code' => 200,
            'status' => 'Ok',
            'copyright' => '© 2024 MARVEL',
            'attributionText' => 'Data provided by Marvel. © 2024 MARVEL',
            'attributionHTML' => '<a href="http://marvel.com">Data provided by Marvel. © 2024 MARVEL</a>',
            'etag' => 'merged-etag',
            'data' => [
                'offset' => 0,
                'limit' => min($limit, count($heroes)),
                'total' => count($heroes),
                'count' => count($heroes),
                'results' => [],
            ],
        ];

        foreach ($heroes as $hero) {
            $characterData = $hero->getData();
            $mergedData['data']['results'][] = $characterData;
        }

        return $mergedData;
    }
}
