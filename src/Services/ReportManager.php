<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportManager
{
    
    /**
     * Writes to temp file and returns
     *
     * @param $spreadsheet
     * @param $fileName
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function writeToTempFile($spreadsheet, $fileName)
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
    
    /**
     * Gets default table style for reports
     *
     * @return array
     */
    protected function getTableStyles()
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
