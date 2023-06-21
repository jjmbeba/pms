const copyright = document.getElementById("copyright");

const year = new Date().getFullYear();
console.log(year)

copyright.innerText =  `© ${year} Parkit. All rights reserved.`