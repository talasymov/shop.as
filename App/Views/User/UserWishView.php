<?php
IncludesFn::printHeader("Wish", "grey");

$wish = ShopFn::DrawProduct(ShopFn::GetProductsFromWish(), 4);

$bodyText = <<<EOF
<h2 class="ta-l strong">Мои желания</h2><br />
<div class="container-fluid">
    <div class="row">
        {$wish}
    </div>
</div>
EOF;
