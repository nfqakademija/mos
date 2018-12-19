<?php

namespace App\Services;

use App\Repository\UserRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsReportManager extends ReportManager
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * ReportManager constructor.
     *
     * @param \App\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Exports participants report to xlsx
     *
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function reportToExcel(\DateTime $dateFrom, \DateTime $dateTo): array
    {
        $participantsReport = $this->userRepository->getParticipantsByGroupPeriod($dateFrom, $dateTo);

        $spreadsheet = new Spreadsheet();

        /** @var $sheet \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $this->setParticipantsReportHeader($sheet, $dateFrom, $dateTo);

        $this->setParticipantsReportTable($sheet, $participantsReport);

        $fileName = 'ParticipantsReport_' . $dateFrom->format('Y-m-d') . '-' . $dateTo->format('Y-m-d') . '.xlsx';
        $tempFileWithName = $this->writeToTempFile($spreadsheet, $fileName);

        return $tempFileWithName;
    }

    private function setParticipantsReportTable(Worksheet &$sheet, $participantsReport)
    {
        $tableStyles = $this->getTableStyles();
        $headerStyles = array_merge($tableStyles, ['font' => ['bold' => true]]);

        $cols = [
            'A' => 'Vardas',
            'B' => 'Pavardė',
            'C' => 'Gimimo data',
            'D' => 'Rajonas / miestas',
            'E' => 'Adresas',
            'F' => 'Vietovės tipas',
            'G' => 'Tel. nr.',
            'H' => 'El. paštas',
            'I' => 'Lytis',
            'J' => 'Mokymų pradžia',
            'K' => 'Pabaiga',
            'L' => 'Grupės Nr.',
        ];

        //set report table headers
        $row = 9;
        foreach ($cols as $colAddr => $colTitle) {
            $cellAddress = $colAddr . $row;
            $sheet->setCellValue($cellAddress, $colTitle);
            $sheet->getStyle($cellAddress)->applyFromArray($headerStyles);
        }

        $row = 10;
        /** @var User $participant */
        foreach ($participantsReport as $participant) {
            $sheet->setCellValue('A' . $row, $participant->getName());
            $sheet->setCellValue('B' . $row, $participant->getSurname());
            $sheet->setCellValue('C' . $row, $participant->getBirthDate());
            if (!empty($participant->getRegion())) {
                $sheet->setCellValue('D' . $row, $participant->getRegion()->getTitle());
            } else {
                $sheet->setCellValue('D' . $row, '');
            }
            $sheet->setCellValue('E' . $row, $participant->getAddress());
            $sheet->setCellValue('F' . $row, $participant->getLivingAreaType());
            $sheet->setCellValue('G' . $row, $participant->getPhone());
            $sheet->setCellValue('H' . $row, $participant->getEmail());
            $sheet->setCellValue('I' . $row, $participant->getGender());
            $sheet->setCellValue('J' . $row, $participant->getLearningGroup()->getStartDate()->format('Y-m-d'));
            $sheet->setCellValue('K' . $row, $participant->getLearningGroup()->getEndDate()->format('Y-m-d'));
            $sheet->setCellValue('L' . $row, $participant->getLearningGroup()->getId());

            foreach ($cols as $colAddr => $colTitle) {
                $cellAddress = $colAddr . $row;
                $sheet->getStyle($cellAddress)->applyFromArray($tableStyles);
            }

            $row++;
        }

        foreach ($cols as $colAddr => $colTitle) {
            $sheet
                ->getColumnDimension($colAddr)
                ->setAutoSize(true);
        }
    }

    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function setParticipantsReportHeader(Worksheet &$sheet, \DateTime $dateFrom, \DateTime $dateTo)
    {
        $reportTitle = "Mokymų dalyvių ataskaita";
        $reportHeaderTextOrganizer = "VŠĮ Žinios visiems";
        $reportHeaderTextProjectCode = "125:2019:98356";

        $sheet->setTitle($dateFrom->format('Y-m-d') . '--' . $dateTo->format('Y-m-d'));
        //set report header
        $sheet->setCellValue('A1', $reportTitle);
        $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 24]]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->mergeCells('A1:L1');

        $sheet->setCellValue('A3', 'Projekto vykdytojas:');
        $sheet->setCellValue('B3', $reportHeaderTextOrganizer);
        $sheet->getStyle('B3')->applyFromArray(['font' => ['bold' => true],]);
        $sheet->setCellValue('A4', 'Projekto kodas:');
        $sheet->setCellValue('B4', $reportHeaderTextProjectCode);
        $sheet->getStyle('B4')->applyFromArray(['font' => ['bold' => true],]);

        $sheet->setCellValue('A6', 'Laikotarpis nuo:');
        $sheet->setCellValue('B6', $dateFrom->format('Y-m-d'));
        $sheet->setCellValue('A7', 'iki:');
        $sheet->setCellValue('B7', $dateTo->format('Y-m-d'));
    }
}
