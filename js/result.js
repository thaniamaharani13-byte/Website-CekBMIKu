// result.js //
document.addEventListener('DOMContentLoaded', () => {
  const raw = sessionStorage.getItem('bmiResult');
  const defaultData = { height: 0, weight: 0, bmi: 0, gender: 'unknown' };
  const data = raw ? JSON.parse(raw) : defaultData;

  // Hitung ulang ideal weight berdasarkan gender //
  let idealWeight;
  const base = data.height - 100;

  if (data.gender === 'male') {
    idealWeight = base - (base * 0.10); // Pria
  } else if (data.gender === 'female') {
    idealWeight = base - (base * 0.15); // Wanita
  } else {
    idealWeight = base; // jika tidak memilih gender
  }

  idealWeight = idealWeight > 0 ? idealWeight.toFixed(1) : '-';

  // Elemen di hasil.html //
  const infoContainer = document.querySelector('.bmi-info');
  const note = document.querySelector('.note');

  if (infoContainer) {
    infoContainer.innerHTML = `
      <p><strong>Tinggi (cm):</strong> ${data.height || '-'}</p>
      <p><strong>Berat (kg):</strong> ${data.weight || '-'}</p>
      <p><strong>BMI kamu:</strong> <span class="bmi">${data.bmi || '-'}</span></p>
      <p><strong>Berat ideal (${data.gender === 'male' ? 'Pria' : data.gender === 'female' ? 'Wanita' : 'Umum'}):</strong> ${idealWeight}</p>
      <div class="bmi-bar">
        <span class="bar"></span>
      </div>
    `;
  }

  // Tentukan kategori BMI //
  const bmiVal = Number(data.bmi);
  let category = 'Tidak diketahui';
  let titleColor = '#D8518C';
  let noteText = 'Pertahankan gaya hidup sehat.';

  if (!bmiVal || isNaN(bmiVal)) {
    category = 'Data tidak lengkap';
    noteText = 'Silakan isi tinggi & berat dengan benar.';
  } else if (bmiVal < 18.5) {
    category = 'Kurus';
    titleColor = '#D8518C';
    noteText = 'Mungkin perlu menambah asupan gizi seimbang.';
  } else if (bmiVal >= 18.5 && bmiVal < 25) {
    category = 'Normal';
    titleColor = '#2E86AB';
    noteText = 'Bagus! Pertahankan pola makan dan aktivitas sehat.';
  } else if (bmiVal >= 25 && bmiVal < 30) {
    category = 'Kelebihan Berat Badan';
    titleColor = '#F6C85F';
    noteText = 'Perhatikan porsi makan dan tambah olahraga rutin.';
  } else {
    category = 'Obesitas';
    titleColor = '#D8518C';
    noteText = 'Pertimbangkan konsultasi profesional.';
  }

  // Update tampilan hasil //
  const headline = document.querySelector('.left-section h1.obesitas');
  const subtitle = document.querySelector('.left-section .sub');
  const barEl = document.querySelector('.bmi-bar .bar');

  if (headline) {
    headline.textContent = category;
    headline.style.color = titleColor;
  }

  if (subtitle) {
    if (category === 'Kurus') subtitle.textContent = 'Berat kurang dari normal';
    else if (category === 'Normal') subtitle.textContent = 'Berat badan dalam kisaran sehat';
    else if (category === 'Kelebihan Berat Badan') subtitle.textContent = 'Berat sedikit di atas normal';
    else if (category === 'Obesitas') subtitle.textContent = 'Berat terlalu berlebih';
  }

  if (note) note.textContent = noteText;

  // Posisi bar //
  function bmiToPercent(bmi) {
    const min = 12, max = 40;
    if (!bmi || isNaN(bmi)) return 0;
    let p = ((bmi - min) / (max - min)) * 100;
    return Math.min(Math.max(p, 0), 100);
  }

  const percent = bmiToPercent(bmiVal);
  if (barEl) barEl.style.left = `calc(${percent}% - 6px)`;
});
