<?php
include 'config.php';

$result = null;
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Fetch feedbacks submitted by this user
    $stmt = $conn->prepare("SELECT id, message, created_at FROM feedback WHERE email = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Feedback</title>
  <style>
    body { background:#0d0d0d; color:#fff; font-family: Arial, sans-serif; }
    .container { margin:50px auto; max-width:800px; background:#1a1a1a; padding:20px; border-radius:10px; }
    h2 { color:#ffcc00; text-align:center; }
    form { margin-bottom:20px; text-align:center; }
    input[type="email"] { padding:10px; width:300px; border-radius:6px; border:none; }
    button { padding:10px 20px; border:none; border-radius:6px; background:#4CAF50; color:#fff; cursor:pointer; }
    button:hover { background:#45a049; }
    .feedback-card { background:#222; padding:15px; margin-bottom:15px; border-radius:8px; }
    .feedback-message { margin-bottom:10px; }
    .reply-box { margin-top:10px; background:#333; padding:10px; border-radius:6px; }
    .reply-box p { margin:0; color:#ffcc00; }
    .date { font-size:0.9em; color:#aaa; }
  </style>
</head>
<body>
  <div class="container">
    <h2>View My Feedback & Replies</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($email) ?>" required>
      <button type="submit">Search</button>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="feedback-card">
          <div class="feedback-message">
            <strong>Your Feedback:</strong> <?= nl2br(htmlspecialchars($row['message'])) ?>
          </div>
          <div class="date">Submitted on <?= $row['created_at'] ?></div>

          <!-- Fetch admin replies for this feedback -->
          <?php
          $stmt = $conn->prepare("SELECT reply_message, replied_at FROM feedback_replies WHERE feedback_id = ? ORDER BY replied_at ASC");
          $stmt->bind_param("i", $row['id']);
          $stmt->execute();
          $replies = $stmt->get_result();
          $stmt->close();

          if ($replies->num_rows > 0): ?>
            <?php while ($reply = $replies->fetch_assoc()): ?>
              <div class="reply-box">
                <p><strong>Admin Reply:</strong> <?= nl2br(htmlspecialchars($reply['reply_message'])) ?></p>
                <div class="date">Replied on <?= $reply['replied_at'] ?></div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="reply-box"><p>No replies yet.</p></div>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    <?php elseif ($email): ?>
      <p>No feedback found for this email.</p>
    <?php endif; ?>
  </div>
</body>
</html>
<?php $conn->close(); ?>
