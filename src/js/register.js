const form = document.getElementById("register-form");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  alert("Registration successful!");

  // send user to login page
  window.location.href = "./login.html";
});
const form = document.getElementById("register-form");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const id = "STU-" + Math.floor(Math.random() * 100000);
  const name = document.getElementById("name").value;

  const user = {
    id: id,
    name: name,
    role: "student",
  };

  localStorage.setItem("currentUser", JSON.stringify(user));

  alert("Registration successful! Your ID: " + id);

  window.location.href = "./login.html";
});