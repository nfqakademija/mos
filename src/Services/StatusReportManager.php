<?php

namespace App\Services;

use App\Repository\RegionRepository;
use App\Repository\UserRepository;

class StatusReportManager extends ReportManager
{

    private $userRepository;

    private $regionRepository;

    public function __construct(UserRepository $userRepository, RegionRepository $regionRepository)
    {
        $this->userRepository = $userRepository;
        $this->regionRepository = $regionRepository;
    }

    /**
     * Reports current project status. Only finished groups are considered.
     */
    public function getStatusReport()
    {
        $result = [
            'allParticipantsCount' => 0,
            'inProblematicRegionsTotal' => 0,
            'inProblematicRegions' => [],
            'olderThan45' => 0,
            'olderThan45InCountrySide' => 0,
            'olderThan45Woman' => 0,
        ];

        $result['allParticipantsCount'] = $this->userRepository->getCountAllFinishedParticipants();

        $problematicRegions = $this->regionRepository->findBy(["isProblematic" => true]);
        foreach ($problematicRegions as $region) {
            $participantsInRegion = $this->userRepository->getParticipantsCountInRegionId($region->getId());
            $result['inProblematicRegions'][] = [
                'title' => $region->getTitle(),
                'participantsCount' => $participantsInRegion,
            ];
            $result['inProblematicRegionsTotal'] += $participantsInRegion;
        }

        $result['olderThan45'] = $this->userRepository->getOlderThan(45);
        $result['olderThan45InCountrySide'] = $this->userRepository->getOlderThanAndInAreaType(45, 'kaimas');
        $result['olderThan45Woman'] = $this->userRepository->getOlderThanAndIsGender(45, 'moteris');

        return $result;
    }
}
