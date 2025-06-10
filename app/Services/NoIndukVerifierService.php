<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;

class NoIndukVerifierService
{
    // Tabel Kode Program Studi untuk NIM
    private const PRODI_D2 = [
        '77' => 'D-II Pengembangan Perangkat Lunak Situs',
    ];

    private const PRODI_D3 = [
        '61' => 'D-III Administrasi Bisnis',
        '51' => 'D-III Akuntansi',
        '56' => 'D-III Akuntansi - PSDKU Lumajang',
        '55' => 'D-III Akuntansi, Kediri',
        '11' => 'D-III Teknik Elektronika',
        '12' => 'D-III Teknik Listrik',
        '13' => 'D-III Teknik Telekomunikasi',
        '41' => 'D-III Teknik Kimia',
        '21' => 'D-III Teknik Mesin',
        '24' => 'D-III Teknik Mesin, Kediri',
        '26' => 'D-III Teknologi Pemeliharaan Pesawat Udara',
        '34' => 'D-III Teknik Pertambangan',
        '31' => 'D-III Teknik Sipil',
        '33' => 'D-III Teknologi Konstruksi Jalan, Jembatan, dan Bangunan Air',
        '36' => 'D-III Teknologi Sipil - PSDKU Lumajang',
        '75' => 'D-III Manajemen Informatika (PSDKU Pamekasan)',
        '73' => 'D-III Manajemen Informatika - PSDKU Kediri',
        '74' => 'D-III Teknologi Informasi - PSDKU Lumajang',
    ];

    private const PRODI_D4 = [
        '52' => 'D-IV Akuntansi Manajemen',
        '58' => 'D-IV Akuntansi Manajemen (Kampus Kab Pamekasan)',
        '62' => 'D-IV Manajemen Pemasaran',
        '63' => 'D-IV Pengelolaan Arsip dan Rekaman Informasi',
        '53' => 'D-IV Keuangan',
        '57' => 'D-IV Keuangan (Kampus Kab Kediri)',
        '83' => 'D-IV Bahasa Inggris untuk Industri Pariwisata',
        '82' => 'D-IV Bahasa Inggris untuk Komunikasi Bisnis dan Profesional',
        '84' => 'D-IV Usaha Perjalanan Wisata',
        '16' => 'D-IV Jaringan Telekomunikasi Digital',
        '15' => 'D-IV Sistem Kelistrikan',
        '17' => 'D-IV Teknik Elektronika',
        '19' => 'D-IV Teknik Elektronika (Kampus Kab Kediri)',
        '42' => 'D-IV Teknologi Kimia Industri',
        '23' => 'D-IV Teknik Mesin Produksi Dan Perawatan',
        '22' => 'D-IV Teknik Otomotif Elektronik',
        '28' => 'D-IV Teknik Mesin Produksi dan Perawatan (Kampus Kab Kediri)',
        '29' => 'D-IV Teknik Otomotif Elektronik (Kampus Kab Pamekasan)',
        '27' => 'D-IV Teknologi Rekayasa Otomotif - PSDKU Lumajang',
        '32' => 'D-IV Manajemen Rekayasa Konstruksi',
        '35' => 'D-IV Teknologi Rekayasa Konstruksi Jalan dan Jembatan',
        '76' => 'D-IV Sistem Informasi Bisnis',
        '72' => 'D-IV Teknik Informatika',
    ];

    private const PRODI_S2 = [
        '25' => 'S-2 Rekayasa Teknologi Manufaktur',
        '54' => 'S-2 Sistem Informasi Akuntansi',
        '18' => 'S-2 Teknik Elektro',
        '12' => 'S-2 Rekayasa Teknologi Informasi',
    ];

    // Constants for validation
    private const MIN_BIRTH_YEAR = 1920;
    private const MAX_BIRTH_YEAR = 2010; // Reasonable maximum for someone to be enrolled
    private const MIN_APPOINTMENT_YEAR = 1945; // Indonesia independence
    private const EARLIEST_NIM_YEAR = 2000; // Earliest reasonable NIM year
    private const LATEST_NIM_FUTURE_YEARS = 1; // Allow 1 year into future for new student registration

