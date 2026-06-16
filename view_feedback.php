<?php
session_start();
include 'config.php';

// ✅ Ensure only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: view_feedback.php?deleted=1");
    exit();
}

// Handle reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_id'])) {
    $reply_id = intval($_POST['reply_id']);
    $reply_message = $_POST['reply_message'];

    // Save reply to DB
    $stmt = $conn->prepare("INSERT INTO feedback_replies (feedback_id, reply_message, replied_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $reply_id, $reply_message);
    $stmt->execute();
    $stmt->close();

    // Get user email
    $stmt = $conn->prepare("SELECT email FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->execute();
    $stmt->bind_result($user_email);
    $stmt->fetch();
    $stmt->close();

    echo "<p style='color:lightgreen; text-align:center;'>✅ Reply saved. (Email notification would be sent to <b>$user_email</b> on live server)</p>";
}

$result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Feedback</title>
  <style>
    body { font-family: Arial, sans-serif; background:#121212; color:#fff; padding:20px; }
    h2 { text-align: center; color:#ffcc00; }
    table { width: 100%; border-collapse: collapse; background:#1e1e1e; margin-top: 20px; border-radius:8px; overflow:hidden; }
    th, td { padding: 12px; border-bottom: 1px solid #333; text-align: left; vertical-align: top; }
    th { background: #333; color:#ffcc00; }
    tr:hover { background:#222; }

    /* Delete Button */
    .delete-btn { background: red; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; }
    .delete-btn:hover { background: darkred; }

    /* Reply Box */
    .reply-box { margin-top: 10px; }
    .reply-box textarea { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #444; background:#222; color:#fff; }
    .reply-box button { margin-top: 5px; padding: 6px 12px; background: #4CAF50; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
    .reply-box button:hover { background:#3e8e41; }

    /* Back Button */
    .back-btn {
      display:inline-block; margin-top:20px; padding:10px 15px;
      background:#ff4081; color:#fff; text-decoration:none; border-radius:6px;
    }
    .back-btn:hover { background:#e73370; }
  </style>
</head>
<body>
  <h2>Feedback Management</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Message</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
        <td><?= nl2br(htmlspecialchars($row['message'] ?? '')) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <a class="delete-btn" href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this feedback?')">Delete</a>
          <div class="reply-box">
            <form method="POST">
              <textarea name="reply_message" placeholder="Write your reply..." required></textarea>
              <input type="hidden" name="reply_id" value="<?= $row['id'] ?>">
              <button type="submit">Send Reply</button>
            </form>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div style="text-align:center;">
    <a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
<?php $conn->close(); ?>
