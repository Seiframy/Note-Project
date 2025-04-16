document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const firstname = document.getElementById("firstname").value;
    const lastname = document.getElementById("lastname").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const confirm = document.getElementById("confirm").value;

    if (password !== confirm) {
        document.getElementById("errorMessage").textContent = "Passwords do not match!";
        return;
    }

    const res = await fetch("register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ firstname, lastname, username, password }),
    });

    const data = await res.json();

    if (data.success) {
        window.location.href = "form.html";
    } else {
        document.getElementById("errorMessage").textContent = data.message;
    }
});