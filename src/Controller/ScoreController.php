<?php

namespace App\Controller;

use App\Entity\ScoreEntry;
use App\Repository\PlayerRepository;
use App\Repository\ScoreEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ScoreController extends AbstractController
{
    #[Route('/api/score/submit', name: 'score_submit', methods: ['POST'])]
    public function submit(
        Request $request,
        PlayerRepository $playerRepository,
        ScoreEntryRepository $scoreEntryRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $playerId = $data['id']        ?? null;
        $score    = $data['score']     ?? 0;
        $level    = $data['level']     ?? 1;
        $mode     = $data['mode']      ?? 'Levels';

        // Find player
        $player = $playerRepository->find($playerId);

        if (!$player) {
            return $this->json(['error' => 'Player not found'], 404);
        }

        // Save score entry
        $entry = new ScoreEntry();
        $entry->setPlayer($player);
        $entry->setScore($score);
        $entry->setLevel($level);
        $entry->setMode($mode);
        $entry->setPlayedAt(new \DateTime());

        $em->persist($entry);

        // Update highest score if beaten
        if ($score > $player->getHighestScore()) {
            $player->setHighestScore($score);
            $player->setUpdatedAt(new \DateTime());
        }

        $em->flush();

        // Get leaderboard — players sorted by highest score
        $leaderboard = $playerRepository->findBy(
            [],
            ['highestScore' => 'DESC'],
            10
        );

        $table = array_map(function ($p, $index) {
            return [
                'rank'          => $index + 1,
                'nickname'      => $p->getNickname(),
                'highest_score' => $p->getHighestScore(),
            ];
        }, $leaderboard, array_keys($leaderboard));

        return $this->json([
            'message'     => 'Score saved',
            'leaderboard' => $table
        ]);
    }
}
