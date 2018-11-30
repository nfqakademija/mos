<?php

namespace App\Report;

use App\Repository\LearningGroupRepository;
use App\Repository\UserRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report
{
    /** @var LearningGroupRepository  */
    private $groupRepository;

    /** @var UserRepository */
    private $userRepository;

    /**
     * Report constructor.
     *
     * @param \App\Repository\LearningGroupRepository $groupRepository
     * @param \App\Repository\UserRepository $userRepository
     */
    public function __construct(LearningGroupRepository $groupRepository, UserRepository $userRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
    }

    public function participantsReportExportToExcel(\DateTime $dateFrom, \DateTime $dateTo) : array
    {
        $reportTitle = "Mokymų dalyvių ataskaita";
        $reportHeaderText = "Nulla porttitor accumsan tincidunt. Mauris blandit aliquet elit,eget tincidunt nibh pulvinar a. Donec sollicitudin molestie malesuada.Sed porttitor lectus nibh. Cras ultricies ligula sed magna dictum porta. Pellentesque in ipsum id orci porta dapibus.Donec rutrum congue leo eget malesuada. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Nulla porttitor accumsan tincidunt.";

        //report column name => key in report array
        $reportValues = [
            'Vardas' => 'name',
            'Pavardė' => 'surname',
            'Gimimo data' => 'birthDate',
            'Rajonas' => 'region',
            'Adresas' => 'address',
            'Vietovės tipas' => 'areaType',
            'Tel. nr.' => 'phone',
            'El. paštas' => 'email',
            'Vyras / moteris' => 'gender',
            'Mokymų pradžia' => 'groupStart',
            'Mokymų pabaiga' => 'groupEnd',
            'Grupės Nr.' => 'groupId',
        ];

        $participantsReport = $this->userRepository->getParticipantsByGroupPeriod($dateFrom, $dateTo);


        $spreadsheet = new Spreadsheet();

        /** @var $sheet \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle( $dateFrom->format('Y-m-d') . '--' . $dateTo->format('Y-m-d'));
        //set report header
        $sheet->setCellValue('F1', $reportTitle);
        $sheet->setCellValue('F2', $reportHeaderText);

        //set report table headers
        $reportStartCol = 'A';
        foreach ($reportValues as $title => $reportArrayKey) {
            $sheet->setCellValue($reportStartCol++ . 4, $title);
        }




        foreach ($participantsReport as $participantReport) {

        }





        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'participantsReport.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return ['file' => $temp_file, 'file_name' => $fileName];
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
