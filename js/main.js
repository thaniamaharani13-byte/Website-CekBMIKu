document.addEventListener("DOMContentLoaded", () => {

  // ==================== NAVBAR ACTIVE & SCROLL SMOOTH ====================
  const navLinks = document.querySelectorAll(".nav-menu a");

  navLinks.forEach(link => {
      link.addEventListener("click", function(e) {
          // Scroll smooth ke section
          if (this.hash !== "") {
              e.preventDefault();
              const target = document.querySelector(this.hash);
              target.scrollIntoView({ behavior: "smooth" });
          }

          // Active state
          navLinks.forEach(nav => nav.classList.remove("active"));
          this.classList.add("active");
      });
  });


  // ==================== GENDER BUTTON ====================
  const male = document.getElementById("male");
  const female = document.getElementById("female");
  const genderInput = document.getElementById("genderInput");

  if (male && female && genderInput) {
      male.addEventListener("click", () => {
          male.classList.add("active");
          female.classList.remove("active");
          genderInput.value = "male";
      });

      female.addEventListener("click", () => {
          female.classList.add("active");
          male.classList.remove("active");
          genderInput.value = "female";
      });
  }


  // ==================== BMI FORM ====================
  const form = document.querySelector("#bmiForm");

  // Input tinggi & berat (fix error)
  const inputHeight = document.querySelector("input[name='height']");
  const inputWeight = document.querySelector("input[name='weight']");

  if (form) {
      form.addEventListener("submit", e => {
          e.preventDefault();

          const height = parseFloat(inputHeight.value);
          const weight = parseFloat(inputWeight.value);
          const gender = genderInput ? genderInput.value : "";

          if (!height || !weight || height <= 0 || weight <= 0) {
              alert("Masukkan tinggi (cm) dan berat (kg) yang valid.");
              return;
          }

          const height_m = height / 100;
          const bmi = Math.round((weight / (height_m * height_m)) * 10) / 10;

          let ideal;

          if (gender === "male") {
              ideal = (height - 100) * 0.90;
          } else if (gender === "female") {
              ideal = (height - 100) * 0.85;
          } else {
              ideal = height - 100;
          }

          ideal = Math.round(ideal * 10) / 10;

          // Submit ke PHP
          form.submit();
      });
  }


  // ==================== FAQ TOGGLE ====================
  const faqBoxes = document.querySelectorAll(".box");

  faqBoxes.forEach(box => {
      const head = box.querySelector(".box_head");

      head.addEventListener("click", () => {
          box.classList.toggle("active");
      });
  });

});
