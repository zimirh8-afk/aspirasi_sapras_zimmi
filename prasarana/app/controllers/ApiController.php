<?php

require_once __DIR__ . '/../models/Notifikasi.php';

class ApiController extends Controller
{
    private $notifikasiModel;

    public function __construct()
    {
        $this->notifikasiModel = new Notifikasi();
    }

    /**
     * Get notifications for current user
     */
    public function notifikasi()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user'])) {
            echo json_encode([]);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $notifs = $this->notifikasiModel->getByUserId($userId, 20);

        // Format untuk JSON
        $result = array_map(function($n) {
            return [
                'id' => $n['id'],
                'judul' => $n['judul'],
                'pesan' => $n['pesan'],
                'status' => $n['status'],
                'pengaduan_id' => $n['pengaduan_id'],
                'created_at' => $this->timeAgo($n['created_at'])
            ];
        }, $notifs);

        echo json_encode($result);
    }

    /**
     * Mark notification as read
     */
    public function readNotif($id)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false]);
            return;
        }

        $result = $this->notifikasiModel->markAsRead($id);
        echo json_encode(['success' => $result]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        try {
            $userId = $_SESSION['user']['id'];
            $result = $this->notifikasiModel->markAllAsRead($userId);
            echo json_encode(['success' => $result, 'message' => 'Semua notifikasi ditandai dibaca']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Time ago helper
     */
    private function timeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' menit yang lalu';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' jam yang lalu';
        } elseif ($diff < 604800) {
            return floor($diff / 86400) . ' hari yang lalu';
        } else {
            return date('d M Y', $timestamp);
        }
    }
}
