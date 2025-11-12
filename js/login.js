// Ambil elemen form dan elemen terkait
const form = document.getElementById("loginForm");
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

// =========================
// 🔒 TOGGLE TAMPIL PASSWORD
// =========================
if (togglePassword && password) {
  togglePassword.addEventListener("click", () => {
    const isPassword = password.getAttribute("type") === "password";
    password.setAttribute("type", isPassword ? "text" : "password");

    // Ubah ikon Font Awesome (mata ↔ mata tertutup)
    togglePassword.classList.toggle("fa-eye");
    togglePassword.classList.toggle("fa-eye-slash");
  });
}

// =========================
// ✅ VALIDASI FORM SEBELUM SUBMIT
// =========================
if (form) {
  form.addEventListener("submit", (e) => {
    const email = document.getElementById("email").value.trim();
    const pass = password.value.trim();

    // Validasi input sebelum dikirim
    if (!email || !pass) {
      e.preventDefault(); // cegah kirim kalau kosong
      alert("Email dan Password wajib diisi!");
      return;
    }

    // Jika semua terisi, biarkan form dikirim ke PHP (tidak ada e.preventDefault())
    // PHP akan menangani proses login dan redirect ke index.php
  });
}
