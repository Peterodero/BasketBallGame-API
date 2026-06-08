<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route as AttributeRoute;

class PlayerController extends AbstractController
{
    #[AttributeRoute('/api/player/login', name: 'player_login', methods: ['POST'])]
    public function login(
        Request $request,
        PlayerRepository $playerRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $nickname = trim($data['nickname'] ?? '');

        if (empty($nickname)) {
            return $this->json([
                'error' => 'Nickname is required'
            ], 400);
        }

        // Check if player exists
        $player = $playerRepository->findOneBy(['nickname' => $nickname]);

        if (!$player) {
            // Create new player
            $player = new Player();
            $player->setNickname($nickname);
            $player->setHighestScore(0);
            $player->setCreatedAt(new \DateTime());
            $player->setUpdatedAt(new \DateTime());

            $em->persist($player);
            $em->flush();
        }

        return $this->json([
            'id'             => $player->getId(),
            'nickname'       => $player->getNickname(),
            'highest_score'  => $player->getHighestScore(),
        ]);
    }
}