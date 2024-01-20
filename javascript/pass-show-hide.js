const passwordField = document.querySelector(".form input[name='password']");
const confirmField = document.querySelector(".form input[name='confirm_password']");
const toggleIconPassword = document.querySelector(".form .field input[name='password'] + i");
const toggleIconConfirm = document.querySelector(".form .field input[name='confirm_password'] + i");

function togglePasswordVisibility(field, toggleIcon) {
  if (field.type === "password") {
    field.type = "text";
    toggleIcon.classList.add("active");
  } else {
    field.type = "password";
    toggleIcon.classList.remove("active");
  }
}

toggleIconPassword.onclick = () => {
  togglePasswordVisibility(passwordField, toggleIconPassword);
}

toggleIconConfirm.onclick = () => {
  togglePasswordVisibility(confirmField, toggleIconConfirm);
}
