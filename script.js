let themeIcon = document.querySelector(".theme__icon");
let passwordControls = document.querySelectorAll(".password__controls");

passwordControls.forEach((field) => {
  let passwordField = field.previousElementSibling;
  field.addEventListener("click", () => {
    if (passwordField.type === "password") {
      passwordField.type = "text";
      field.src = "/pms/assets/eye.svg";
    } else {
      passwordField.type = "password";
      field.src = "/pms/assets/eyeshut.svg";
    }
  });
});

themeIcon.addEventListener("click", () => {
  document.body.classList.toggle("dark");

  let theme = "light";

  if (document.body.classList.contains("dark")) {
    themeIcon.src = "/pms/assets/sun.svg";
    theme = "dark";
  } else {
    themeIcon.src = "/pms/assets/moon.svg";
  }

  document.cookie = "theme="+theme+'; path=/;';
});
