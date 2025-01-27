document.addEventListener('DOMContentLoaded', function () {
    const staffLoginForm = document.getElementById('staff-login');
    const studentLoginForm = document.getElementById('student-login');

    // Handle staff login form submit
    staffLoginForm?.addEventListener('submit', function (event) {
        // No AJAX logic here, the form will submit as usual
    });

    // Handle student login form submit
    studentLoginForm?.addEventListener('submit', function (event) {
        // No AJAX logic here, the form will submit as usual
    });
});

fetch('auth_api.php', {
    method: 'GET',
    headers: {
        'API-Key': localStorage.getItem('api_key') // Or any secure storage
    }
}).then(response => response.json())
  .then(data => {
      if (data.status === 'success') {
          console.log('User authenticated:', data.user_type);
      } else {
          console.error(data.message);
          window.location.href = 'unauthorized.html';
      }
  });
