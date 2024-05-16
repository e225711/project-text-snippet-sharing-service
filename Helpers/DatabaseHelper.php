<?php

namespace Helpers;

require_once __DIR__ . '/../Database/MySQLWrapper.php';

use Database\MySQLWrapper;
use Exception;

class DatabaseHelper
{
  public static function saveSnippet(string $snippet_name, string $expiry_date, string $code_type, string $content, string $hash): bool
  {
    // Validate snippet_name
    if (empty($snippet_name) || strlen($snippet_name) > 255) {
      throw new Exception('Invalid snippet_name. It must be a non-empty string with a maximum length of 255 characters.');
    }

    // Calculate expiry_date_time
    $valid_durations = ['1 minute', '10 minutes', '1 hour', '1 day', 'Permanent'];
    if (!in_array($expiry_date, $valid_durations)) {
      throw new Exception('Invalid expiry_date. It must be one of the following values: ' . implode(', ', $valid_durations));
    }

    $expiry_date_time = null;
    if ($expiry_date === 'Permanent') {
      // Permanent, set expiry_date_time to null or a far future date
      $expiry_date_time = '9999-12-31 23:59:59'; // Far future date with time
    } else {
      // Calculate the expiry date based on the current time
      $expiry_date_time = (new \DateTime())->modify('+' . $expiry_date)->format('Y-m-d H:i:s');
    }

    // Validate code_type
    if (empty($code_type) || strlen($code_type) > 255) {
      throw new Exception('Invalid code_type. It must be a non-empty string with a maximum length of 255 characters.');
    }

    // Validate content
    if (empty($content)) {
      throw new Exception('Invalid content. It must be a non-empty string.');
    }

    $db = new MySQLWrapper();

    $stmt = $db->prepare("
            INSERT INTO snippet (snippet_name, expiry_date, code_type, content, hash)
            VALUES (?, ?, ?, ?, ?)
        ");
    if (!$stmt) {
      throw new Exception('Prepare statement failed: ' . $db->error);
    }

    $stmt->bind_param('sssss', $snippet_name, $expiry_date_time, $code_type, $content, $hash);
    $success = $stmt->execute();

    if (!$success) {
      throw new Exception('Could not insert snippet into database: ' . $stmt->error);
    }

    return $success;
  }

  private static function isValidDate(string $date): bool
  {
    $d = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
    return $d && $d->format('Y-m-d H:i:s') === $date;
  }
}