    /**
     * Verifikasi utama nomor induk (NIM 10-11 digit, NIP 18, NIDN 10).
     *
     * @param string|null $noInduk
     * @return array ['type'=>string, 'data'=>array, 'errors'=>array]
     */
     public function verify(?string $noInduk): array
    {
        // Validasi input kosong
        if (empty($noInduk)) {
            return ['type' => 'Tidak Valid', 'data' => [], 'errors' => ['Input kosong']];
        }

        // Hanya angka
        $noInduk = preg_replace('/\D/', '', trim($noInduk));
        if (empty($noInduk)) {
            return ['type' => 'Tidak Valid', 'data' => [], 'errors' => ['Input harus berupa angka']];
        }

        // Panjang minimal 10 digit untuk NIM dan NIP
        if (strlen($noInduk) < 10) {
            return ['type' => 'Tidak Valid', 'data' => [], 'errors' => ['Panjang nomor terlalu pendek (minimum 10 digit)']];
        }

        $len = strlen($noInduk);
        switch ($len) {
            case 18:
                return $this->verifyNip($noInduk);
            case 10:
            case 11:
                return $this->verifyNim($noInduk);
            default:
                return ['type' => 'Tidak Valid', 'data' => [], 'errors' => ["Panjang nomor tidak sesuai format (ditemukan: $len digit)"]];
        }
    }

