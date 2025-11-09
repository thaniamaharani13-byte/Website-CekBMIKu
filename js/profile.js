document.addEventListener("DOMContentLoaded", () => {

  const editBtn = document.getElementById("editBtn");
  const inputs = document.querySelectorAll("input");
  const backBtn = document.querySelector(".back-btn");
  const logoutBtn = document.getElementById("logoutBtn");

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

  const nameEl = document.getElementById("userName");
  const emailEl = document.getElementById("userEmail");
  const umurEl = document.getElementById("umur");
  const genderEl = document.getElementById("gender");

  if (nameEl) nameEl.textContent = "Nathania Maharani";
  if (emailEl) emailEl.textContent = "nathania@example.com";
  if (umurEl) umurEl.value = "21";
  if (genderEl) genderEl.value = "Perempuan";

  if (backBtn) {
    const params = new URLSearchParams(window.location.search);
    const fromPage = params.get("from");

    let targetPage = "index.html";
if (fromPage === "hasil") {
  targetPage = "hasil.html";
}

    backBtn.addEventListener("click", () => {
      window.location.href = targetPage; 
    });
  }

  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      window.location.href = "login.html";
    });
  }
});
