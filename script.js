let themeIcon = document.querySelector(".theme__icon");
let passwordControls = document.querySelectorAll(".password__controls");
let editBtn = document.querySelectorAll(".edit__btn");
let editModal = document.querySelector(".edit__modal");
let closeButton = document.getElementById("close__btn");
let closeCreateLotBtn = document.getElementById("closeCreateModal__btn");
let createBtn = document.querySelector('.create__btn');
let createModal = document.querySelector('.createLot__modal');
const tabs = document.querySelectorAll('[data-tab-target]');
const tabContents = document.querySelectorAll('[data-tab-content]');

//Sidebar tabs logic
tabs.forEach(tab => {
  tab.addEventListener('click',() => {
    const target = document.querySelector(tab.dataset.tabTarget);

    tabContents.forEach(tabContent => {
      tabContent.classList.remove('active');
    })

    tabs.forEach(tab => {
      tab.classList.remove('active');
    })

    tab.classList.add('active');
    target.classList.add('active');
  })
})

createBtn.addEventListener('click',() => {
  createModal.showModal();
})

closeCreateLotBtn.addEventListener('click',() => {
  createModal.close();
});

//Password hidden and unhidden logic
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

//Theme logic
themeIcon.addEventListener("click", () => {
  document.body.classList.toggle("dark");

  let theme = "light";

  if (document.body.classList.contains("dark")) {
    themeIcon.src = "/pms/assets/sun.svg";
    theme = "dark";
  } else {
    themeIcon.src = "/pms/assets/moon.svg";
  }

  document.cookie = "theme=" + theme + "; path=/;";
});


//Edit user logic
// editBtn.forEach((btn) => {
//   btn.addEventListener("click", () => {
//     editModal.showModal();
//   });
// })

// closeButton.addEventListener("click", () => {
//   editModal.close();
// });

