document.getElementById("registration_").addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form values
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    // Check if passwords match
    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return; // Prevent form submission
    }

    const formData = {
        username: document.getElementById("username").value,
        password: password,
        confirm_password: confirmPassword,
    };

    // Send to PHP backend
    fetch("../php/process.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
        .then(res => res.json())
        .then(data => {
            console.log("Response from PHP:", data);
            if (data.success) {
                alert("Data sent successfully! " + data.message);
                window.location.href = "../index.html"; // Optional redirect
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
        });
});
