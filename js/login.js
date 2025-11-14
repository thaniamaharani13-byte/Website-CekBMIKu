const form = document.getElementById("loginForm");
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

if (togglePassword && password) {
  togglePassword.addEventListener("click", () => {
    const isPassword = password.type === "password";
    password.type = isPassword ? "text" : "password";

    // Ubah ikon Font Awesome (mata ↔ mata tertutup)
    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
  });
}

if (form) {
  form.addEventListener("submit", (e) => {
    const email = document.getElementById("email").value.trim();
    const pass = password.value.trim();

    // Validasi input sebelum dikirim
    if (!email || !pass) {
      e.preventDefault(); // cegah submit jika kosong
      alert("Email dan Password wajib diisi!");
      return;
    }

    // Jika semua terisi, biarkan form dikirim ke PHP
  });
}
