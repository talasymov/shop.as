<?php
$shares = BF::GenerateList(DataBase::GetShares(), '<div class="item"><a href="?"><img src="?" /></a></div>', ["shares_url", "shares_img"]);
$news = BF::GenerateList(DataBase::GetNews(), '<div class="col-md-3 one-news-home"><div class="one-news-home-div"><a href="/news/?"><img src="/Images/Products/?" /><strong>?</strong></a><span>?</span></div></div>', ["news_id", "news_img", "news_title", "news_description"]);

$popular = ShopFn::DrawProduct(DataBase::GetPopularProducts(), 3);
$sales = ShopFn::DrawProduct(DataBase::GetSalesProducts(), 3);
$last = ShopFn::DrawProduct(DataBase::GetLastProducts(), 3);
$viewed = ShopFn::DrawProduct(DataBase::GetViewedProducts(), 3);

$menu = IncludesFn::GenerateCategoryMenu();

//AuxiliaryFn::StylePrint(ShopFn::GetIdFromViewed());

IncludesFn::printHeader("Home");

$bodyText = <<<EOF
<div class="bg-menu-black"></div>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="menu-top">
                <div class="menu-home">
                    <strong><i class="fa fa-cog" aria-hidden="true"></i> Каталог товаров</strong>
                    {$menu}
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div id="main-slider" class="class="owl-carousel owl-theme"">{$shares}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="home-header-list">Популярные товары</h3>
        </div>
    </div>
    {$popular}
    <div class="row perspective">
        <div class="col-md-12">
            <h3 class="home-header-list">Скидки</h3>
        </div>
    </div>
    {$sales}
    <div class="row">
        <div class="col-md-12">
            <h3 class="home-header-list">Последние товары</h3>
        </div>
    </div>
    {$last}
    <div class="row">
        <div class="col-md-12">
            <h3 class="home-header-list">Просмотренные товары</h3>
        </div>
    </div>
    {$viewed}
    <div class="row">
        <div class="col-md-12">
            <h3 class="home-header-list">Новости, советы, статьи</h3>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="news-request home-c">
                            <strong>Узнавай о новых акциях!</strong>
                            <input placeholder="Введите e-mail">
                            <button>Подписаться</button>
                            <div class="social">
                                <a href="https://www.facebook.com/%D0%A0%D0%B5%D0%BA%D0%BB%D0%B0%D0%BC%D0%BD%D0%BE%D0%B5-%D0%B0%D0%B3%D0%B5%D0%BD%D1%82%D1%81%D1%82%D0%B2%D0%BE-%D0%A7%D0%B5%D0%BC%D0%BF%D0%B8%D0%BE%D0%BD-437677686285200/"><span class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></span></a>
                                <a href="http://www.google.com/"><span class="google"><i class="fa fa-google-plus" aria-hidden="true"></i></span></a>
                                <a href="https://www.instagram.com/groupchampion/"><span class="instagram"><i class="fa fa-instagram" aria-hidden="true"></i></span></a>
                                <a href="https://vk.com/ra_chempion_group"><span class="vk"><i class="fa fa-vk" aria-hidden="true"></i></span></a>
                                <a href="https://twitter.com/ChempionGroup"><span class="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                            </div>
                        </div>
                    </div>
                    {$news}
                </div>
                <div class="row">
                    <div class="col-md-12 ta-r">
                        <a href="/news/" class="arrow-link">Все новости, советы, статьи <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
EOF;
