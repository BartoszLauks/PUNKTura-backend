<?php

namespace App\Controller;

use App\Repository\CfpEventsRepository;
use App\Repository\MeinEventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/events', name: 'events_')]
class EventsController extends AbstractController
{
    public function __construct(
        private readonly CfpEventsRepository $cfpEventsRepository,
        private readonly MeinEventsRepository $meinEventsRepository
    ) {
    }

    #[Route('/cfp', name: 'app_cfp', methods: ['GET'])]
    public function cfp(Request $request): JsonResponse
    {
        return $this->json($this->cfpEventsRepository->findEventsFilters($request->query->all()));
    }

    #[Route('/mein', name: 'app_mein', methods: ['GET'])]
    public function mein(Request $request): JsonResponse
    {
        return $this->json($this->meinEventsRepository->findEventsFilters($request->query->all()));
    }
}
