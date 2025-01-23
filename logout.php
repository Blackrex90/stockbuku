<?php  
	require 'function.php';

	function logoutAndClearSession() {
		// Mulai sesi jika belum dimulai
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		// Hapus semua variabel sesi
		$_SESSION = [];

		// Hapus cookie sesi jika ada
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}

		// Hapus sesi di server
		session_destroy();
	}

	// Panggil fungsi untuk logout dan hapus sesi
	logoutAndClearSession();

	// Redirect ke halaman login dengan pesan logout
	header('Location: login.php');
	exit();
?>
