document.getElementById('loginForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form from refreshing the page

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Send the data to PHP
    fetch("login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ username: username, password: password })
    })
        .then(response => response.json()) // Get the response from PHP
        .then(data => {
            if (data.success) {
                // If login is successful, redirect to a new page
                window.location.href = "home.html"; // Redirect to home page
            } else {
                // If login fails, show an error message
                document.getElementById('errorMessage').textContent = data.message;
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
});
