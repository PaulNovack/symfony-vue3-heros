<?php

namespace App\Command;

use App\Entity\Hero;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch-marvel-characters',
    description: 'Updates the Local Cache in db of marvel characters.',
)]
class FetchMarvelCharactersCommand extends Command
{
    protected static $defaultName = 'app:fetch-marvel-characters';

    private EntityManagerInterface $entityManager;
    private Client $client;
    private string $publicKey;
    private string $privateKey;
    private string $baseUrl = 'https://gateway.marvel.com:443/v1/public/characters';

    public function __construct(EntityManagerInterface $entityManager, string $publicKey, string $privateKey)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->client = new Client();
        $this->publicKey = $publicKey;  // Injected from .env
        $this->privateKey = $privateKey;  // Injected from .env
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fetch characters from Marvel API and insert into the database with full JSON data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $ts = time();
        $hash = md5($ts.$this->privateKey.$this->publicKey);

        // Generate all letter combinations ("aa", "ab", ..., "zz")
        $letterCombinations = $this->generateLetterCombinations();

        // Loop through all combinations and fetch characters
        foreach ($letterCombinations as $letters) {
            $io->info("Fetching characters starting with: $letters");

            $response = $this->client->request('GET', $this->baseUrl, [
                'query' => [
                    'apikey' => $this->publicKey,
                    'ts' => $ts,
                    'hash' => $hash,
                    'nameStartsWith' => $letters,
                    'limit' => 100, // Adjust limit as per your need
                ],
            ]);

            // Get response body
            $body = $response->getBody();
            $data = json_decode($body, true);

            // Process results and save to the database
            if (isset($data['data']['results']) && count($data['data']['results']) > 0) {
                foreach ($data['data']['results'] as $character) {
                    $this->saveCharacter($character);  // Changed to pass only the character data
                    $io->success("Inserted/Updated: {$character['name']}");
                }
            } else {
                $io->warning("No characters found for: $letters");
            }
        }

        $io->success('All characters fetched and saved successfully.');
        // After all records have been processed, flush remaining entities
        $this->entityManager->flush();  // Flush any remaining unflushed entities
        $this->entityManager->clear();  // Clear the entity manager

        return Command::SUCCESS;
    }

    private function generateLetterCombinations(): array
    {
        $combinations = [];
        foreach (range('a', 'z') as $firstLetter) {
            foreach (range('a', 'z') as $secondLetter) {
                $combinations[] = $firstLetter.$secondLetter;
            }
        }

        return $combinations;
    }

    private function saveCharacter(array $characterData): void
    {
        $heroRepository = $this->entityManager->getRepository(Hero::class);

        $hero = $heroRepository->findOneBy(['characterId' => $characterData['id']]);

        if (!$hero) {
            $hero = new Hero();
            $hero->setCharacterId($characterData['id']);
            $hero->setName($characterData['name']);
        }

        $hero->setData($characterData);

        $this->entityManager->persist($hero);

        // Optional: Call flush() periodically in a loop if processing a large number of records
        if (0 === $this->entityManager->getUnitOfWork()->size() % 50) {
            $this->entityManager->flush(); // Flush every 50 records
            $this->entityManager->clear(); // Detach entities to free up memory
        }
    }
}
