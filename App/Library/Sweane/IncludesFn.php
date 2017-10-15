<?php
class IncludesFn
{
    public static function printHeader($title, $bodyClass = null, $info = null)
    {
        $menuVisible = BF::ClearCode("menu", "str", "cookie");

        $header = <<<EOF
<!DOCTYPE html>
<html>
<head>

<title>{$title}</title>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="/Libs/FrontEnd/css/eric-meyer-reset.css" title="no title">
<link rel="stylesheet" href="/Libs/FrontEnd/bootstrap-3.3.7/css/bootstrap.min.css" media="screen" title="no title" charset="utf-8">
<link rel="stylesheet" href="/Libs/FrontEnd/owl/owl.carousel.css">
<link rel="stylesheet" href="/Libs/FrontEnd/owl/owl.theme.default.css">
<link rel="stylesheet" href="/Libs/FrontEnd/css/fonts.css" title="no title">
<link rel="stylesheet" href="/Libs/FrontEnd/css/main.css" title="no title">
<link rel="stylesheet" href="/Libs/FrontEnd/css/font-awesome.css">

{$info}

</head>
<body class="{$bodyClass} {$menuVisible}">
<div class="copy-body">
EOF;
        print($header);

    }

    public static function ReturnRating($int) // From 0 To 100
    {
        $countFull = (int)($int / 20);
        $countHalf = 0;

        if($int %20 != 0)
        {
            $countHalf = 1;
        }

        $countEmpty =  5 - (int)($countFull + $countHalf);

        $arrayRating = [$countFull, $countHalf, $countEmpty];

        $htmlRating = "";

        foreach ($arrayRating as $key => $value)
        {
            for ($i = 0; $i < $value; $i++)
            {
                if($key == 0)
                {
                    $htmlRating .= '<i class="fa fa-star" aria-hidden="true"></i>';
                }
                else if($key == 1)
                {
                    $htmlRating .= '<i class="fa fa-star-half-o" aria-hidden="true"></i>';
                }
                else
                {
                    $htmlRating .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
                }
            }
        }

        return $htmlRating;
    }

    public static function ReturnIconCategory($name)
    {
        switch ($name)
        {
            case ("web"):
                return "/Images/Icons/web-03.svg"; break;
        }
    }

    public static function printMenuCategory($sql, $url, $name, $default = null, $prefix = null)
    {
        $category = R::getAll($sql);

        $li = "";

        foreach ($category as $value)
        {
            $href = $value[$url];
            $title = $value[$name];

            if($href == $default)
            {
                $li .= '<li><a href="' . $prefix . $href . '" class="active"><i class="fa fa-check-circle-o" aria-hidden="true"></i> ' . $title . '</a></li>';
                continue;
            }

            $li .= '<li><a href="' . $prefix . $href . '"><i class="fa fa-circle-o" aria-hidden="true"></i> ' . $title . '</a></li>';
        }
        $menu = <<<EOF
            <ul>
                {$li}
            </ul>
EOF;
        return $menu;
    }

    public static function printMenu($url, $menuName, $className = null, $shell = null, $print = null)
    {
        $menu = "";

        $arrayMenu = [
            "/" => 'dashboard_menu_home_page',
            "/services/" => 'dashboard_menu_services',
            "/blog/" => 'dashboard_menu_blog',
            "/portfolio/" => 'dashboard_menu_portfolio',
            "/shop/" => 'dashboard_menu_template_shop',
            "/contacts/" => 'dashboard_menu_contacts',
        ];

        if($menuName == "base")
        {
            $li = "";

            foreach ($arrayMenu as $key => $value)
            {
                if($key == $url)
                {
                    $li .= '<li><a href="' . $key . '" class="active">' . Languages::Translate($value) . '</a></li>';
                    continue;
                }

                $li .= '<li><a href="' . $key . '">' . Languages::Translate($value) . '</a></li>';
            }
            $menu = <<<EOF
                <ul>
                    {$li}
                </ul>
EOF;
        }

        if($shell)
        {
            $languagesSwitch = Languages::LanguageSwitch("in");

            $menu = <<<EOF
            <div class="header-any-page clearfix {$className}">
                <div class="head-logo">
                    <a href="/"><div class="link-go-home"><img alt="Carrot" src="/Images/Home/logoBold-03.svg"></div></a>
                </div>
                <div class="head-menu">
                    <button><i class="fa fa-bars" aria-hidden="true"></i></button>
                    {$menu}
                </div>
                <div class="head-user">
                    {$languagesSwitch}
                        <a href="/login/"><button class="login-button clear-button"><span><i class="fa fa-user" aria-hidden="true"></i></span></button></a>
                </div>
            </div>
EOF;
        }

        if($print)
        {
            return print($menu);
        }
        return $menu;
    }

    public static function GenerateCategoryMenu()
    {
        $rootCategory = R::getAll("SELECT * FROM Categories WHERE Categories_parent = 0");

        $rootLi = '';

        foreach ($rootCategory as $value)
        {
            $subCategory = R::getAll("SELECT * FROM Categories WHERE Categories_parent = ?", [$value["Categories_id"]]);

            $subLi = '';
            $img = '';

            foreach ($subCategory as $subValue)
            {
                $popularProducts = R::getAll("SELECT * FROM Categories WHERE Categories_parent = ?", [$subValue["Categories_id"]]);

                $liProduct = '';

                foreach($popularProducts as $productValue)
                {
                    $liProduct .= '<li class="menu-li" data-id="'.$productValue["Categories_id"].'" data-img="/Images/Products/'.$productValue["ProductImagesPreview"].'"><a href="/category/'.$productValue["Categories_id"].'">' . $productValue["Categories_name"] . '</a></li>';
                }

                $ulProduct = '<ul>' . $liProduct . '</ul>';

                $subLi .= '<div><a href="/category/' . $subValue["Categories_id"] . '">' . $subValue["Categories_name"] . '</a>'.$ulProduct.'</div>';
            }

            $shares = R::getRow("SELECT * FROM Shares WHERE Shares_category = ? AND Shares_view = 1", [
                $value["Categories_id"]
            ]);

            if(intval($shares["shares_id"]) > 0)
            {
                $img = '<div class="img-preview-menu">
                    <strong>' . $shares["shares_title"] . '</strong>
                    <img src="' . $shares["shares_img"] . '">
                    <a href="' . $shares["shares_url"] . '">Перейти</a>
                </div>';
            }

            $subLi = '<div>' . $subLi . $img .'</div>';

            $rootLi .= '<li><a href="/category/' . $value["Categories_id"] . '">' . $value["Categories_name"] . "</a>" . $subLi . '</li>';
        }

        $ul = '<ul>' . $rootLi . '</ul>';

        return $ul;
    }
}