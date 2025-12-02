<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudentApplicationModel;

class StudentApplications extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new StudentApplicationModel();
    }

    public function index()
    {
        $perPage = $this->request->getGet('per_page') ?? 25;
        $sortBy = $this->request->getGet('sort') ?? 'newest';
        $search = $this->request->getGet('search') ?? '';
        $filterStatus = $this->request->getGet('filter_status') ?? '';

        // Apply search
        if ($search) {
            $this->model->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('nisn', $search)
                ->orLike('nama_ortu', $search)
                ->orLike('email', $search)
                ->orLike('no_hp', $search)
                ->orLike('asal_sekolah', $search)
                ->groupEnd();
        }

        // Apply status filter
        if ($filterStatus) {
            $this->model->where('status', $filterStatus);
        }

        // Apply sorting
        switch ($sortBy) {
            case 'oldest':
                $this->model->orderBy('created_at', 'ASC');
                break;
            case 'name_asc':
                $this->model->orderBy('nama_lengkap', 'ASC');
                break;
            case 'name_desc':
                $this->model->orderBy('nama_lengkap', 'DESC');
                break;
            case 'status':
                $this->model->orderBy('status', 'ASC');
                break;
            case 'newest':
            default:
                $this->model->orderBy('created_at', 'DESC');
                break;
        }

        $applications = $this->model->paginate($perPage, 'default');
        $pager = $this->model->pager;

        $data = [
            'title' => 'Data Pendaftaran Siswa',
            'applications' => $applications,
            'pager' => $pager,
            'perPage' => $perPage,
            'sortBy' => $sortBy,
            'search' => $search,
            'filterStatus' => $filterStatus,
            'sortOptions' => [
                'newest' => 'Terbaru',
                'oldest' => 'Terlama',
                'name_asc' => 'Nama A-Z',
                'name_desc' => 'Nama Z-A',
                'status' => 'Status'
            ],
            'statusOptions' => [
                '' => 'Semua Status',
                'pending' => 'Pending',
                'review' => 'Review',
                'accepted' => 'Diterima',
                'rejected' => 'Ditolak'
            ],
            'enableBulkActions' => true,
            'bulkActions' => [
                ['action' => 'delete', 'label' => 'Hapus', 'icon' => 'trash', 'variant' => 'danger', 'confirm' => 'Hapus pendaftaran terpilih?']
            ]
        ];

        return view('admin/student_applications/index', $data);
    }

    public function view($id)
    {
        $application = $this->model->find($id);

        if (!$application) {
            return redirect()->to('/dashboard/pendaftaran')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Pendaftaran - ' . $application['nama_lengkap'],
            'application' => $application
        ];

        return view('admin/student_applications/view', $data);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        
        if (!in_array($status, ['pending', 'review', 'accepted', 'rejected'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->model->update($id, ['status' => $status]);
        
        helper('auth');
        log_activity('update_student_application_status', 'student_application', $id, ['status' => $status]);

        return redirect()->back()->with('message', 'Status berhasil diupdate.');
    }

    public function delete($id)
    {
        $application = $this->model->find($id);

        if (!$application) {
            return redirect()->to('/dashboard/pendaftaran')->with('error', 'Data tidak ditemukan.');
        }

        // Delete documents
        if ($application['dokumen_kk']) {
            $path = APPPATH . '../../' . $application['dokumen_kk'];
            if (file_exists($path)) unlink($path);
        }
        if ($application['dokumen_akte']) {
            $path = APPPATH . '../../' . $application['dokumen_akte'];
            if (file_exists($path)) unlink($path);
        }
        if ($application['pas_foto']) {
            $path = APPPATH . '../../' . $application['pas_foto'];
            if (file_exists($path)) unlink($path);
        }
        if ($application['foto_ijazah']) {
            $path = APPPATH . '../../' . $application['foto_ijazah'];
            if (file_exists($path)) unlink($path);
        }

        $this->model->delete($id);
        
        helper('auth');
        log_activity('delete_student_application', 'student_application', $id, ['nama' => $application['nama_lengkap'] ?? null]);

        return redirect()->to('/dashboard/pendaftaran')->with('message', 'Data berhasil dihapus.');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
        }

        $count = 0;
        foreach ($ids as $id) {
            $application = $this->model->find($id);

            if ($application) {
                // Delete documents
                if ($application['dokumen_kk']) {
                    $path = APPPATH . '../../' . $application['dokumen_kk'];
                    if (file_exists($path)) unlink($path);
                }
                if ($application['dokumen_akte']) {
                    $path = APPPATH . '../../' . $application['dokumen_akte'];
                    if (file_exists($path)) unlink($path);
                }
                if ($application['pas_foto']) {
                    $path = APPPATH . '../../' . $application['pas_foto'];
                    if (file_exists($path)) unlink($path);
                }
                if ($application['foto_ijazah']) {
                    $path = APPPATH . '../../' . $application['foto_ijazah'];
                    if (file_exists($path)) unlink($path);
                }

                $this->model->delete($id);
                $count++;
            }
        }

        return redirect()->to('/dashboard/pendaftaran')->with('message', "$count data berhasil dihapus.");
    }

    public function exportExcel()
    {
        // Check if PhpSpreadsheet is available
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return redirect()->back()->with('error', 'Library PhpSpreadsheet belum terinstall. Jalankan: composer require phpoffice/phpspreadsheet');
        }
        
        // Apply same filters as index
        $search = $this->request->getGet('search') ?? '';
        $filterStatus = $this->request->getGet('filter_status') ?? '';

        if ($search) {
            $this->model->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('nisn', $search)
                ->orLike('nama_ortu', $search)
                ->orLike('email', $search)
                ->orLike('no_hp', $search)
                ->orLike('asal_sekolah', $search)
                ->groupEnd();
        }

        if ($filterStatus) {
            $this->model->where('status', $filterStatus);
        }

        $applications = $this->model->orderBy('created_at', 'DESC')->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = ['No', 'Nama Lengkap', 'NISN', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat', 'Nama Orang Tua', 'No HP', 'Email', 'Asal Sekolah', 'Dokumen KK', 'Dokumen Akte', 'Pas Foto', 'Foto Ijazah', 'Status', 'Tanggal Daftar'];
        $sheet->fromArray($headers, NULL, 'A1');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A1:Q1')->applyFromArray($headerStyle);

        // Set column widths for image columns
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);

        // Data
        $row = 2;
        foreach ($applications as $index => $app) {
            // Set row height for images
            $sheet->getRowDimension($row)->setRowHeight(100);

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $app['nama_lengkap']);
            $sheet->setCellValue('C' . $row, $app['nisn'] ?? '-');
            $sheet->setCellValue('D' . $row, $app['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan');
            $sheet->setCellValue('E' . $row, $app['tempat_lahir']);
            $sheet->setCellValue('F' . $row, date('d/m/Y', strtotime($app['tanggal_lahir'])));
            $sheet->setCellValue('G' . $row, $app['alamat']);
            $sheet->setCellValue('H' . $row, $app['nama_ortu']);
            $sheet->setCellValue('I' . $row, $app['no_hp']);
            $sheet->setCellValue('J' . $row, $app['email'] ?? '-');
            $sheet->setCellValue('K' . $row, $app['asal_sekolah'] ?? '-');

            // Dokumen KK - Insert Image
            if ($app['dokumen_kk']) {
                // Path di database: dash/public/uploads/...
                // APPPATH = dash/app/, so APPPATH . '../../' = adyatama-school2/
                $kkPath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $app['dokumen_kk'];
                if (file_exists($kkPath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath($kkPath);
                    $drawing->setCoordinates('L' . $row);
                    $drawing->setHeight(90);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('L' . $row, 'File tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('L' . $row, 'Tidak Ada');
            }

            // Dokumen Akte - Insert Image
            if ($app['dokumen_akte']) {
                // Path di database: dash/public/uploads/...
                $aktePath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $app['dokumen_akte'];
                if (file_exists($aktePath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath($aktePath);
                    $drawing->setCoordinates('M' . $row);
                    $drawing->setHeight(90);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('M' . $row, 'File tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('M' . $row, 'Tidak Ada');
            }

            // Pas Foto - Insert Image
            if ($app['pas_foto']) {
                $pasfotoPath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $app['pas_foto'];
                if (file_exists($pasfotoPath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath($pasfotoPath);
                    $drawing->setCoordinates('N' . $row);
                    $drawing->setHeight(90);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('N' . $row, 'File tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('N' . $row, 'Tidak Ada');
            }

            // Foto Ijazah - Insert Image
            if ($app['foto_ijazah']) {
                $ijazahPath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $app['foto_ijazah'];
                if (file_exists($ijazahPath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath($ijazahPath);
                    $drawing->setCoordinates('O' . $row);
                    $drawing->setHeight(90);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue('O' . $row, 'File tidak ditemukan');
                }
            } else {
                $sheet->setCellValue('O' . $row, 'Tidak Ada');
            }

            $sheet->setCellValue('P' . $row, ucfirst($app['status']));
            $sheet->setCellValue('Q' . $row, date('d/m/Y H:i', strtotime($app['created_at'])));
            $row++;
        }

        // Auto-size columns (except image columns)
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('P', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $filename = 'Data_Pendaftaran_' . date('Y-m-d_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportDoc($id)
    {
        // Check if PHPWord is available
        if (!class_exists('\PhpOffice\PhpWord\PhpWord')) {
            return redirect()->back()->with('error', 'Library PHPWord belum terinstall. Jalankan: composer require phpoffice/phpword');
        }

        $application = $this->model->find($id);

        if (!$application) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Title
        $section->addText('FORMULIR PENDAFTARAN SISWA BARU', ['bold' => true, 'size' => 16], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addText('SD ADYATAMA', ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(2);

        // Data table
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
        $table = $section->addTable($tableStyle);

        $fields = [
            ['Nama Lengkap', $application['nama_lengkap']],
            ['NISN', $application['nisn'] ?? '-'],
            ['Jenis Kelamin', $application['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'],
            ['Tempat Lahir', $application['tempat_lahir']],
            ['Tanggal Lahir', date('d F Y', strtotime($application['tanggal_lahir']))],
            ['Alamat', $application['alamat']],
            ['Nama Orang Tua', $application['nama_ortu']],
            ['No HP', $application['no_hp']],
            ['Email', $application['email'] ?? '-'],
            ['Asal Sekolah', $application['asal_sekolah'] ?? '-'],
            ['Status', ucfirst($application['status'])],
            ['Tanggal Pendaftaran', date('d F Y H:i', strtotime($application['created_at']))]
        ];

        foreach ($fields as $field) {
            $table->addRow();
            $table->addCell(4000)->addText($field[0], ['bold' => true]);
            $table->addCell(6000)->addText($field[1]);
        }

        // === DOKUMEN LAMPIRAN ===
        
        // Dokumen KK - Halaman 2
        $section->addPageBreak();
        $section->addText('DOKUMEN KARTU KELUARGA', ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);
        if ($application['dokumen_kk']) {
            // Path di database: dash/public/uploads/...
            $kkPath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $application['dokumen_kk'];
            if (file_exists($kkPath)) {
                // Lebar max 150mm (15cm), tinggi auto - lebih pas untuk dokumen
                $section->addImage($kkPath, [
                    'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(15),
                    'wrappingStyle' => 'inline'
                ]);
            } else {
                $section->addText('File tidak ditemukan');
            }
        } else {
            $section->addText('Tidak ada dokumen KK');
        }

        // Dokumen Akte - Halaman 3
        $section->addPageBreak();
        $section->addText('DOKUMEN AKTE KELAHIRAN', ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);
        if ($application['dokumen_akte']) {
            // Path di database: dash/public/uploads/...
            $aktePath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $application['dokumen_akte'];
            if (file_exists($aktePath)) {
                // Lebar max 150mm (15cm), tinggi auto - lebih pas untuk dokumen
                $section->addImage($aktePath, [
                    'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(15),
                    'wrappingStyle' => 'inline'
                ]);
            } else {
                $section->addText('File tidak ditemukan');
            }
        } else {
            $section->addText('Tidak ada dokumen Akte');
        }

        // Pas Foto - Halaman 4
        $section->addPageBreak();
        $section->addText('PAS FOTO (4x6 cm)', ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);
        if ($application['pas_foto']) {
            $pasfotoPath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $application['pas_foto'];
            if (file_exists($pasfotoPath)) {
                // Pas foto 4x6 cm - exact size
                $section->addImage($pasfotoPath, [
                    'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(4),
                    'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(6),
                    'wrappingStyle' => 'inline'
                ]);
            } else {
                $section->addText('File tidak ditemukan');
            }
        } else {
            $section->addText('Tidak ada pas foto');
        }

        // Foto Ijazah - Halaman 5
        $section->addPageBreak();
        $section->addText('FOTO IJAZAH', ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $section->addTextBreak(1);
        if ($application['foto_ijazah']) {
            $ijazahPath = str_replace('\\', '/', realpath(APPPATH . '../../')) . '/' . $application['foto_ijazah'];
            if (file_exists($ijazahPath)) {
                // Lebar max 150mm (15cm), tinggi auto - lebih pas untuk dokumen
                $section->addImage($ijazahPath, [
                    'width' => \PhpOffice\PhpWord\Shared\Converter::cmToPoint(15),
                    'wrappingStyle' => 'inline'
                ]);
            } else {
                $section->addText('File tidak ditemukan');
            }
        } else {
            $section->addText('Tidak ada foto ijazah');
        }

        // Download
        $filename = 'Pendaftaran_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $application['nama_lengkap']) . '_' . date('Y-m-d') . '.docx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
}
