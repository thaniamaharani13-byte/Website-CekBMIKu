const form = document.getElementById("loginForm");
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

// Toggle tampil/sembunyi password + ubah ikon
togglePassword.addEventListener("click", () => {
  const isPassword = password.getAttribute("type") === "password";
  password.setAttribute("type", isPassword ? "text" : "password");

  // Ubah ikon Font Awesome
  togglePassword.classList.toggle("fa-eye");
  togglePassword.classList.toggle("fa-eye-slash");
});

// Form login
form.addEventListener("submit", (e) => {
  e.preventDefault();

  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const pass = password.value.trim();

  if (!name || !email || !pass) {
    alert("Semua kolom harus diisi!");
    return;
  }

  // Simulasi login sukses
  alert(`Selamat datang, ${name}! Anda berhasil masuk ke CekBMiku.`);

  // Arahkan ke halaman utama setelah login
  window.location.href = "index.html";
});