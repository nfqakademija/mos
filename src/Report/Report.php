<?php

namespace App\Report;

use App\Entity\User;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report
{


    /** @var UserRepository */
    private $userRepository;

    /**
     * Report constructor.
     *
     * @param \App\Repository\UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Reports current project status. Only finished groups are considered.
     */
    public function getStatusReport(RegionRepository $regionRepository)
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

        $problematicRegions = $regionRepository->findBy(["isProblematic" => true]);
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
    public function participantsReportExportToExcel(\DateTime $dateFrom, \DateTime $dateTo): array
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

    /**
     * Writes to temp file and returns
     *
     * @param $spreadsheet
     * @param $fileName
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function writeToTempFile($spreadsheet, $fileName)
    {
        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return ['file' => $temp_file, 'file_name' => $fileName];
    }

    private function setParticipantsReportTable(
        Worksheet &$sheet,
        $participantsReport
    ) {

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
            'J' => 'Mokymų pradžios data',
            'K' => 'Mokymų pabaigos data',
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
            $sheet->setCellValue('A' . $row, $participant->getName() );
            $sheet->setCellValue('B' . $row, $participant->getSurname() );
            $sheet->setCellValue('C' . $row, $participant->getBirthDate() );
            try {
                $sheet->setCellValue('D' . $row,
                  $participant->getRegion()->getTitle());
            } catch (\Exception $e) {
                $sheet->setCellValue('D' . $row, '');
            }
            $sheet->setCellValue('E' . $row, $participant->getAddress() );
            $sheet->setCellValue('F' . $row, $participant->getLivingAreaType() );
            $sheet->setCellValue('G' . $row, $participant->getPhone() );
            $sheet->setCellValue('H' . $row, $participant->getEmail() );
            $sheet->setCellValue('I' . $row, $participant->getGender() );
            $sheet->setCellValue('J' . $row, $participant->getLearningGroup()->getStartDate() );
            $sheet->setCellValue('K' . $row, $participant->getLearningGroup()->getEndDate() );
            $sheet->setCellValue('L' . $row, $participant->getLearningGroup()->getId() );

            foreach ($cols as $colAddr => $colTitle) {
                $cellAddress = $colAddr . $row;
                $sheet->getStyle($cellAddress)->applyFromArray($tableStyles);
            }
            
            $row++;
        }
    }

    /**
     * Gets default table style for reports
     *
     * @return array
     */
    private function getTableStyles()
    {
        return [
          'font' => [
          ],
          'borders' => [
            'left' => ['borderStyle' => Border::BORDER_THIN],
            'top' => ['borderStyle' => Border::BORDER_THIN],
            'right' => ['borderStyle' => Border::BORDER_THIN],
            'bottom' => ['borderStyle' => Border::BORDER_THIN],
          ],
        ];
    }
    
    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function setParticipantsReportHeader(
        Worksheet &$sheet,
        \DateTime $dateFrom,
        \DateTime $dateTo
    ) {
        $reportTitle = "Mokymų dalyvių ataskaita";
        $reportHeaderText = "Nulla porttitor accumsan tincidunt. 
        Mauris blandit aliquet elit,eget tincidunt nibh pulvinar a. 
        Donec sollicitudin molestie malesuada.Sed porttitor lectus nibh. 
        Cras ultricies ligula sed magna dictum porta. 
        Pellentesque in ipsum id orci porta dapibus.Donec rutrum congue leo eget malesuada. 
        Quisque velit nisi, pretium ut lacinia in, elementum id enim. Nulla porttitor accumsan tincidunt.";

        $sheet->setTitle($dateFrom->format('Y-m-d') . '--' . $dateTo->format('Y-m-d'));
        //set report header
        $sheet->setCellValue('F1', $reportTitle);
        $sheet->getStyle('F1')->applyFromArray(['font' => ['bold' => true],]);

        $sheet->setCellValue('A3', $reportHeaderText);
        $sheet->getStyle('A3')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(3)->setRowHeight(80);
        $sheet->mergeCells('A3:L3');

        $sheet->setCellValue('A5', 'Laikotarpis nuo:');
        $sheet->setCellValue('B5', $dateFrom->format('Y-m-d'));
        $sheet->setCellValue('A6', 'iki:');
        $sheet->setCellValue('B6', $dateTo->format('Y-m-d'));
        $sheet->getColumnDimension('A')->setAutoSize(true);
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    private function normalizeData($data): array
    {
        $data['dateFrom'] = new \DateTime($data['dateFrom']);
        $data['dateTo'] = new \DateTime($data['dateTo']);

        return $data;
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function getRangeFromFormData($data)
    {
        $data = $this->normalizeData($data);

        $range = ['dateFrom' => '', 'dateTo' => ''];

        if (!empty($data['dateFrom'])) {
            $range['dateFrom'] = ($data['dateFrom'])->format('Y-m-d');
        }

        if (!empty($data['dateTo'])) {
            $range['dateTo'] = ($data['dateTo'])->format('Y-m-d');
        }

        return $range;
    }
}
