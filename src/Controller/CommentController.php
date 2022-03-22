<?php


namespace App\Controller;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comments/{id}/vote/{direction<up|down>}", methods="POST",name="app_comment_vote")
     */
    public function commentVote($id, $direction, LoggerInterface $logger)
    {
        //todo use id query database

        if ($direction === "up") {
            $logger->info('Voting up');
            $currentVoteCount = rand(0, 5);
        } else {
            $logger->warning('Voting up');
            $currentVoteCount = rand(5, 10);
        }

        return $this->json([
            'votes' => $currentVoteCount,
        ]);
    }
}