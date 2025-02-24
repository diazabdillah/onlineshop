<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
{{-- <title>{{ $app_name }}</title> --}}
<title>Anekabarangsby</title>
  <link rel="shortcut icon" href="{{ asset('ashion') }}/img/logo.jpg" type="image/x-icon">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
rel="stylesheet">

<!-- Css Styles -->
<link rel="stylesheet" href="{{ asset('ashion') }}/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('ashion') }}/css/elegant-icons.css" type="text/css">
<link rel="stylesheet" href="{{ asset('ashion') }}/css/jquery-ui.min.css" type="text/css">
<link rel="stylesheet" href="{{ asset('ashion') }}/css/magnific-popup.css" type="text/css">
<link rel="stylesheet" href="{{ asset('ashion') }}/css/owl.carousel.min.css" type="text/css">
<link rel="stylesheet" href="{{ asset('ashion') }}/css/slicknav.min.css" type="text/css">
<link rel="stylesheet" href="{{ asset('ashion') }}/css/style.css" type="text/css">
<link rel="stylesheet" href="{{ asset('stisla') }}/css/style2.css">
<link rel="stylesheet" href="{{ asset('stisla') }}/css/components.css">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .review-text {
    font-size: 14px;
    color: #555;
}

.review-media img, .review-media video {
    border: 1px solid #ddd; /* Garis border untuk gambar dan video */
    padding: 5px;
    background-color: #f9f9f9;
    border-radius: 5px; /* Sudut melengkung */
}

.reviews-container {
    background-color: #fff; /* Warna latar card */
    border: 1px solid #ddd; /* Garis border card */
    border-radius: 5px; /* Sudut melengkung card */
}
  .form-control:focus {
    box-shadow: none;
    border-color: #BA68C8
}

.profile-button {
    background: rgb(99, 39, 120);
    box-shadow: none;
    border: none
}
.rating-css div {
    color: #ffe400;
    font-size: 30px;

    font-family: sans-serif;
    font-weight: 800;
    text-align: center;
    text-transform: uppercase;
    padding: 20px 0;
  }
  .rating-css input {
    display: none;
  }
  .rating-css input + label {
    font-size: 60px;
    text-shadow: 1px 1px 0 #8f8420;
    cursor: pointer;
  }
  .rating-css input:checked + label ~ label {
    color: #b4afaf;
  }
  .rating-css label:active {
    transform: scale(0.8);

    transition: 0.3s ease;
  }
  .checked{
    color: #ffe400;
  }
  .gray {
    color: gray; /* Atur warna sesuai kebutuhan */
}
.profile-button:hover {
    background: #682773
}

.profile-button:focus {
    background: #682773;
    box-shadow: none
}

.profile-button:active {
    background: #682773;
    box-shadow: none
}

.back:hover {
    color: #682773;
    cursor: pointer
}

.labels {
    font-size: 11px
}

.add-experience:hover {
    background: #BA68C8;
    color: #fff;
    cursor: pointer;
    border: solid 1px #BA68C8
}
@media (max-width: 768px) {
        .botman-widget {
            width: 90% !important; /* Mengatur lebar widget */
            height: 500px !important; /* Mengatur tinggi widget */
        }
    }
</style>