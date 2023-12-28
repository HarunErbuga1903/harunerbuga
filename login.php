<?php
include("baglanti.php");

$username_err = "";
$parola_err = "";
$login_result = "";

if (isset($_POST["giris"])) {

    // KULLANICI ADI DOĞRULAMA
    if (empty($_POST["kullaniciadi"])) {
        $username_err = "Kullanıcı adı boş geçilemez!";
    } else {
        $username = $_POST["kullaniciadi"];
    }

    // PAROLA DOĞRULAMA İŞLEMİ
    if (empty($_POST["parola"])) {
        $parola_err = "Parola Boş Geçilemez";
    } else {
        $parola = $_POST["parola"];
    }

    // Diğer işlemler ve veritabanı sorgulama işlemi
    if (empty($username_err) && empty($parola_err)) {

        $secim = "SELECT * FROM kullanicilar WHERE kullanici_adi ='$username'";
        $calistir = mysqli_query($baglanti, $secim);
        $kayitsayisi = mysqli_num_rows($calistir);

        if ($kayitsayisi > 0) {
            $ilgilikayit = mysqli_fetch_assoc($calistir);
            $hashlisifre = $ilgilikayit["parola"];

            if (password_verify($parola, $hashlisifre)) {
                session_start();
                $_SESSION["kullanici_adi"] = $ilgilikayit["kullanici_adi"];
                $_SESSION["email"] = $ilgilikayit["email"];

                // Giriş başarılı olduğunda yönlendirme yap
                header("location: index.html");
                exit(); // Çıkış yapmayı unutmayın
            } else {
                $login_result = '<div class="alert alert-danger" role="alert">
                    Parola Yanlış
                </div>';
            }
        } else {
            $login_result = '<div class="alert alert-danger" role="alert">
                Kullanıcı Adı Yanlış
            </div>';
        }

        mysqli_close($baglanti);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Üye Giriş İşlemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous">
</head>
<body>
<div class="container p-5">
    <div class="card p-5">

        <?php
        // Giriş sonucunu göster
        echo $login_result;
        ?>

        <form action="login.php" method="POST">

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control <?php if (!empty($username_err)) { echo "is-invalid"; } ?>" id="exampleInputEmail1" name="kullaniciadi">
                <div class="invalid-feedback">
                    <?php echo $username_err; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Parola</label>
                <input type="password" class="form-control <?php if (!empty($parola_err)) { echo "is-invalid"; } ?>" id="exampleInputPassword1" name="parola">
                <div class="invalid-feedback">
                    <?php echo $parola_err; ?>
                </div>
            </div>

            <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>
        </form>

    <div class="mt-3">
    <p>Eğer kayıt olmadıysanız ve kayıt olmak istiyorsanız <a href="kayit.php">tıklayınız</a>.</p>
    </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>
</html>
