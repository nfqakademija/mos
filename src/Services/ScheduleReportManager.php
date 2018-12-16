<?php
/**
 * Created by PhpStorm.
 * User: vaidas
 * Date: 18.12.15
 * Time: 13.42
 */

namespace App\Services;


use App\Repository\TimeSlotRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleReportManager extends ReportManager
{
    private $timeSlotRepository;

    public function __construct(TimeSlotRepository $timeSlotRepository)
    {
        $this->timeSlotRepository = $timeSlotRepository;
    }


    /**
     * Schedule report.
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     *
     * @return array
     */
    public function reportToExcel(\DateTime $dateFrom, \DateTime $dateTo): array
    {
        $scheduleReport = $this->timeSlotRepository->getTimeSlotsInPeriod($dateFrom, $dateTo);

        $spreadsheet = new Spreadsheet();

        /** @var $sheet \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $this->setReportHeader($sheet, $dateFrom, $dateTo);

        $this->setReportTable($sheet, $scheduleReport);

        $fileName = 'ScheduleReport_' . $dateFrom->format('Y-m-d') . '-' . $dateTo->format('Y-m-d') . '.xlsx';
        $tempFileWithName = $this->writeToTempFile($spreadsheet, $fileName);

        return $tempFileWithName;
    }

    private function setReportTable(
      Worksheet &$sheet,
      $scheduleReport
    ) {

        $tableStyles = $this->getTableStyles();
        $headerStyles = array_merge($tableStyles, ['font' => ['bold' => true]]);

        $cols = [
          'A' => 'Adresas',
          'B' => 'Pradžia',
          'C' => 'Trukmė',
          'D' => 'Dalyvių sk.',
          'E' => 'Grupės Nr.',
        ];

        //set report table headers
        $row = 9;
        foreach ($cols as $colAddr => $colTitle) {
            $cellAddress = $colAddr . $row;
            $sheet->setCellValue($cellAddress, $colTitle);
            $sheet->getStyle($cellAddress)->applyFromArray($headerStyles);
        }

        $row = 10;
        /** @var \App\Entity\TimeSlot $schedule */
        foreach ($scheduleReport as $schedule) {
            $sheet->setCellValue('A' . $row, $schedule->getLearningGroup()->getAddress());
            $sheet->setCellValue('B' . $row, $schedule->getDate() . ' ' . $schedule->getStartTime());
            $sheet->setCellValue('C' . $row, $schedule->getDuration() . 'min.');
            $sheet->setCellValue('D' . $row, sizeof ($schedule->getLearningGroup()->getParticipants()));
            $sheet->setCellValue('E' . $row, $schedule->getLearningGroup()->getId());

            foreach ($cols as $colAddr => $colTitle) {
                $cellAddress = $colAddr . $row;
                $sheet->getStyle($cellAddress)->applyFromArray($tableStyles);
            }

            $row++;
        }
        
        foreach($cols as $colAddr => $title) {
            $sheet->getColumnDimension($colAddr)
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
    private function setReportHeader(
      Worksheet &$sheet,
      \DateTime $dateFrom,
      \DateTime $dateTo
    ) {
        $reportTitle = "Mokymų tvarkaraštis";
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