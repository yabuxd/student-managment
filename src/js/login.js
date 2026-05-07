document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("login-form");

  if (!loginForm) return;

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const inputId = document.getElementById("idNumber").value;
    const storedUser = JSON.parse(localStorage.getItem("currentUser"));

    if (!storedUser) {
      alert("No user found. Please register first.");
      return;
    }

    if (inputId === storedUser.id) {
      alert("Login successful!");

      if (storedUser.role === "student") {
        window.location.href = "./student-dashboard.html";
      } else if (storedUser.role === "teacher") {
        window.location.href = "./teacher-panel.html";
      } else {
        window.location.href = "./parent-dashboard.html";
      }
    } else {
      alert("Invalid ID");
    }
  });
});
