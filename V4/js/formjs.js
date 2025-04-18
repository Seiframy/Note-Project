document.getElementById("loginForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();

    try {
        const res = await fetch("../php/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, password }),
        });

        if (!res.ok) {
            throw new Error(`Server error: ${res.status}`);
        }

        const data = await res.json();

        if (data.success) {
            window.location.href = "../index.html";
        } else {
            document.getElementById("errorMessage").textContent = data.message;
            console.warn("Login failed:", data.message);
        }
    } catch (err) {
        console.error("Fetch/login error:", err);
        document.getElementById("errorMessage").textContent = "An error occurred while logging in.";
    }
});
