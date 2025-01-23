<?php

	require 'vendor/autoload.php';

	// Create a database connection
	$conn = new mysqli("127.0.0.1", "root", "", "stockbuku");

	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;

	class SecureDatabaseSessionHandler implements SessionHandlerInterface {
		private $db;

		public function __construct() {
			$this->db = new PDO('mysql:host=127.0.0.1;dbname=stockbuku', 'root', '');
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		public function open($savePath, $sessionName): bool {
			return true;
		}

		public function close(): bool {
			return true;
		}

		public function read($id): string|false {
			$stmt = $this->db->prepare('SELECT data FROM sessions WHERE id = :id LIMIT 1');
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result ? $result['data'] : '';
		}

		public function write($id, $data): bool {
			$stmt = $this->db->prepare('REPLACE INTO sessions (id, data, last_access) VALUES (:id, :data, NOW())');
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->bindParam(':data', $data, PDO::PARAM_STR);
			return $stmt->execute();
		}

		public function destroy($id): bool {
			$stmt = $this->db->prepare('DELETE FROM sessions WHERE id = :id');
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			return $stmt->execute();
		}

		public function gc($maxlifetime): int|false {
			$stmt = $this->db->prepare('DELETE FROM sessions WHERE last_access < NOW() - INTERVAL :maxlifetime SECOND');
			$stmt->bindParam(':maxlifetime', $maxlifetime, PDO::PARAM_INT);
			return $stmt->execute();
		}
	}

	// Fungsi untuk mengenkripsi data menggunakan bcrypt
	function bcryptEncrypt($data): string {
		return password_hash($data, PASSWORD_BCRYPT);
	}

	// Fungsi untuk memverifikasi data terenkripsi
	function bcryptVerify($data, $hash): bool {
		return password_verify($data, $hash);
	}

	// Set session ini settings before starting the session
	ini_set('session.cookie_httponly', 1);
	ini_set('session.cookie_secure', 1); // Pastikan menggunakan HTTPS
	ini_set('session.cookie_samesite', 'Strict');

	// Set secure session handler
	session_set_save_handler(new SecureDatabaseSessionHandler(), true);

	// Start the session
	session_start();

	// Fungsi untuk memeriksa dan menyimpan IP address dan User Agent
	function initializeSession() {
		// Durasi sesi dalam detik
		$session_duration = 3600; // 60 menit

		// Memeriksa waktu aktivitas terakhir
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_duration)) {
			session_unset();
			session_destroy();
			session_start();
		}

		// Memperbarui waktu aktivitas terakhir
		$_SESSION['LAST_ACTIVITY'] = time();

		// Menyimpan IP address pengguna saat sesi dimulai
		if (isset($_SERVER['REMOTE_ADDR'])) {
			if (!isset($_SESSION['IP_ADDRESS'])) {
				$_SESSION['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];
			}

			// Memeriksa apakah IP address berubah
			if ($_SESSION['IP_ADDRESS'] !== $_SERVER['REMOTE_ADDR']) {
				session_unset();
				session_destroy();
				session_start();
			}
		}

		// Menyimpan User Agent pengguna saat sesi dimulai
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			if (!isset($_SESSION['USER_AGENT'])) {
				$_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			}

			// Memeriksa apakah User Agent berubah
			if ($_SESSION['USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']) {
				session_unset();
				session_destroy();
				session_start();
			}
		}
	}

	// Panggil fungsi untuk menginisialisasi sesi
	initializeSession();

	// Check for errors and display them
	if ($conn->connect_error) {
		logError("Connection failed : " . $conn->connect_error);
		exit;
	}

	// Enable error reporting and display errors
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// Tambahan keamanan: Input filtering
	function filterInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function showErrorMessage($message, $redirectURL) {
		echo "<script>
			document.addEventListener('DOMContentLoaded', function() {
				Swal.fire({
					icon: 'error',
					title: 'Oops...',
					text: '" . addslashes($message) . "',
					showConfirmButton: false,
					timer: 2000
				}).then(function() {
					window.location.href = '" . addslashes($redirectURL) . "';
				});
			});
		</script>";
	}
	
	function showSuccessMessage($message, $redirectURL) {
		echo "<script>
			document.addEventListener('DOMContentLoaded', function() {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil!',
					text: '" . addslashes($message) . "',
					showConfirmButton: false,
					timer: 2000
				}).then(function() {
					window.location.href = '" . addslashes($redirectURL) . "';
				});
			});
		</script>";
	}
	

	// Log errors to a file
	function logError($message) {
		error_log($message, 3, 'error.log');
		$logFile = '/opt/lampp/htdocs/stockbuku/logfile.log'; // Ganti dengan path ke file log Anda
		$currentDateTime = date('Y-m-d');
		$logMessage = "[$currentDateTime] ERROR: $message" . PHP_EOL;
		file_put_contents($logFile, $logMessage, FILE_APPEND);
	}

	// Fungsi untuk menghindari XSS
	function escapeHTML($data) {
		return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	}

	// Menambah data buku
	if (isset($_POST['submit_import'])) {
		$excelFile = $_FILES['excel_file']['tmp_name'];
		$allowedMimeTypes = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
	
		if ($_FILES['excel_file']['error'] == 0 && $excelFile) {
			if (!in_array($_FILES['excel_file']['type'], $allowedMimeTypes)) {
				showErrorMessage("Format file tidak didukung! Harap unggah file Excel.", "index.php");
				exit;
			}
	
			try {
				$spreadsheet = IOFactory::load($excelFile);
				$sheet = $spreadsheet->getActiveSheet();
				$rows = $sheet->toArray();
	
				foreach ($rows as $rowIndex => $row) {
					if ($rowIndex == 0) continue;
	
					$judulbuku = isset($row[0]) ? trim($row[0]) : null;
					$genre_buku = isset($row[1]) ? trim($row[1]) : null;
					$harga = isset($row[2]) && is_numeric($row[2]) ? (float)$row[2] : null;
					$stock = isset($row[3]) && is_numeric($row[3]) ? (int)$row[3] : null;
					$image = isset($row[4]) ? basename(trim($row[4])) : null;
	
					if (!$judulbuku || !$genre_buku || !$harga || !$stock || !$image) {
						showErrorMessage("Data tidak valid pada baris " . ($rowIndex + 1) . "!", "index.php");
						continue;
					}
	
					// Validasi apakah data sudah ada di database
					$checkStmt = $conn->prepare("SELECT COUNT(*) FROM buku WHERE judulbuku = ? AND genre_buku = ?");
					if (!$checkStmt) {
						showErrorMessage("Gagal mempersiapkan query validasi.", "index.php");
						continue;
					}
	
					$checkStmt->bind_param("ss", $judulbuku, $genre_buku);
					$checkStmt->execute();
					$checkStmt->bind_result($count);
					$checkStmt->fetch();
					$checkStmt->close();
	
					if ($count > 0) {
						showErrorMessage("Data buku '$judulbuku' dengan genre '$genre_buku' sudah ada di database. Baris diabaikan.", "index.php");
						continue;
					}
	
					// Validasi gambar
					$originalImagePath = "/opt/lampp/htdocs/stockbuku/images/" . $image;
					if (!file_exists($originalImagePath)) {
						showErrorMessage("Gambar '$image' tidak ditemukan pada baris " . ($rowIndex + 1) . ".", "index.php");
						continue;
					}
	
					// Buat nama unik untuk gambar
					$hashedImageName = md5($image . time() . rand()) . '.' . pathinfo($image, PATHINFO_EXTENSION);
					$newImagePath = "/opt/lampp/htdocs/stockbuku/images/" . $hashedImageName;
	
					if (!copy($originalImagePath, $newImagePath)) {
						showErrorMessage("Gagal menyalin gambar '$image' pada baris " . ($rowIndex + 1) . ".", "index.php");
						continue;
					}
	
					// Simpan data ke database
					$stmt = $conn->prepare("INSERT INTO buku (judulbuku, genre_buku, harga, stock, image) VALUES (?, ?, ?, ?, ?)");
					if (!$stmt) {
						showErrorMessage("Gagal mempersiapkan query database.", "index.php");
						continue;
					}
	
					$stmt->bind_param("ssdds", $judulbuku, $genre_buku, $harga, $stock, $hashedImageName);
	
					if ($stmt->execute()) {
						showSuccessMessage("Data buku berhasil ditambahkan: $judulbuku", "index.php");
					} else {
						showErrorMessage("Gagal menyimpan data buku pada baris " . ($rowIndex + 1) . ": " . $stmt->error, "index.php");
					}
	
					$stmt->close();
				}
	
				showSuccessMessage("Proses impor data selesai!", "index.php");
			} catch (Exception $e) {
				showErrorMessage("Terjadi kesalahan: " . $e->getMessage(), "index.php");
			}
		} else {
			showErrorMessage("Harap pilih file Excel untuk diimpor!", "index.php");
		}
	}
	

	// Update data buku
	if (isset($_POST['updatebuku'])) {
		// Ambil data dari form
		$idb = intval($_POST['idb']); // ID buku yang akan diupdate
		$judulbuku = htmlspecialchars(trim($_POST['judulbuku']), ENT_QUOTES, 'UTF-8');
		$genre_buku = htmlspecialchars(trim($_POST['genre_buku']), ENT_QUOTES, 'UTF-8');
		$harga = filter_var($_POST['harga'], FILTER_VALIDATE_FLOAT);
		$stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

		// Validasi data
		if ($judulbuku && $genre_buku && $harga !== false && $stock !== false) {
			// Periksa apakah data dengan judul dan genre sudah ada tetapi bukan untuk buku yang sedang diupdate
			$checkStmt = $conn->prepare("SELECT COUNT(*) FROM buku WHERE judulbuku = ? AND genre_buku = ? AND idbuku != ?");
			if (!$checkStmt) {
				showErrorMessage("Gagal mempersiapkan query validasi.", "index.php");
				return;
			}

			$checkStmt->bind_param("ssi", $judulbuku, $genre_buku, $idb);
			$checkStmt->execute();
			$checkStmt->bind_result($count);
			$checkStmt->fetch();
			$checkStmt->close();

			if ($count > 0) {
				// Jika data sudah ada, tampilkan pesan kesalahan
				showErrorMessage("Buku dengan judul '$judulbuku' dan genre '$genre_buku' sudah ada. Perubahan tidak dapat dilakukan.", "index.php");
				return;
			}

			// Prepare statement untuk update buku
			$stmt = $conn->prepare("UPDATE buku SET judulbuku = ?, genre_buku = ?, harga = ?, stock = ? WHERE idbuku = ?");
			if ($stmt === false) {
				showErrorMessage("Gagal mempersiapkan query database.", "index.php");
				return;
			}

			// Bind parameter
			$stmt->bind_param("ssdii", $judulbuku, $genre_buku, $harga, $stock, $idb);

			// Eksekusi query
			if ($stmt->execute()) {
				// Jika berhasil diupdate
				showSuccessMessage("Data buku berhasil diperbarui!", "index.php");
			} else {
				// Jika gagal diupdate
				showErrorMessage("Gagal memperbarui data buku: " . $stmt->error, "index.php");
			}

			$stmt->close();
		} else {
			// Jika data tidak valid
			showErrorMessage("Data tidak valid. Pastikan semua input diisi dengan benar.", "index.php");
		}
	}

	
	

	// Menambah data penerbit
	if (isset($_POST['importData'])) {
		if (!empty($_FILES['file_excel']['tmp_name'])) {
			$file = $_FILES['file_excel']['tmp_name'];
			$fileType = mime_content_type($file);

			// Validasi apakah file yang diunggah adalah file Excel
			if ($fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $fileType === 'application/vnd.ms-excel') {
				try {
					// Membaca file Excel menggunakan PhpSpreadsheet
					$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
					$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

					unset($sheetData[1]); // Abaikan header pada baris pertama
					foreach ($sheetData as $row) {
						// Mengambil data dari setiap kolom
						$idpenerbit = trim($row['A'] ?? '');
						$nama = trim($row['B'] ?? '');
						$email = trim($row['C'] ?? '');
						$negara_asal = trim($row['D'] ?? '');

						// Validasi data yang diperlukan
						if (empty($idpenerbit) || empty($nama) || empty($email) || empty($negara_asal)) {
							showErrorMessage("Data tidak lengkap pada ID Penerbit: $idpenerbit!", "penerbit.php");
							continue;
						}

						// Validasi email
						if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
							showErrorMessage("Format email tidak valid: $email!", "penerbit.php");
							continue;
						}

						// Cek apakah ID penerbit sudah ada di database
						$checkQuery = $conn->prepare("SELECT idpenerbit FROM penerbit WHERE idpenerbit = ?");
						$checkQuery->bind_param("s", $idpenerbit);
						$checkQuery->execute();
						$checkQuery->store_result();

						if ($checkQuery->num_rows > 0) {
							showErrorMessage("ID Penerbit sudah ada: $idpenerbit!", "penerbit.php");
							$checkQuery->close();
							continue;
						}
						$checkQuery->close();

						// Menambahkan data penerbit ke tabel
						$insertQuery = $conn->prepare("INSERT INTO penerbit (idpenerbit, nama, email, negara_asal) VALUES (?, ?, ?, ?)");
						if ($insertQuery) {
							$insertQuery->bind_param("ssss", $idpenerbit, $nama, $email, $negara_asal);
							if (!$insertQuery->execute()) {
								showErrorMessage("Gagal menyimpan data untuk ID Penerbit: $idpenerbit!", "penerbit.php");
							}
							$insertQuery->close();
						} else {
							showErrorMessage("Gagal mempersiapkan query untuk ID Penerbit: $idpenerbit!", "penerbit.php");
						}
					}
					// Tampilkan pesan sukses setelah semua data berhasil diproses
					showSuccessMessage("Proses impor data penerbit selesai!", "penerbit.php");
				} catch (Exception $e) {
					// Menangani kesalahan saat membaca file Excel
					showErrorMessage("Terjadi kesalahan saat membaca file Excel: " . $e->getMessage(), "penerbit.php");
				}
			} else {
				// Jika file bukan format Excel
				showErrorMessage("Format file tidak valid! Harap unggah file Excel (.xls/.xlsx).", "penerbit.php");
			}
		} else {
			// Jika tidak ada file yang diunggah
			showErrorMessage("Harap unggah file Excel terlebih dahulu.", "penerbit.php");
		}
	}


	// Update info penerbit
	if (isset($_POST['updatepenerbit'])) {
		$idpenerbit = htmlspecialchars(trim($_POST['idpenerbit']), ENT_QUOTES, 'UTF-8');
		$nama_penerbit = htmlspecialchars(trim($_POST['nama_penerbit']), ENT_QUOTES, 'UTF-8');
		$email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
		$negara_asal = htmlspecialchars(trim($_POST['negara_asal']), ENT_QUOTES, 'UTF-8');

		// Validasi data
		if (empty($idpenerbit) || empty($nama_penerbit) || empty($email) || empty($negara_asal)) {
			showErrorMessage("Semua field harus diisi!", "penerbit.php");
			return;
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			showErrorMessage("Format email tidak valid!", "penerbit.php");
			return;
		}

		// Eksekusi query update
		$updateQuery = $conn->prepare("UPDATE penerbit SET nama = ?, email = ?, negara_asal = ? WHERE idpenerbit = ?");
		if (!$updateQuery) {
			showErrorMessage("Gagal mempersiapkan query update.", "penerbit.php");
			return;
		}

		$updateQuery->bind_param("ssss", $nama_penerbit, $email, $negara_asal, $idpenerbit);

		if ($updateQuery->execute()) {
			showSuccessMessage("Data berhasil diperbarui!", "penerbit.php");
		} else {
			showErrorMessage("Gagal memperbarui data!", "penerbit.php");
		}
		$updateQuery->close();
	}



	// Menambah data pengirim
	if (isset($_POST['importDataPengirim'])) {
		if (!empty($_FILES['file_excel']['tmp_name'])) {
			$file = $_FILES['file_excel']['tmp_name'];
			$fileType = mime_content_type($file);
	
			// Validasi apakah file yang diunggah adalah file Excel
			if ($fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $fileType === 'application/vnd.ms-excel') {
				try {
					// Membaca file Excel menggunakan PhpSpreadsheet
					$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
					$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
	
					unset($sheetData[1]); // Abaikan header pada baris pertama
					foreach ($sheetData as $row) {
						// Mengambil data dari setiap kolom
						$idpenerbit = trim($row['A'] ?? '');
						$tanggal_kirim = trim($row['B'] ?? '');
						$nobukti = trim($row['C'] ?? '');
	
						// Validasi data yang diperlukan
						if (empty($idpenerbit) || empty($tanggal_kirim) || empty($nobukti)) {
							showErrorMessage("Data tidak lengkap pada No Bukti: $nobukti!", "pengirim.php");
							continue;
						}
	
						// Validasi bahwa tanggal_beli adalah tanggal yang valid
						$tanggal_kirim = date('Y-m-d', strtotime($tanggal_kirim));
						if (!$tanggal_kirim) {
							showErrorMe_kirim("Format tanggal tidak valid pada ID Penerbit: $idpenerbit!", "pengirim.php");
							continue;
						}
	
						// Cek apakah ID penerbit ada di database
						$checkPenerbitQuery = $conn->prepare("SELECT idpenerbit, nama FROM penerbit WHERE idpenerbit = ?");
						$checkPenerbitQuery->bind_param("s", $idpenerbit);
						$checkPenerbitQuery->execute();
						$checkPenerbitQuery->store_result();
	
						if ($checkPenerbitQuery->num_rows == 0) {
							showErrorMessage("ID Penerbit tidak ditemukan: $idpenerbit!", "pengirim.php");
							$checkPenerbitQuery->close();
							continue;
						}
	
						// Ambil nama penerbit
						$checkPenerbitQuery->bind_result($idpenerbit, $nama);
						$checkPenerbitQuery->fetch();
						$checkPenerbitQuery->close();
	
						// Cek apakah No Bukti sudah ada di database
						$checkQuery = $conn->prepare("SELECT nobukti FROM pengirim WHERE nobukti = ?");
						$checkQuery->bind_param("s", $nobukti);
						$checkQuery->execute();
						$checkQuery->store_result();
	
						if ($checkQuery->num_rows > 0) {
							showErrorMessage("No Bukti sudah ada: $nobukti!", "pengirim.php");
							$checkQuery->close();
							continue;
						}
						$checkQuery->close();
	
						// Menambahkan data pengirim ke tabel
						$insertQuery = $conn->prepare("INSERT INTO pengirim (idpenerbit, tanggal_kirim, nobukti, nama) VALUES (?, ?, ?, ?)");
						if ($insertQuery) {
							$insertQuery->bind_param("ssss", $idpenerbit, $tanggal_kirim, $nobukti, $nama);
							if (!$insertQuery->execute()) {
								showErrorMessage("Gagal menyimpan data untuk No Bukti: $nobukti!", "pengirim.php");
							}
							$insertQuery->close();
						} else {
							showErrorMessage("Gagal mempersiapkan query untuk No Bukti: $nobukti!", "pengirim.php");
						}
					}
					// Tampilkan pesan sukses setelah semua data berhasil diproses
					showSuccessMessage("Proses impor data pengirim selesai!", "pengirim.php");
				} catch (Exception $e) {
					// Menangani kesalahan saat membaca file Excel
					showErrorMessage("Terjadi kesalahan saat membaca file Excel: " . $e->getMessage(), "pengirim.php");
				}
			} else {
				// Jika file bukan format Excel
				showErrorMessage("Format file tidak valid! Harap unggah file Excel (.xls/.xlsx).", "pengirim.php");
			}
		} else {
			// Jika tidak ada file yang diunggah
			showErrorMessage("Harap unggah file Excel terlebih dahulu.", "pengirim.php");
		}
	}

	// Menambah data pembeli
	if (isset($_POST['importData'])) {
		if (!empty($_FILES['file_excel']['tmp_name'])) {
			$file = $_FILES['file_excel']['tmp_name'];
			$fileType = mime_content_type($file);

			// Validasi apakah file yang diunggah adalah file Excel
			if ($fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $fileType === 'application/vnd.ms-excel') {
				try {
					// Membaca file Excel menggunakan PhpSpreadsheet
					$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
					$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

					unset($sheetData[1]); // Abaikan header pada baris pertama
					foreach ($sheetData as $row) {
						// Mengambil data dari setiap kolom
						$idpembeli = trim($row['A'] ?? '');
						$nama_pembeli = trim($row['B'] ?? '');
						$tanggal_beli = trim($row['C'] ?? '');

						// Validasi data yang diperlukan
						if (empty($idpembeli) || empty($nama_pembeli) || empty($tanggal_beli)) {
							showErrorMessage("Data tidak lengkap pada ID Pembeli: $idpembeli!", "pembeli.php");
							continue;
						}

						// Validasi bahwa idpembeli adalah angka
						if (!is_numeric($idpembeli)) {
							showErrorMessage("ID Pembeli harus berupa angka: $idpembeli!", "pembeli.php");
							continue;
						}

						// Validasi bahwa tanggal_beli adalah tanggal yang valid
						$tanggal_beli = date('Y-m-d', strtotime($tanggal_beli));
						if (!$tanggal_beli) {
							showErrorMessage("Format tanggal tidak valid pada ID Pembeli: $idpembeli!", "pembeli.php");
							continue;
						}

						// Cek apakah ID pembeli sudah ada di database
						$checkQuery = $conn->prepare("SELECT idpembeli FROM pembeli WHERE idpembeli = ?");
						$checkQuery->bind_param("i", $idpembeli);
						$checkQuery->execute();
						$checkQuery->store_result();

						if ($checkQuery->num_rows > 0) {
							showErrorMessage("ID Pembeli sudah ada: $idpembeli!", "pembeli.php");
							$checkQuery->close();
							continue;
						}
						$checkQuery->close();

						// Menambahkan data pembeli ke tabel
						$insertQuery = $conn->prepare("INSERT INTO pembeli (idpembeli, nama_pembeli, tanggal_beli) VALUES (?, ?, ?)");
						if ($insertQuery) {
							$insertQuery->bind_param("iss", $idpembeli, $nama_pembeli, $tanggal_beli);
							if (!$insertQuery->execute()) {
								showErrorMessage("Gagal menyimpan data untuk ID Pembeli: $idpembeli!", "pembeli.php");
							}
							$insertQuery->close();
						} else {
							showErrorMessage("Gagal mempersiapkan query untuk ID Pembeli: $idpembeli!", "pembeli.php");
						}
					}
					// Tampilkan pesan sukses setelah semua data berhasil diproses
					showSuccessMessage("Proses impor data pembeli selesai!", "pembeli.php");
				} catch (Exception $e) {
					// Menangani kesalahan saat membaca file Excel
					showErrorMessage("Terjadi kesalahan saat membaca file Excel: " . $e->getMessage(), "pembeli.php");
				}
			} else {
				// Jika file bukan format Excel
				showErrorMessage("Format file tidak valid! Harap unggah file Excel (.xls/.xlsx).", "pembeli.php");
			}
		} else {
			// Jika tidak ada file yang diunggah
			showErrorMessage("Harap unggah file Excel terlebih dahulu.", "pembeli.php");
		}
	}
	

	// Menambah data detail pembeli
	if (isset($_POST['submit_import'])) {
		if (!empty($_FILES['excel_file']['tmp_name'])) {
			$file = $_FILES['excel_file']['tmp_name'];
			$fileType = mime_content_type($file);

			// Validasi apakah file yang diunggah adalah file Excel
			if ($fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $fileType === 'application/vnd.ms-excel') {
				try {
					// Membaca file Excel menggunakan PhpSpreadsheet
					$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
					$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

					unset($sheetData[1]); // Abaikan header pada baris pertama
					foreach ($sheetData as $rowNumber => $row) {
						// Mengambil data dari setiap kolom
						$nama_pembeli = trim($row['A'] ?? '');
						$judulbuku = trim($row['B'] ?? '');
						$qty = trim($row['C'] ?? '');

						// Validasi data yang diperlukan
						if (empty($nama_pembeli) || empty($judulbuku) || empty($qty)) {
							$errorMessage = "Data tidak lengkap pada baris $rowNumber: Nama Pembeli: $nama_pembeli, Judul Buku: $judulbuku, Qty: $qty";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pembeli.php");
							continue;
						}

						// Validasi bahwa qty adalah angka
						if (!is_numeric($qty) || $qty <= 0) {
							$errorMessage = "Jumlah harus berupa angka positif pada baris $rowNumber: Qty: $qty";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pembeli.php");
							continue;
						}

						// Cek apakah nama pembeli ada di database
						$checkPembeliQuery = $conn->prepare("SELECT idpembeli FROM pembeli WHERE nama_pembeli = ?");
						$checkPembeliQuery->bind_param("s", $nama_pembeli);
						$checkPembeliQuery->execute();
						$checkPembeliQuery->store_result();
						$checkPembeliQuery->bind_result($idpembeli);
						$checkPembeliQuery->fetch();

						if ($checkPembeliQuery->num_rows == 0) {
							$errorMessage = "Nama Pembeli tidak ditemukan: $nama_pembeli";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pembeli.php");
							$checkPembeliQuery->close();
							continue;
						}
						$checkPembeliQuery->close();

						// Cek apakah judul buku ada di database dan ambil harga serta stok
						$checkBukuQuery = $conn->prepare("SELECT idbuku, harga, stock FROM buku WHERE judulbuku = ?");
						$checkBukuQuery->bind_param("s", $judulbuku);
						$checkBukuQuery->execute();
						$checkBukuQuery->store_result();
						$checkBukuQuery->bind_result($idbuku, $harga, $stock);
						$checkBukuQuery->fetch();

						if ($checkBukuQuery->num_rows == 0) {
							$errorMessage = "Judul Buku tidak ditemukan: $judulbuku";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pembeli.php");
							$checkBukuQuery->close();
							continue;
						}

						// Validasi stok
						if ($stock < $qty) {
							$errorMessage = "Stok tidak mencukupi untuk Judul Buku: $judulbuku";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pembeli.php");
							$checkBukuQuery->close();
							continue;
						}
						$checkBukuQuery->close();

						// Mengurangi stok buku
						$updateStockQuery = $conn->prepare("UPDATE buku SET stock = stock - ? WHERE idbuku = ?");
						$updateStockQuery->bind_param("ii", $qty, $idbuku);
						if (!$updateStockQuery->execute()) {
							$errorMessage = "Gagal mengurangi stok untuk Judul Buku: $judulbuku";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pembeli.php");
							$updateStockQuery->close();
							continue;
						}
						$updateStockQuery->close();

						// Menghitung total harga
						$totalHarga = $harga * $qty;

						// Cek apakah data sudah ada di tabel detail_pembeli
						$checkDetailQuery = $conn->prepare("SELECT idpembeli FROM detil_pembeli WHERE idpembeli = ? AND idbuku = ?");
						$checkDetailQuery->bind_param("ii", $idpembeli, $idbuku);
						$checkDetailQuery->execute();
						$checkDetailQuery->store_result();

						if ($checkDetailQuery->num_rows == 0) {
							// Menambahkan data ke tabel detail_pembeli
							$insertQuery = $conn->prepare("INSERT INTO detil_pembeli (idpembeli, idbuku, qty, harga) VALUES (?, ?, ?, ?)");
							if ($insertQuery) {
								$insertQuery->bind_param("iiid", $idpembeli, $idbuku, $qty, $totalHarga);
								if (!$insertQuery->execute()) {
									$errorMessage = "Gagal menyimpan data untuk Nama Pembeli: $nama_pembeli";
									logError($errorMessage);
									showErrorMessage($errorMessage, "detail_pembeli.php");
								}
								$insertQuery->close();
							} else {
								$errorMessage = "Gagal mempersiapkan query untuk Nama Pembeli: $nama_pembeli";
								logError($errorMessage);
								showErrorMessage($errorMessage, "detail_pembeli.php");
							}
						} else {
							$errorMessage = "Data sudah ada untuk Nama Pembeli: $nama_pembeli dan Judul Buku: $judulbuku";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pembeli.php");
						}
						$checkDetailQuery->close();
					}
					// Tampilkan pesan sukses setelah semua data berhasil diproses
					showSuccessMessage("Proses impor data detail pembeli selesai!", "detail_pembeli.php");
				} catch (Exception $e) {
					// Menangani kesalahan saat membaca file Excel
					$errorMessage = "Terjadi kesalahan saat membaca file Excel: " . $e->getMessage();
					logError($errorMessage);
					showErrorMessage($errorMessage, "detail_pembeli.php");
				}
			} else {
				// Jika file bukan format Excel
				$errorMessage = "Format file tidak valid! Harap unggah file Excel (.xls/.xlsx).";
				logError($errorMessage);
				showErrorMessage($errorMessage, "detail_pembeli.php");
			}
		} else {
			// Jika tidak ada file yang diunggah
			$errorMessage = "Harap unggah file Excel terlebih dahulu.";
			logError($errorMessage);
			showErrorMessage($errorMessage, "detail_pembeli.php");
		}
	}


	// Menambah data detil pengirim
	if (isset($_POST['submit_import'])) {
		if (!empty($_FILES['excel_file']['tmp_name'])) {
			$file = $_FILES['excel_file']['tmp_name'];
			$fileType = mime_content_type($file);
	
			// Validasi apakah file yang diunggah adalah file Excel
			if ($fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $fileType === 'application/vnd.ms-excel') {
				try {
					// Membaca file Excel menggunakan PhpSpreadsheet
					$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
					$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
	
					unset($sheetData[1]); // Abaikan header pada baris pertama
					foreach ($sheetData as $rowNumber => $row) {
						// Mengambil data dari setiap kolom
						$nobukti = trim($row['A'] ?? '');
						$judulbuku = trim($row['B'] ?? '');
						$qty = trim($row['C'] ?? '');
						$harga = trim($row['D'] ?? ''); // Tambahkan kolom harga
	
						// Validasi data yang diperlukan
						if (empty($nobukti) || empty($judulbuku) || empty($qty) || empty($harga)) {
							$errorMessage = "Data tidak lengkap pada baris $rowNumber: No Bukti: $nobukti, Judul Buku: $judulbuku, Qty: $qty, Harga: $harga";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pengirim.php");
							continue;
						}
	
						// Validasi bahwa qty dan harga adalah angka
						if (!is_numeric($qty) || $qty <= 0 || !is_numeric($harga) || $harga <= 0) {
							$errorMessage = "Jumlah dan Harga harus berupa angka positif pada baris $rowNumber: Qty: $qty, Harga: $harga";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pengirim.php");
							continue;
						}
	
						// Cek apakah nomor bukti ada di database pengirim
						$checkNobuktiQuery = $conn->prepare("SELECT nobukti FROM pengirim WHERE nobukti = ?");
						$checkNobuktiQuery->bind_param("s", $nobukti);
						$checkNobuktiQuery->execute();
						$checkNobuktiQuery->store_result();
	
						if ($checkNobuktiQuery->num_rows == 0) {
							$errorMessage = "Nomor Bukti tidak ditemukan: $nobukti";
							logError($errorMessage);
							showErrorMessage($errorMessage, "detail_pengirim.php");
							$checkNobuktiQuery->close();
							continue;
						}
						$checkNobuktiQuery->close();
	
						// Cek apakah judul buku ada di database
						$checkBukuQuery = $conn->prepare("SELECT idbuku, harga, stock FROM buku WHERE judulbuku = ?");
						$checkBukuQuery->bind_param("s", $judulbuku);
						$checkBukuQuery->execute();
						$checkBukuQuery->store_result();
						$checkBukuQuery->bind_result($idbuku, $harga_buku, $stock);
						$checkBukuQuery->fetch();
	
						if ($checkBukuQuery->num_rows == 0) {
							// Jika buku tidak ditemukan, tambahkan buku baru
							$insertBukuQuery = $conn->prepare("INSERT INTO buku (judulbuku, stock, harga) VALUES (?, ?, ?)");
							$insertBukuQuery->bind_param("sid", $judulbuku, $qty, $harga);
							if (!$insertBukuQuery->execute()) {
								$errorMessage = "Gagal menambahkan buku baru: $judulbuku";
								logError($errorMessage);
								showErrorMessage($errorMessage, "detail_pengirim.php");
								$insertBukuQuery->close();
								continue;
							}
							$idbuku = $insertBukuQuery->insert_id;
							$insertBukuQuery->close();
						} else {
							// Jika buku ditemukan, tambahkan qty ke stock
							$updateStockQuery = $conn->prepare("UPDATE buku SET stock = stock + ? WHERE idbuku = ?");
							$updateStockQuery->bind_param("ii", $qty, $idbuku);
							if (!$updateStockQuery->execute()) {
								$errorMessage = "Gagal menambahkan stok untuk Judul Buku: $judulbuku";
								logError($errorMessage);
								showErrorMessage($errorMessage, "detail_pengirim.php");
								$updateStockQuery->close();
								continue;
							}
							$updateStockQuery->close();
						}
						$checkBukuQuery->close();
	
						// Menghitung harga berdasarkan aturan impor yang berlaku di Indonesia tahun ini
						// Misalnya, harga dihitung berdasarkan harga dasar + pajak impor 10%
						$harga = $harga * 1.10;
	
						// Cek apakah entri sudah ada di tabel detil_pengirim
						$checkDetilPengirimQuery = $conn->prepare("SELECT nobukti FROM detil_pengirim WHERE nobukti = ? AND idbuku = ?");
						$checkDetilPengirimQuery->bind_param("si", $nobukti, $idbuku);
						$checkDetilPengirimQuery->execute();
						$checkDetilPengirimQuery->store_result();
	
						if ($checkDetilPengirimQuery->num_rows > 0) {
							// Jika entri sudah ada, perbarui data yang ada
							$updateDetilPengirimQuery = $conn->prepare("UPDATE detil_pengirim SET harga = ?, qty = qty + ? WHERE nobukti = ? AND idbuku = ?");
							$updateDetilPengirimQuery->bind_param("disi", $harga, $qty, $nobukti, $idbuku);
							if (!$updateDetilPengirimQuery->execute()) {
								$errorMessage = "Gagal memperbarui data untuk No Bukti: $nobukti";
								logError($errorMessage);
								showErrorMessage($errorMessage, "detail_pengirim.php");
							}
							$updateDetilPengirimQuery->close();
						} else {
							// Jika entri belum ada, tambahkan data baru
							$insertQuery = $conn->prepare("INSERT INTO detil_pengirim (nobukti, idbuku, harga, qty) VALUES (?, ?, ?, ?)");
							if ($insertQuery) {
								$insertQuery->bind_param("siid", $nobukti, $idbuku, $harga, $qty);
								if (!$insertQuery->execute()) {
									$errorMessage = "Gagal menyimpan data untuk No Bukti: $nobukti";
									logError($errorMessage);
									showErrorMessage($errorMessage, "detail_pengirim.php");
								}
								$insertQuery->close();
							} else {
								$errorMessage = "Gagal mempersiapkan query untuk No Bukti: $nobukti";
								logError($errorMessage);
								showErrorMessage($errorMessage, "detail_pengirim.php");
							}
						}
						$checkDetilPengirimQuery->close();
					}
					// Tampilkan pesan sukses setelah semua data berhasil diproses
					showSuccessMessage("Proses impor data detil pengirim selesai!", "detail_pengirim.php");
				} catch (Exception $e) {
					// Menangani kesalahan saat membaca file Excel
					$errorMessage = "Terjadi kesalahan saat membaca file Excel: " . $e->getMessage();
					logError($errorMessage);
					showErrorMessage($errorMessage, "detail_pengirim.php");
				}
			} else {
				// Jika file bukan format Excel
				$errorMessage = "Format file tidak valid! Harap unggah file Excel (.xls/.xlsx).";
				logError($errorMessage);
				showErrorMessage($errorMessage, "detail_pengirim.php");
			}
		} else {
			// Jika tidak ada file yang diunggah
			$errorMessage = "Harap unggah file Excel terlebih dahulu.";
			logError($errorMessage);
			showErrorMessage($errorMessage, "detail_pengirim.php");
		}
	}

	// Mengedit data admin
	if (isset($_POST['updateadmin'])) {
		$id = $_POST['id'];
		$usernamebaru = $_POST['username'];
		$emailbaru = $_POST['emailadmin'];
		$passwordbaru = $_POST['passwordbaru'];
		
		// Buat query untuk mengupdate data di tabel login saja
		if (empty($passwordbaru)) {
			// Jika password tidak diubah
			$query = "UPDATE login SET username=?, email=? WHERE iduser=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("sssi", $usernamebaru, $emailbaru, $id);
		} else {
			// Jika password diubah
			$passwordbaru = password_hash($passwordbaru, PASSWORD_DEFAULT);
			$query = "UPDATE login SET username=?, email=?, password=? WHERE iduser=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param("ssssi", $usernamebaru, $emailbaru, $passwordbaru, $id);
		}

		if ($stmt->execute()) {
			showSuccessMessage("Admin berhasil diperbarui", "admin.php");
		} else {
			showErrorMessage("Gagal memperbarui admin", "admin.php");
		}
	}




	// Menambah data admin baru
	if (isset($_POST['addnewadmin'])) {
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		$query = "INSERT INTO login (username, email, password) VALUES (?, ?, ?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("sss", $username, $email, $password);

		if ($stmt->execute()) {
			showSuccessMessage("Admin berhasil ditambahkan", "admin.php");
		} else {
			showErrorMessage("Gagal menambahkan admin", "admin.php");
		}
	}



	// Menghapus data admin
	if (isset($_POST['hapusadmin'])) {
		$id = $_POST['id'];
		
		$query = "DELETE FROM login WHERE iduser=?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("i", $id);

		if ($stmt->execute()) {
			showSuccessMessage("Admin berhasil dihapus", "admin.php");
		} else {
			showErrorMessage("Gagal menghapus admin", "admin.php");
		}
	}



?>
