<?php
global $currentYear;

$Footer = <<<EOF
<footer id="footer">
        <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-col-1">
                    <p class="strong">Интернет магазин компании "Champion Group" <br> 2013-{$currentYear}</p>
                    <br>
                    <p class="strong">График работы Call-центра:</p>
                    <p>Пн-Пт с 8:00 до 20:00</p>
                    <p>Сб-Вс с 9:00 до 18:00</p>
                    <p class="strong">Наши контакты:</p>
                    <p>+38 (048) 736 26 49</p>
                </div>
            </div>
            <div class="col-md-2">
                <ul class="footer-links-ul">
                    <li class="footer-link-li"><a href="/delivery/">Доставка и оплата</a></li>
                    <li class="footer-link-li"><a href="/contacts/">Обратная связь</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-2">
                <ul class="footer-links-ul">
                    <li class="footer-link-li"><a href="/aboutus/">О нас</a></li>
                    <li class="footer-link-li"><a href="/news/">Новости</a></li>
                    <li class="footer-link-li"><a href="/faq/">FAQ</a></li>
                    <li class="footer-link-li"><a href="/contacts/">Контакты</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <p class="strong">Подпишитесь и получайте новости об акциях и выгодных предложениях</p>
                <div class="footer-subscribe">
                    <input type="text" placeholder="Введите e-mail" class="design-input" id="subscribe-email">
                    <button id="subscribe-btn" class="btn btn-info">Подписаться</button>
                </div>
                <div class="social-links">
                    <div class="social">
                        <a href="https://www.facebook.com/%D0%A0%D0%B5%D0%BA%D0%BB%D0%B0%D0%BC%D0%BD%D0%BE%D0%B5-%D0%B0%D0%B3%D0%B5%D0%BD%D1%82%D1%81%D1%82%D0%B2%D0%BE-%D0%A7%D0%B5%D0%BC%D0%BF%D0%B8%D0%BE%D0%BD-437677686285200/"><span class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></span></a>
                        <a href="http://www.google.com/"><span class="google"><i class="fa fa-google-plus" aria-hidden="true"></i></span></a>
                        <a href="https://www.instagram.com/groupchampion/"><span class="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></span></a>
                        <a href="https://vk.com/ra_chempion_group"><span class="vk"><i class="fa fa-vk" aria-hidden="true"></i></span></a>
                        <a href="https://twitter.com/ChempionGroup"><span class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                    </div>
                </div>
                <div class="pay-type-footer">
                    <img src="/Images/Home/visa.svg" alt="Мы принимаем Visa">
                    <img src="/Images/Home/mastercard.svg" alt="Мы принимаем Master Card">
                </div>
            </div>
            <div class="col-md-12 ta-c"><a href="http://www.std-carrot.com" class="logo-carrot" rel="nofollow" target="_blank">Сайт разработан в Carrot Studio<br><img src="/Images/Icons/carrotlogo.svg"></a></div>
        </div>
    </div>
</footer>
</div>
</body>
</html>
EOF;

BF::IncludeScripts([
    "jquery/jquery-3.1.0.min",
    "owl/owl.carousel",
    "bootstrap-3.3.7/js/bootstrap",
    "core/bootbox.min",
    "core/ui-slider",
    "core/core"
]);

print($script);

print($Footer);
