  <main>
    <div class="auth-container">
      <h1 class="auth-title">Create Account</h1>

      <?php if (!empty($errors ?? [])) : ?>
        <div class="error-msg" style="color: red; margin-bottom: 1rem;">
          <ul style="margin: 0; padding-left: 1.2rem;">
            <?php foreach ($errors as $error) : ?>
              <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" action="<?php echo $base_path; ?>/signup">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create a strong password" required>
        </div>

        <div class="form-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
        </div>

        <button class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1rem;">Sign Up</button>
      </form>

      <div class="auth-link">
        <p>Already have an account? <a href="<?php echo $base_path; ?>/login">Login here</a></p>
      </div>

      <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color); text-align: center; color: var(--text-light); font-size: 0.9rem;">
        <p>🔒 Your account is protected with encryption</p>
        <p>By signing up, you agree to our <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></p>
      </div>
    </div>
  </main>
