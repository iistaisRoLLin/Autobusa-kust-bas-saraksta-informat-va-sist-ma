function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    document.getElementById('hours').innerText = hours;
    document.getElementById('minutes').innerText = minutes;
    document.getElementById('seconds').innerText = seconds;
}

// Update time every second
setInterval(updateTime, 1000);

// Initial update
updateTime();
document.getElementById('loginButton').addEventListener('click', function () {
    document.getElementById('loginPanel').style.display = 'block';
  });
  
  document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault();
  
    // Handle form submission here
    // ...
  
    // Close the login panel
    document.getElementById('loginPanel').style.display = 'none';
  });

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



// LOGINS


