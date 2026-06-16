document.getElementById('loginBtn').addEventListener('click', function () {
    alert('Login logic here...');
    this.style.display = 'none';
    document.getElementById('logoutBtn').style.display = 'inline-block';
  });
  
  document.getElementById('logoutBtn').addEventListener('click', function () {
    alert('Logout logic here...');
    this.style.display = 'none';
    document.getElementById('loginBtn').style.display = 'inline-block';
  });