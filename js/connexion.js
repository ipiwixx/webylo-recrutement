window.onload = (event) => {
  const eye = document.querySelector(".bx-show");
  const eyeoff = document.querySelector(".bx-low-vision");
  const passwordField = document.querySelector('input[type="password"]');

  eye.addEventListener("click", () => {
    eye.style.display = "none";
    eyeoff.style.display = "block";
    passwordField.type = "text";
  });

  eyeoff.addEventListener("click", () => {
    eyeoff.style.display = "none";
    eye.style.display = "block";
    passwordField.type = "password";
  });
};
