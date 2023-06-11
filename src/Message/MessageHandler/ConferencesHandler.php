<?php

declare(strict_types=1);

namespace App\Message\MessageHandler;

use App\Entity\CfpEvents;
use App\Message\Conferences;
use App\Repository\CfpEventsRepository;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ConferencesHandler
{
    public function __construct(
        private readonly CfpEventsRepository $cfpEventsRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Conferences $conferences)
    {
        $conferences = $conferences->getConferences();
        foreach ($conferences as $conference) {
            if ($this->cfpEventsRepository->findOneBy(['handle' => $conference[1], 'fullName' => $conference[2]])) {
                continue;
            }

            $cfpEvents = new CfpEvents();
            $cfpEvents->setCfpLink($conference[0]);
            $cfpEvents->setHandle($conference[1]);

            if ($conference[1]) {
                $matches = [];
                preg_match('/\s*(\d{4})\s*/', $conference[1], $matches);
                if (!empty($matches)) {
                    $cfpEvents->setClearHandle(str_replace($matches[0], '', $conference[1]));
                }
            }

            $cfpEvents->setFullName($conference[2]);

            $matches = [];
            preg_match('/\s*\((.*?)\)\s*/', $conference[2], $matches);
            if (!empty($matches)) {
                $cfpEvents->setClearFullName(str_replace($matches[0], '', $conference[2]));
            }

            $cfpEvents->setLocation($conference[4]);
            $cfpEvents->setSubmitDate($conference[5]);

            if ($conference[5]) {
                $matches = [];
                preg_match( '/(\w{3} \d{1,2}, \d{4})/', $conference[5], $matches);
                if (!empty($matches)) {
                    $cfpEvents->setSubmitDateFormat(DateTimeImmutable::createFromFormat("M d, Y", $matches[0]));
                }
            }
            if ($conference[1]) {
                $matches = [];
                preg_match( '/(\d{4})/', $conference[1], $matches);
                if (!empty($matches)) {
                    $cfpEvents->setYear($matches[0]);
                }
            }

            if ($conference[3]) {
                $matches = [];
                preg_match_all( '/(\w{3} \d{1,2}, \d{4})/', $conference[3], $matches);
                if (!empty($matches[0])) {
                    $cfpEvents->setBeginDate($matches[0][0]);
                    $cfpEvents->setBeginDateFormat(DateTimeImmutable::createFromFormat("M d, Y", $matches[0][0]));
                    $cfpEvents->setFinishDate($matches[0][1]);
                    $cfpEvents->setFinishDateFormat(DateTimeImmutable::createFromFormat("M d, Y", $matches[0][1]));
                }
            }
            $this->cfpEventsRepository->save($cfpEvents);
        }
        $this->cfpEventsRepository->flush();

        $this->logger->info("Done");
    }
}