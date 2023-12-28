<?php
include("baglanti.php");

$email_err = "";
$username_err = "";
$parola_err = "";
$parolatkr_err = "";
$registration_result = "";

if (isset($_POST["kaydet"])) {

    // KULLANICI ADI DOĞRULAMA
    if (empty($_POST["kullaniciadi"])) {
        $username_err = "Kullanıcı adı boş geçilemez!";
    } elseif (mb_strlen($_POST["kullaniciadi"]) < 6) {
        $username_err = "Kullanıcı adı en az 6 karakterden oluşmalıdır";
    } elseif (!preg_match('/^[a-z\d_]{5,20}$/i', $_POST["kullaniciadi"])) {
        $username_err = "Kullanıcı adı büyük küçük harf ve rakamdan oluşmalıdır";
    } else {
        $username = $_POST["kullaniciadi"];
    }

    // EMAIL DOĞRULAMA
    if (empty($_POST["email"])) {
        $email_err = "Email Alanı Boş Geçilemez.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Geçersiz Email Formatı";
    } else {
        $email = $_POST["email"];
    }

    // PAROLA DOĞRULAMA İŞLEMİ
    if (empty($_POST["parola"])) {
        $parola_err = "Parola Boş Geçilemez";
    } else {
        $plain_password = $_POST["parola"];
        $parola = password_hash($plain_password, PASSWORD_DEFAULT);
    }

    // PAROLA TEKRAR DOĞRULAMASI
    if (empty($_POST["parolatkr"])) {
        $parolatkr_err = "Parola Tekrar Kısmı Boş Geçilemez";
    } elseif ($_POST["parolatkr"] != $plain_password) {
        $parolatkr_err = "Parolalar eşleşmiyor";
    }

    // Kullanıcı adının veritabanında önceden var olup olmadığını kontrol et
    $kontrol = "SELECT * FROM kullanicilar WHERE kullanici_adi = '$username'";
    $calistir_kontrol = mysqli_query($baglanti, $kontrol);

    if (mysqli_num_rows($calistir_kontrol) > 0) {
        $username_err = "Bu kullanıcı adı zaten kullanılmaktadır.";
    }

    // Diğer işlemler ve veritabanı ekleme işlemi
    if (empty($username_err) && empty($email_err) && empty($parola_err) && empty($parolatkr_err)) {

        $ekle = "INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES ('$username','$email','$parola')";
        $calistirekle = mysqli_query($baglanti, $ekle);

        if ($calistirekle) {
            $registration_result = '<div class="alert alert-success" role="alert">
            Kayıt Başarılı bir şekilde eklendi
          </div>';
        } else {
            $registration_result = '<div class="alert alert-danger" role="alert">
            Kayıt Eklenirken Bir Problem Oluştu: ' . mysqli_error($baglanti) . '
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
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous">
</head>
<body>
<div class="container p-5">
    <div class="card p-5">

        <?php
        // Kayıt sonucunu göster
        echo $registration_result;
        ?>

        <form action="kayit.php" method="POST">

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control <?php if (!empty($username_err)) { echo "is-invalid"; } ?>" id="exampleInputEmail1" name="kullaniciadi">
                <div class="invalid-feedback">
                    <?php echo $username_err; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">E-Posta Adresi</label>
                <input type="text" class="form-control <?php if (!empty($email_err)) { echo "is-invalid"; } ?>" id="exampleInputEmail1" name="email">
                <div class="invalid-feedback">
                    <?php echo $email_err; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Parola</label>
                <input type="password" class="form-control <?php if (!empty($parola_err)) { echo "is-invalid"; } ?>" id="exampleInputPassword1" name="parola">
                <div class="invalid-feedback">
                    <?php echo $parola_err; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Parola Tekrar</label>
                <input type="password" class="form-control <?php if (!empty($parolatkr_err)) { echo "is-invalid"; } ?>" id="exampleInputPassword1" name="parolatkr">
                <div class="invalid-feedback">
                    <?php echo $parolatkr_err; ?>
                </div>
            </div>

            <button type="submit" name="kaydet" class="btn btn-primary">Kaydet</button>
        </form>

        <div class="mt-3">
        <p>Eğer daha önceden kayıt olduysanız veya giriş yapmak istiyorsanız <a href="login.php">tıklayınız</a>.</p>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>
</html>
