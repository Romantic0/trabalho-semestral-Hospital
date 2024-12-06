document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Mock login validation
    if (username === "admin" && password === "12345") {
        window.location.href = "dashboard.html";
    } else {
        const errorMessage = document.getElementById("errorMessage");
        errorMessage.textContent = "Usuário ou senha inválidos!";
        errorMessage.style.display = "block";
    }
});