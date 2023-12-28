document.addEventListener("DOMContentLoaded", function() {
    // Sayfa yüklendiğinde yapılacak işlemler buraya gelecek
});

function loginUser() {
    var inputUsername = document.getElementById('loginUsername').value;
    var inputPassword = document.getElementById('loginPassword').value;

    // Kayıtlı kullanıcı bilgilerini localStorage'dan alıp kontrol etme
    var storedUsername = localStorage.getItem('username');
    var storedPassword = localStorage.getItem('password');

    if (inputUsername === storedUsername && inputPassword === storedPassword) {
        // Giriş işlemi başarıyla tamamlandığında websitesi.html sayfasına yönlendirme
        window.location.href = "index.html";
    } else {
        alert("Kullanıcı adı veya şifre hatalı!");
    }
}


function goToYazilimPage() {
    window.location.href = "yazilim.html";
}

function goToDonanimPage() {
    window.location.href = "donanim.html";
}

function goToHakkimdaPage() {
    showSection('hakkimda');
}

function goToIletisimPage() {
    showSection('iletisim');
}

function redirectToKayitPage() {
    console.log("Icona tıklandı");
    window.location.href = "kayit.php";
}

function showSection(sectionId) {
    var sections = document.querySelectorAll('.section');
    for (var i = 0; i < sections.length; i++) {
        sections[i].classList.remove('fade-in');
        sections[i].style.display = 'none';
    }

    var sectionToShow = document.getElementById(sectionId);
    sectionToShow.style.display = 'inline-block';
    sectionToShow.classList.add('fade-in');
}