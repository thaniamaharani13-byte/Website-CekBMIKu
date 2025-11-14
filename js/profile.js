document.addEventListener("DOMContentLoaded", () => {

  const editBtn = document.getElementById("editBtn");
  const inputs = document.querySelectorAll("input[readonly]");
  const backBtn = document.querySelector(".back-btn");
  const logoutBtn = document.getElementById("logoutBtn");

  // Tombol Edit
  if (editBtn) {
    editBtn.addEventListener("click", () => {
      const isEditing = editBtn.textContent === "Edit";

      inputs.forEach(input => input.readOnly = !isEditing);
      editBtn.textContent = isEditing ? "Simpan" : "Edit";

      if (!isEditing) {
        alert("✅ Data berhasil disimpan!");
      }
    });
  }

 // Tombol Back
if (backBtn) {
    backBtn.addEventListener("click", () => {
        if (document.referrer && document.referrer !== window.location.href) {
            // Kembali ke halaman sebelumnya
            window.location.href = document.referrer;
        } else {
            // Jika tidak ada halaman sebelumnya (referrer kosong)
            window.location.href = "index.php";
        }
    });
}


  // Tombol Logout ke logout.php
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      window.location.href = "logout.php";
    });
  }
});
