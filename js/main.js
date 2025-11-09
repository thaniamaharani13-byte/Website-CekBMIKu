document.addEventListener("DOMContentLoaded", () => {

  // === NAVBAR AKTIF & SCROLL HALUS ===
  const navLinks = document.querySelectorAll(".nav-menu a");
  navLinks.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      navLinks.forEach(l => l.classList.remove("active"));
      this.classList.add("active");
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      }
    });
  });

  // === PROFIL NAVBAR ===
  const profileNav = document.querySelector(".nav-profile");
  if (profileNav) {
    profileNav.addEventListener("click", () => {
      window.location.href = "profile.html";
    });
  }

  // === Pilihan Gender ===
  const male = document.getElementById("male");
  const female = document.getElementById("female");
  const genders = [male, female];

  if (male && female) {
    genders.forEach(el => {
      el.addEventListener("click", () => {
        genders.forEach(g => g.classList.remove("active"));
        el.classList.add("active");
      });
    });
  }

  // === Form BMI ===
  const form = document.querySelector("#bmiForm");
  if (form) {
    const inputHeight = document.querySelector(".height");
    const inputWeight = document.querySelector(".weight");

    form.addEventListener("submit", e => {
      e.preventDefault();

      const height = parseFloat(inputHeight.value);
      const weight = parseFloat(inputWeight.value);

      if (!height || !weight || height <= 0 || weight <= 0) {
        alert("Masukkan tinggi (cm) dan berat (kg) yang valid.");
        return;
      }

      const height_m = height / 100;
      const bmi = Math.round((weight / (height_m * height_m)) * 10) / 10;

      const gender = male?.classList.contains("active")
        ? "male"
        : female?.classList.contains("active")
        ? "female"
        : "unknown";

      let idealWeight;
      if (gender === "male") {
        idealWeight = (height - 100) - ((height - 100) * 0.10);
      } else if (gender === "female") {
        idealWeight = (height - 100) - ((height - 100) * 0.15);
      } else {
        idealWeight = null;
      }

      idealWeight = idealWeight ? Math.round(idealWeight * 10) / 10 : null;

      const data = { height, weight, bmi, gender, idealWeight };
      sessionStorage.setItem("bmiResult", JSON.stringify(data));

      // === Simpan ke server (PHP) ===
      fetch("/CekBMIku/php/simpan_bmi.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          height: height,
          weight: weight,
          bmi: bmi,
          gender: gender,
          ideal_weight: idealWeight,
          note: "Data BMI disimpan otomatis"
        }),
      })
      .then(res => res.json())
      .then(data => console.log("Status simpan:", data))
      .catch(err => console.error("Error:", err));

      // === Pindah ke hasil.html ===
      window.location.href = "hasil.html";
    });
  }

  // === FAQ Section ===
  const boxes = document.querySelectorAll(".box");
  boxes.forEach(box => {
    const head = box.querySelector(".box_head");
    const content = box.querySelector(".box_text");

    if (head && content) {
      head.addEventListener("click", () => {
        const isActive = box.classList.contains("active");

        if (isActive) {
          content.style.maxHeight = null;
          box.classList.remove("active");
        } else {
          boxes.forEach(b => {
            b.classList.remove("active");
            b.querySelector(".box_text").style.maxHeight = null;
          });
          box.classList.add("active");
          content.style.maxHeight = (content.scrollHeight + 50) + "px";
        }
      });
    }
  });

  // === FORM MASUKAN ===
  const feedbackForm = document.querySelector(".masukan-form");
  if (feedbackForm) {
    feedbackForm.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("Terima kasih atas masukan Anda! 💬");
      feedbackForm.reset();
    });
  }
});