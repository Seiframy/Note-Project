document.getElementById("registration_").addEventListener("submit", async function (e) {
    e.preventDefault();

    const d_data = {
        first_name: document.getElementById("first_name").value.trim(),
        last_name: document.getElementById("last_name").value.trim(),
        username: document.getElementById("username").value.trim(),
        password: document.getElementById("password").value,
        confirmPassword: document.getElementById("confirm_password").value,
        email: document.getElementById("email").value.trim(),
        phone: document.getElementById("phone").value.trim(),
        location: document.getElementById("location").value.trim(),
    };

    // Check password match
    if (d_data.password !== d_data.confirmPassword) {
        document.getElementById("errorMessage").textContent = "❌ Passwords do not match!";
        return;
    }


    console.log(d_data); // Add this to check what you're sending


    const res = await fetch("http://localhost/shahr4/MyP/V3/create.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(d_data) // This should work fine
    });




    let data;
    try {
        data = await res.json();
    } catch (err) {
        document.getElementById("errorMessage").textContent = "❌ Invalid server response";
        return;
    }

});
