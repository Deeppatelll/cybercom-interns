/**
 * Auth (Login/Signup) Validations
 */

document.addEventListener('DOMContentLoaded', function() {
  const basePath = (window.EASYCART_BASE_PATH || '').replace(/\/$/, '');
  const toUrl = (path) => `${basePath}${path}`;
  const currentPath = document.location.pathname;
  const normalizedPath = basePath && currentPath.startsWith(basePath)
    ? currentPath.slice(basePath.length)
    : currentPath;

  const showInputError = (input, message) => {
    let existingError = input.parentElement.querySelector('.error-msg');
    if (existingError) {
      existingError.textContent = message;
    } else {
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-msg';
      errorDiv.style.color = 'red';
      errorDiv.style.fontSize = '0.85rem';
      errorDiv.style.marginTop = '4px';
      errorDiv.textContent = message;
      input.parentElement.appendChild(errorDiv);
    }
    input.style.borderColor = 'red';
  };

  const clearInputError = (input) => {
    let existingError = input.parentElement.querySelector('.error-msg');
    if (existingError) {
      existingError.remove();
    }
    input.style.borderColor = '';
  };

  const validateEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  if (normalizedPath.match(/\/login(\/|$)/)) {
    const mainLoginForm = document.querySelector('.auth-container form');

    if (mainLoginForm) {
      mainLoginForm.addEventListener('submit', function(e) {
        let isValid = true;
        const emailInput = mainLoginForm.querySelector('input[type="email"]');
        const passInput = mainLoginForm.querySelector('input[type="password"]');

        clearInputError(emailInput);
        clearInputError(passInput);

        if (!emailInput.value.trim()) {
          showInputError(emailInput, 'Email is required');
          isValid = false;
        } else if (!validateEmail(emailInput.value.trim())) {
          showInputError(emailInput, 'Please enter a valid email');
          isValid = false;
        }

        if (!passInput.value.trim()) {
          showInputError(passInput, 'Password is required');
          isValid = false;
        }

        if (!isValid) {
          e.preventDefault();
        }
      });
    }
  }

  if (normalizedPath.match(/\/signup(\/|$)/)) {
    const signupForm = document.querySelector('.auth-container form');
    if (signupForm) {
      signupForm.addEventListener('submit', function(e) {
        let isValid = true;
        const nameIn = signupForm.querySelector('input[id*="name"]');
        const emailIn = signupForm.querySelector('input[type="email"]');
        const passIn = signupForm.querySelectorAll('input[type="password"]')[0];
        const confirmPassIn = signupForm.querySelectorAll('input[type="password"]')[1];

        [nameIn, emailIn, passIn, confirmPassIn].forEach(i => i && clearInputError(i));

        if (!nameIn.value.trim()) {
          showInputError(nameIn, 'Name is required');
          isValid = false;
        }

        if (!emailIn.value.trim() || !validateEmail(emailIn.value.trim())) {
          showInputError(emailIn, 'Valid email is required');
          isValid = false;
        }

        if (passIn.value.length < 6) {
          showInputError(passIn, 'Password must be at least 6 characters');
          isValid = false;
        }

        if (passIn.value !== confirmPassIn.value) {
          showInputError(confirmPassIn, 'Passwords do not match');
          isValid = false;
        }

        if (!isValid) {
          e.preventDefault();
        }
      });
    }
  }
});
