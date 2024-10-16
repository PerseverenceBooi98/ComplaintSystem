// Get the forms
const provincialForm = document.getElementById('provincialForm');
const districtForm = document.getElementById('districtForm');

// Get the buttons
const provincialButton = document.querySelector('.buttons a:first-child');
const districtButton = document.querySelector('.buttons a:last-child');

// Add event listeners to the buttons
provincialButton.addEventListener('click', showProvincialForm);
districtButton.addEventListener('click', showDistrictForm);

// Function to show the provincial form
function showProvincialForm() {
  provincialForm.classList.remove('hide');
  districtForm.classList.add('hide');
}

// Function to show the district form
function showDistrictForm() {
  provincialForm.classList.add('hide');
  districtForm.classList.remove('hide');
}

// Populate Municipalities based on selected District
function populateMunicipalities() {
  const districtSelect = document.getElementById('district');
  const municipalitySelect = document.getElementById('municipality');
  const selectedDistrict = districtSelect.value;
  const municipalities = {
    "Ngaka Modiri Molema": [
      "Mahikeng Local Municipality",
      "Ramotshere Moiloa Local Municipality",
      "Ditsobotla Local Municipality",
      "Tswaing Local Municipality",
      "Ratlou Local Municipality"
    ],
    "Bojanala Platinum": [
      "Rustenburg",
      "Madibeng",
      "Moses Kotane",
      "Moretele",
      "Kgetlengrivier"
    ],
    "Dr Kenneth Kaunda": [
      "JB Marks",
      "City of Matlosana",
      "Maquassi Hills"
    ],
    "Dr Ruth Segomotsi Mompati": [
      "Greater Taung",
      "Kagisano-Molopo",
      "Naledi",
      "Mamusa",
      "Lekwa-Teemane"
    ]
  };

  // Clear current options
  municipalitySelect.innerHTML = '';

  // Populate new options
  municipalities[selectedDistrict].forEach(function(municipality) {
    const option = document.createElement('option');
    option.value = municipality;
    option.textContent = municipality;
    municipalitySelect.appendChild(option);
  });
}

// Add event listener to district select
document.getElementById('district').addEventListener('change', populateMunicipalities);
