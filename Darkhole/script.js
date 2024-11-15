let elem = document.getElementById('searchInput');
let profilePic = document.getElementById('profile-pic');
let inputFile = document.getElementById('input-file');

inputFile.onchange = function(){
    profilePic.src = URL.createObjectURL(inputFile.files[0]);
}




function Search(event) {
if (event.keyCode === 13) {
  event.preventDefault();
    window.location = 'https://www.google.com/search?q=' + elem.value;
}
}
elem.addEventListener("keyup", Search); 



function gmail(){
window.location = 'https://mail.google.com/mail/u/0/#inbox';
}

function user(){
window.location = 'user.html';
}
