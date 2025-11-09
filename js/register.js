const form = document.getElementById("registerForm");

//  Toggle tampil/sembunyi password
document.querySelectorAll(".toggle-pass").forEach(icon => {
  icon.addEventListener("click", () => {
    const targetInput = document.getElementById(icon.dataset.target);
    const isPassword = targetInput.getAttribute("type") === "password";
    targetInput.setAttribute("type", isPassword ? "text" : "password");
    icon.classList.toggle("fa-eye");
    icon.classList.toggle("fa-eye-slash");
  });
});

// Validasi dan pesan sukses
form.addEventListener("submit", e => {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const pass = document.getElementById("password").value.trim();
  const confirm = document.getElementById("confirm").value.trim();
  const age = document.getElementById("age").value.trim();
  const gender = document.getElementById("gender").value;

  if (!name || !email || !pass || !confirm || !age || !gender) {
    alert("Semua kolom harus diisi!");
    return;
  }

  if (pass !== confirm) {
    alert("Password dan konfirmasi tidak cocok!");
    return;
  }

  alert(`Selamat datang, ${name}! Akun kamu berhasil dibuat.`);
  window.location.href = "login.html";
});
