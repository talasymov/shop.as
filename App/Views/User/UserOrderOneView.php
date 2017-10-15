<?php
IncludesFn::printHeader("Orders", "grey");

$orders = ShopFn::DrawProduct($data, 4);

$bodyText = <<<EOF
<h2 class="ta-l strong">В заказ входит:</h2><br />
<div class="container-fluid">
    <div class="row">
        {$orders}
    </div>
    <div class="row">
        <div class="col-md-12">
            <a href="/user/orders"><button class="btn btn-info">Вернуться назад</button></a>
        </div>
    </div>
</div>
EOF;