    /**
     * Validate date with realistic constraints
     */
    private function isValidBirthDate(int $day, int $month, int $year): array
    {
        $errors = [];

        // Basic date validation
        if (!checkdate($month, $day, $year)) {
            $errors[] = 'Format tanggal tidak valid';
            return ['valid' => false, 'errors' => $errors];
        }

        // Year range validation
        if ($year < self::MIN_BIRTH_YEAR || $year > self::MAX_BIRTH_YEAR) {
            $errors[] = "Tahun lahir tidak masuk akal (rentang valid: " . self::MIN_BIRTH_YEAR . "-" . self::MAX_BIRTH_YEAR . ")";
        }

        // Future date validation
        $birthDate = Carbon::createFromDate($year, $month, $day);
        $today = Carbon::now();

        if ($birthDate->isAfter($today)) {
            $errors[] = 'Tanggal lahir tidak boleh di masa depan';
        }

        // Age validation (reasonable range for employees/students)
        $age = $today->diffInYears($birthDate);
        if ($age < 15) {
            $errors[] = 'Usia terlalu muda (kurang dari 15 tahun)';
        } elseif ($age > 80) {
            $errors[] = 'Usia terlalu tua (lebih dari 80 tahun)';
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }

    /**
     * Validate academic year for NIM
     */
    private function isValidAcademicYear(int $nimYear): array
    {
        $errors = [];
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('n');

        // Convert 2-digit year to 4-digit year
        $fullYear = 2000 + $nimYear;

        // Check if year is too early
        if ($fullYear < self::EARLIEST_NIM_YEAR) {
            $errors[] = "Tahun angkatan terlalu lama ($fullYear)";
            return ['valid' => false, 'errors' => $errors, 'fullYear' => $fullYear];
        }

        // Academic year validation logic
        // Academic year starts in July (month 7) and ends in June (month 6) next year
        if ($currentMonth >= 7) {
            // July-December: can accept current year and next year
            $maxValidYear = $currentYear + self::LATEST_NIM_FUTURE_YEARS;
        } else {
            // January-June: can accept current year only
            $maxValidYear = $currentYear;
        }

        if ($fullYear > $maxValidYear) {
            $errors[] = "Tahun angkatan terlalu jauh di masa depan ($fullYear)";
        }

        // Additional sanity check: not more than 10 years old
        if ($fullYear < ($currentYear - 10)) {
            $errors[] = "Tahun angkatan terlalu lama ($fullYear), maksimal 10 tahun yang lalu";
        }

        return ['valid' => empty($errors), 'errors' => $errors, 'fullYear' => $fullYear];
    }

    /**
     * Verifikasi NIP (18 digit)
     */
    private function verifyNip(string $nip): array
    {
        $data = [];
        $errors = [];

        try {
            // Extract date components
            $year = (int)substr($nip, 0, 4);
            $month = (int)substr($nip, 4, 2);
            $day = (int)substr($nip, 6, 2);

            // Validate birth date
            $birthValidation = $this->isValidBirthDate($day, $month, $year);
            if (!$birthValidation['valid']) {
                $errors = array_merge($errors, $birthValidation['errors']);
            } else {
                $data['Tanggal Lahir'] = sprintf('%02d-%02d-%04d', $day, $month, $year);
            }

            // Extract appointment date
            $yearApp = (int)substr($nip, 8, 4);
            $monthApp = (int)substr($nip, 12, 2);

            // Validate appointment date
            if (!checkdate($monthApp, 1, $yearApp)) {
                $errors[] = 'Tahun/bulan pengangkatan tidak valid';
            } elseif ($yearApp < self::MIN_APPOINTMENT_YEAR) {
                $errors[] = "Tahun pengangkatan terlalu lama ($yearApp)";
            } elseif ($yearApp > (int)date('Y')) {
                $errors[] = "Tahun pengangkatan di masa depan ($yearApp)";
            } else {
                $data['Tahun & Bulan Pengangkatan'] = sprintf('%02d-%04d', $monthApp, $yearApp);

                // Cross-validate appointment date with birth date
                if (isset($data['Tanggal Lahir'])) {
                    $birthYear = $year;
                    $minAppointmentAge = 18; // Minimum age for civil servant
                    if (($yearApp - $birthYear) < $minAppointmentAge) {
                        $errors[] = "Usia saat pengangkatan terlalu muda (kurang dari $minAppointmentAge tahun)";
                    }
                }
            }

            // Gender validation
            $genderCode = substr($nip, 14, 1);
            if (!in_array($genderCode, ['1', '2'])) {
                $errors[] = 'Kode jenis kelamin tidak valid (harus 1 atau 2)';
                $data['Jenis Kelamin'] = 'Tidak Valid';
            } else {
                $data['Jenis Kelamin'] = $genderCode === '1' ? 'Pria' : 'Wanita';
            }

            // Serial number validation
            $serialNumber = substr($nip, 15, 3);
            if (!ctype_digit($serialNumber) || $serialNumber === '000') {
                $errors[] = 'Nomor urut PNS tidak valid';
            } else {
                $data['Nomor Urut PNS'] = $serialNumber;
            }

        } catch (Exception $e) {
            $errors[] = 'Format NIP tidak dapat diproses';
        }

        if (!empty($errors)) {
            return ['type' => 'NIP (Tidak Valid)', 'data' => $data, 'errors' => $errors];
        }

        return ['type' => 'NIP (Nomor Induk Pegawai)', 'data' => $data, 'errors' => []];
    }

    /**
     * Verifikasi NIM Polinema (10-11 digit)
     */
    private function verifyNim(string $nim): array
    {
        $len = strlen($nim);
        if ($len < 10 || $len > 11) {
            return ['type' => 'Tidak Diketahui', 'data' => [], 'errors' => ['Panjang NIM tidak sesuai (harus 10-11 digit)']];
        }

        $data = [];
        $errors = [];

        try {
            // Validate and extract year
            $tahunMasukCode = (int)substr($nim, 0, 2);
            $yearValidation = $this->isValidAcademicYear($tahunMasukCode);

            if (!$yearValidation['valid']) {
                $errors = array_merge($errors, $yearValidation['errors']);
            } else {
                $data['Tahun Masuk'] = $yearValidation['fullYear'];
            }

            // Extract and validate program
            $programCode = substr($nim, 2, 1);
            $prodiCode = substr($nim, 4, 2);

            if (!ctype_digit($prodiCode)) {
                $errors[] = 'Kode program studi tidak valid';
                return ['type' => 'NIM (Tidak Valid)', 'data' => $data, 'errors' => $errors];
            }

            $prodiName = null;
            switch ($programCode) {
                case '2':
                    $data['Program Pendidikan'] = 'D-II';
                    $prodiName = self::PRODI_D2[$prodiCode] ?? null;
                    break;
                case '3':
                    $data['Program Pendidikan'] = 'D-III';
                    $prodiName = self::PRODI_D3[$prodiCode] ?? null;
                    break;
                case '4':
                    $data['Program Pendidikan'] = 'D-IV';
                    $prodiName = self::PRODI_D4[$prodiCode] ?? null;
                    break;
                case '5':
                    $data['Program Pendidikan'] = 'S-2 Terapan';
                    $prodiName = self::PRODI_S2[$prodiCode] ?? null;
                    break;
                default:
                    $errors[] = "Kode program pendidikan tidak valid ($programCode)";
                    return ['type' => 'NIM (Tidak Valid)', 'data' => $data, 'errors' => $errors];
            }

            if (!$prodiName) {
                $errors[] = "Program studi tidak ditemukan (kode: $prodiCode)";
                return ['type' => 'NIM (Tidak Valid)', 'data' => $data, 'errors' => $errors];
            }
            $data['Program Studi'] = $prodiName;

            // Validate field of study
            $bidang = substr($nim, 3, 1);
            if (!in_array($bidang, ['1', '2'])) {
                $errors[] = "Kode bidang studi tidak valid ($bidang)";
                $data['Bidang Studi'] = 'Tidak Valid';
            } else {
                $data['Bidang Studi'] = $bidang === '1' ? 'Rekayasa' : 'Tata Niaga';
            }

            // Validate entrance status
            $status = substr($nim, 6, 1);
            $mapStatus = [
                '0' => 'Kelas Reguler',
                '3' => 'Alih Jenjang/Pindahan Semester 3',
                '5' => 'Alih Jenjang/Pindahan Semester 5'
            ];

            if (!isset($mapStatus[$status])) {
                $errors[] = "Status masuk tidak valid ($status)";
                $data['Status Masuk'] = 'Tidak Valid';
            } else {
                $data['Status Masuk'] = $mapStatus[$status];
            }

            // Validate serial number
            $urut = substr($nim, 7);
            if (!ctype_digit($urut) || empty($urut)) {
                $errors[] = 'Nomor urut tidak valid';
            } elseif ($urut === str_repeat('0', strlen($urut))) {
                $errors[] = 'Nomor urut tidak boleh semua nol';
            } else {
                $data['Nomor Urut'] = $urut;
            }

        } catch (Exception $e) {
            $errors[] = 'Format NIM tidak dapat diproses';
        }

        if (!empty($errors)) {
            return ['type' => 'NIM (Tidak Valid)', 'data' => $data, 'errors' => $errors];
        }

        return ['type' => 'NIM (Nomor Induk Mahasiswa)', 'data' => $data, 'errors' => []];
    }

    /**
     * Get detailed validation rules for reference
     */
    public function getValidationRules(): array
    {
        return [
            'NIM' => [
                'length'=>'10-11 digit',
                'year_range'=>'Maksimal '.self::LATEST_NIM_FUTURE_YEARS.' tahun ke depan',
                'academic_year'=>'Tahun akademik Juli-Juni'
            ],
            'NIP' => [
                'length'=>'18 digit',
                'birth_year_range'=>self::MIN_BIRTH_YEAR.'-'.self::MAX_BIRTH_YEAR,
                'appointment_year_min'=>self::MIN_APPOINTMENT_YEAR,
                'minimum_appointment_age'=>'18 tahun'
            ]
        ];
    }
}
