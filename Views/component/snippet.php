<?php
use Database\MySQLWrapper;

$hash = $_GET['hash'] ?? null;
if (!$hash) {
  die('No hash provided');
}

$db = new MySQLWrapper();

try {
  $stmt = $db->prepare("SELECT * FROM snippet WHERE hash = ?");
  $stmt->bind_param("s", $hash);
  $stmt->execute();
  $result = $stmt->get_result();
  $hash = $result->fetch_assoc();
} catch (Exception $e) {
  die('Error: ' . $e->getMessage());
}

if (!$hash) {
  die('No snippet found with the provided hash');
}

// Check if expiry date is in the past
$expiry_date = strtotime($hash['expiry_date']);
$current_time = time();
if ($expiry_date && $expiry_date < $current_time) {
  echo '<div class="container">';
  echo '<p>このスニペットは、有効期限が切れています。</p>';
  echo '</div>';
  exit; // Stop further execution
}
?>

<div class="container">
  <h1><?php echo htmlspecialchars($hash['snippet_name']); ?></h1>
  <div class="snippet-content">
    <pre><code><?php echo htmlspecialchars($hash['content']); ?></code></pre>
  </div>
</div>
