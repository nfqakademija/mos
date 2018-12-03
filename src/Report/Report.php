<?php

namespace App\Report;

use App\Repository\LearningGroupRepository;
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

    public function getStatusReport()
    {
        $result = [
            'allParticipantsCount',
            'appParticipantsInProblematicRegions' => ['title', 'participantsCount'],
            'olderThan45',
            'olderThan45InCountrySide',
            'olderThan45Woman'
          ];



         return $result;
    }

    public function participantsReportExportToExcel(\DateTime $dateFrom, \DateTime $dateTo): array
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes

        $reportKeysMap = $this->getParticipantsReportKeysMap();

        $participantsReport = $this->userRepository->getParticipantsByGroupPeriod($dateFrom, $dateTo);

        $spreadsheet = new Spreadsheet();

        /** @var $sheet \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $this->setParticipantsReportHeader($sheet, $dateFrom, $dateTo);

        $this->setParticipantsReportTable($sheet, $participantsReport, $reportKeysMap);

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
        $participantsReport,
        $reportKeysMap
    ) {

        $tableStyles = $this->getTableStyles();
        $headerStyles = array_merge($tableStyles, ['font' => ['bold' => true]]);

        //set report table headers
        $col = 'A';
        $row = 9;
        foreach ($reportKeysMap as $title => $reportArrayKey) {
            $cellAddress = $col++ . $row;
            $sheet->setCellValue($cellAddress, $title);
            $sheet->getStyle($cellAddress)->applyFromArray($headerStyles);
        }

        $row = 10;
        foreach ($participantsReport as $participantReport) {
            $col = 'A';
            foreach ($reportKeysMap as $title => $reportArrayKey) {
                $cellAddress = $col++ . $row;
                $sheet->setCellValue(
                    $cellAddress,
                    $participantReport[$reportArrayKey]
                );
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
     * Format: report column name => key in report array
     *
     * @return array
     */
    private function getParticipantsReportKeysMap()
    {
        return [
          'Vardas' => 'name',
          'Pavardė' => 'surname',
          'Gimimo data' => 'birthDate',
          'Rajonas' => 'regionTitle',
          'Adresas' => 'address',
          'Vietovės tipas' => 'livingAreaType',
          'Tel. nr.' => 'phone',
          'El. paštas' => 'email',
          'Vyras / moteris' => 'gender',
          'Mokymų pradžia' => 'groupStart',
          'Mokymų pabaiga' => 'groupEnd',
          'Grupės Nr.' => 'groupId',
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
        $sheet->getRowDimension(3)->setRowHeight(60);
        $sheet->mergeCells('A3:L3');

        $sheet->setCellValue('A5', 'Laikotarpis nuo:');
        $sheet->setCellValue('B5', $dateFrom->format('Y-m-d'));
        $sheet->setCellValue('A6', 'iki:');
        $sheet->setCellValue('B6', $dateTo->format('Y-m-d'));
        $sheet->getColumnDimension('A')->setAutoSize(true);
    }

    /**
     * @param $data
     *
     * @return \DateTime[] $range
     */
    public function getRangeFromFormData($data)
    {
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
