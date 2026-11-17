<?php

class EmailHelper
{
    private static $fromEmail = 'noreply@prasarana.sch.id';
    private static $fromName = 'Sistem Prasarana Sekolah';

    /**
     * Kirim email notifikasi
     */
    public static function send($to, $subject, $message)
    {
        $headers = [
            'From' => self::$fromName . ' <' . self::$fromEmail . '>',
            'Reply-To' => self::$fromEmail,
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=UTF-8'
        ];

        $headersStr = '';
        foreach ($headers as $key => $value) {
            $headersStr .= $key . ': ' . $value . "\r\n";
        }

        // Wrap message with HTML template
        $htmlMessage = self::wrapTemplate($subject, $message);

        return mail($to, $subject, $htmlMessage, $headersStr);
    }

    /**
     * Kirim notifikasi update status pengaduan ke siswa
     */
    public static function sendStatusUpdate($email, $namaSiswa, $judulPengaduan, $status, $keterangan = '')
    {
        $subject = "Update Status Pengaduan - $status";
        
        $message = "
            <h2 style='color: #4f46e5; margin-bottom: 20px;'>Halo, $namaSiswa!</h2>
            <p>Pengaduan Anda telah diperbarui statusnya:</p>
            
            <div style='background: #f3f4f6; padding: 20px; border-radius: 12px; margin: 20px 0;'>
                <h3 style='margin: 0 0 10px 0; color: #1f2937;'>$judulPengaduan</h3>
                <p style='margin: 0;'>
                    <strong>Status:</strong> 
                    <span style='
                        background: " . self::getStatusColor($status) . ";
                        color: white;
                        padding: 4px 12px;
                        border-radius: 20px;
                        font-size: 12px;
                    '>$status</span>
                </p>
                " . ($keterangan ? "<p style='margin-top: 10px;'><strong>Keterangan:</strong> $keterangan</p>" : "") . "
            </div>
            
            <p style='color: #6b7280; font-size: 14px;'>
                Silakan login ke sistem untuk melihat detail lengkap.<br>
                <a href='" . BASE_URL . "' style='color: #4f46e5;'>Klik di sini untuk login</a>
            </p>
            
            <hr style='border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;'>
            
            <p style='color: #9ca3af; font-size: 12px;'>
                Email ini dikirim otomatis oleh Sistem Prasarana Sekolah.<br>
                Mohon tidak membalas email ini.
            </p>
        ";

        return self::send($email, $subject, $message);
    }

    /**
     * Kirim notifikasi pengaduan baru ke admin
     */
    public static function sendNewPengaduan($email, $namaAdmin, $namaSiswa, $judulPengaduan, $kategori)
    {
        $subject = "Pengaduan Baru dari $namaSiswa";
        
        $message = "
            <h2 style='color: #4f46e5; margin-bottom: 20px;'>Halo, $namaAdmin!</h2>
            <p>Ada pengaduan baru yang masuk:</p>
            
            <div style='background: #f3f4f6; padding: 20px; border-radius: 12px; margin: 20px 0;'>
                <h3 style='margin: 0 0 10px 0; color: #1f2937;'>$judulPengaduan</h3>
                <p style='margin: 5px 0;'><strong>Pelapor:</strong> $namaSiswa</p>
                <p style='margin: 5px 0;'><strong>Kategori:</strong> $kategori</p>
            </div>
            
            <p style='color: #6b7280; font-size: 14px;'>
                Segera login untuk menindaklanjuti pengaduan ini.<br>
                <a href='" . BASE_URL . "/admin/pengaduan' style='color: #4f46e5;'>Klik di sini untuk melihat</a>
            </p>
            
            <hr style='border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;'>
            
            <p style='color: #9ca3af; font-size: 12px;'>
                Email ini dikirim otomatis oleh Sistem Prasarana Sekolah.<br>
                Mohon tidak membalas email ini.
            </p>
        ";

        return self::send($email, $subject, $message);
    }

    /**
     * Wrap message dengan template HTML
     */
    private static function wrapTemplate($subject, $content)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$subject</title>
        </head>
        <body style='
            font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
        '>
            <div style='
                max-width: 600px;
                margin: 0 auto;
                background: white;
                border-radius: 16px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            '>
                <div style='
                    background: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 100%);
                    padding: 30px;
                    text-align: center;
                '>
                    <h1 style='
                        color: white;
                        margin: 0;
                        font-size: 24px;
                        font-weight: 700;
                    '>Sistem Prasarana</h1>
                    <p style='
                        color: rgba(255, 255, 255, 0.9);
                        margin: 5px 0 0 0;
                        font-size: 14px;
                    '>Sekolah</p>
                </div>
                
                <div style='padding: 30px;'>
                    $content
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Get warna berdasarkan status
     */
    private static function getStatusColor($status)
    {
        $colors = [
            'Diajukan' => '#f59e0b',
            'Diproses' => '#3b82f6',
            'Selesai' => '#10b981',
            'Ditolak' => '#ef4444'
        ];

        return $colors[$status] ?? '#6b7280';
    }
}
