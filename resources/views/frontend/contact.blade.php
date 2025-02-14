@extends('layouts.frontend.app')
@section('content')
    <section class="ftco-section">
        <div class="container">
            <!-- <div class="row justify-content-center">
        <div class="col-md-6 text-center mb-5">
         <h2 class="heading-section">Contact</h2>
        </div>
       </div> -->
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="wrapper">
                        <div class="row no-gutters mb-5">
                            <div class="col-md-7">
                                <div class="contact-wrap w-100 p-md-5 p-4">
                                    <h3 class="mb-4">Contact Us</h3>
                                    <div id="form-message-warning" class="mb-4"></div>
                                    <div id="form-message-success" class="mb-4">
                                      <p>Selamat Datang di toko kami🙏💓. <br>
										🏠Disini anekabarangsby,Ada yang bisa kami bantu?<br>
										🏠Ada yang ditanyakan terkait produk kami silahkan Whatsapp / Email kami.<br>
										🚛Pengiriman JNE, POS Indonesia, dan Tiki cepat kirim.<br>
										🗣Pelayanan jam 8.00-jam 21.00.<br>
										📦Pengiriman Barang Senin-Minggu di jam 16:30.<br>
										🎟Dapetin kode voucher diskon.<br>
										🎁Produk kosong di hubungi melalui Whatsapp.<br>
										🍦Barang wajib pakai packing luar jawa timur.<br>
										🛍Selamat Berbelanja,<br>
										Terima Kasih Pelanggan🙋</p>
                                    </div>
                                    <form method="POST" action="#" id="contactForm" name="contactForm" class="contactForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="label" for="name">Full Name</label>
                                                    <input type="text" class="form-control" name="name" id="name"
                                                        placeholder="Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="label" for="email">Email Address</label>
                                                    <input type="email" class="form-control" name="email" id="email"
                                                        placeholder="Email">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="label" for="subject">Subject</label>
                                                    <input type="text" class="form-control" name="subject" id="subject"
                                                        placeholder="Subject">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="label" for="#">Message</label>
                                                    <textarea name="message" class="form-control" id="message" cols="30" rows="4" placeholder="Message"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="submit" value="Send Message" class="btn btn-primary">
                                                    <div class="submitting"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-5 d-flex align-items-stretch" style="margin-top:150px;">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.34847259330508!2d112.8139090859351!3d-7.289035619941893!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f09c3191c0ff%3A0x48a5b7a4eaf53bc9!2sJl.%20Sukolilo%20Damai%20II%20No.6%2C%20Keputih%2C%20Kec.%20Sukolilo%2C%20Surabaya%2C%20Jawa%20Timur%2060111!5e0!3m2!1sid!2sid!4v1739290634294!5m2!1sid!2sid"
                                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="dbox w-100 text-center">
                                    <div class="icon d-flex align-items-center justify-content-center rounded-circle"
                                        style="background-color: blue; width: 50px; height: 50px; margin: 0 auto;">
                                        <span class="fa fa-map-marker" style="color: white;"></span>
                                    </div>
                                    <div class="text">
                                        <p><span>Address:</span> Jl. Sukolilo Damai II No.6, Keputih, Kec. Sukolilo,
                                            Surabaya, Jawa Timur 60111</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="dbox w-100 text-center">
                                    <div class="icon d-flex align-items-center justify-content-center rounded-circle"
                                        style="background-color: blue; width: 50px; height: 50px; margin: 0 auto;">
                                        <span class="fa fa-phone" style="color: white;"></span>
                                    </div>
                                    <div class="text">
                                        <p><span>Phone:</span> <a href="https://wa.me/6281331913558">+6281331913558</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="dbox w-100 text-center">
                                    <div class="icon d-flex align-items-center justify-content-center rounded-circle"
                                        style="background-color: blue; width: 50px; height: 50px; margin: 0 auto;">
                                        <span class="fa fa-paper-plane" style="color: white;"></span>
                                    </div>
                                    <div class="text">
                                        <p><span>Email:</span> <a
                                                href="mailto:zaidabdillah18@gmail.com">zaidabdillah18@gmail.com</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="dbox w-100 text-center">
                                    <div class="icon d-flex align-items-center justify-content-center rounded-circle"
                                        style="background-color: blue; width: 50px; height: 50px; margin: 0 auto;">
                                        <span class="fa fa-globe" style="color: white;"></span>
                                    </div>
                                    <div class="text">
                                        <p><span>Website: </span> <a
                                                href="https://anekabarangsby.my.id/">anekabrangsby.my.id</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>

						<div class="row">
							<h3 class="mb-4">Online Shop:</h3>
                       

                            <div class="col-md-3">
                                <div class="dbox w-100 text-center mb-4"> <!-- Menambahkan margin bawah untuk jarak -->
                                    <div class="icon d-flex align-items-center justify-content-center rounded-circle"
                                        style="background-color: blue; width: 50px; height: 50px; margin: 0 auto;">
										<span class="fa fa-shopping-cart" style="color: white;"></span> <!-- Changed from fa-shopee to fa-shopping-cart -->
                                    </div>
                                    <div class="text">
                                        <p><span>Shopee: </span> <a
                                                href="https://shopee.co.id/anekabarangsby/">anekabrangsby</a></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Tambahkan lebih banyak kolom untuk platform lain jika diperlukan -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
@endsection
