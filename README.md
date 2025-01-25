**Note:** Please ensure that Composer is installed before starting to try my project.

# Install Composer for Importing Data from Excel (All OS)

This guide explains the steps to install Composer and use a PHP library to import data from Excel files on Windows, macOS, and Linux operating systems.

---

## Prerequisites

1. **PHP**: Ensure PHP is installed on your system.
   - Run the following command to check:
     ```bash
     php -v
     ```
   - If not installed, download and install PHP according to your operating system.

2. **Composer**: Composer is a dependency manager for PHP.

---

## Installing Composer

### Windows
1. Download the Composer installer from [https://getcomposer.org/Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe).
2. Run the installer file.
3. Follow the on-screen instructions until completion.
4. Verify the installation:
   ```bash
   composer -v
   ```

### macOS/Linux
1. Run the following commands in the terminal:
   ```bash
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php composer-setup.php
   php -r "unlink('composer-setup.php');"
   ```
2. Move the Composer file to a global directory:
   ```bash
   sudo mv composer.phar /usr/local/bin/composer
   ```
3. Verify the installation:
   ```bash
   composer -v
   ```

---

## Installing the Library for Excel Import

Use the [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) library to import data from Excel.

1. Navigate to your project directory:
   ```bash
   cd /path/to/your-project
   ```

2. Add PhpSpreadsheet using Composer:
   ```bash
   composer require phpoffice/phpspreadsheet
   ```

---

## Example PHP Code

Here is a simple example to read an Excel file:

```php
<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Path to the Excel file
$filePath = 'data.xlsx';

// Reading the Excel file
$spreadsheet = IOFactory::load($filePath);
$sheet = $spreadsheet->getActiveSheet();
$data = $sheet->toArray();

// Displaying the data
print_r($data);
```
```

---

## Troubleshooting

### Composer Not Found
- Ensure Composer is added to your system PATH.
- Verify the installation with the command:
  ```bash
  composer -v
  ```

### Excel File Not Readable
- Ensure the file has sufficient permissions to be read by the application.
- Check that the file extension is correct (e.g., `.xlsx` or `.xls`).

---

## References
- [Composer](https://getcomposer.org/)
- [PhpSpreadsheet Documentation](https://phpspreadsheet.readthedocs.io/)

---

## After Installing Composer

**Enter the username, email, and password below to log in:**
```
-------------------------------------------------------------
Username       |   Email               |   Password      |
-------------------------------------------------------------
Admin          |   admin@gmail.com     |   admin         |
-------------------------------------------------------------
```

We hope this guide helps!
