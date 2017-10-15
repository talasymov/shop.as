<?php
function InitHere($command = null)
{
    if($command == "SetCookie")
    {
        setcookie("language", BF::ClearCode("value", "str", "post"), time() + 86400, "/");
    }
    else if($command == "ListUsers")
    {
        $users = R::getAll("SELECT carrot_users_id, carrot_users_name FROM carrot_users WHERE carrot_users_permissions <> 777 AND carrot_users_permissions <> 1");

        print(AuxiliaryFn::ArrayToSelect($users, "UsersSelect", "carrot_users_id", "carrot_users_name", "Select User"));
    }
    else if($command == "AddBlog")
    {
        $image = BF::UploadFile("image", "/Images/Blog/");

        if($image["imageStatus"])
        {
        R::exec("INSERT INTO carrot_blog(
        carrot_blog_content,
        carrot_blog_category,
        carrot_blog_language,
        carrot_blog_title,
        carrot_blog_description,
        carrot_blog_keywords,
        carrot_blog_images
        ) VALUES(?, ?, ?, ?, ?, ?, ?)", [
            BF::ClearCode("content", "str", "post"),
            BF::ClearCode("category", "int", "post"),
            BF::ClearCode("language", "str", "post"),
            BF::ClearCode("title", "str", "post"),
            BF::ClearCode("description", "str", "post"),
            BF::ClearCode("keywords", "str", "post"),
            $image["imageUploadedName"]
        ]);
        }

        AuxiliaryFn::StylePrint($image);
    }
    else if($command == "Filter")
    {
        $filter = R::getAll("SELECT *  FROM Product

        LEFT JOIN CharacteristicsOutput ON CharacteristicsOutput.cOutput_id_Product = Product.ID_Product

        WHERE cOutput_id_SubCategory = ? AND cOutput_id_Value IN (?)
        
        GROUP BY Product.ID_Product",
            [BF::ClearCode("category", "int", "post"), implode(",", BF::ClearCode("listVar", "array", "post"))]);

        print(json_encode($filter));
    }
    else if($command == "AddToCart")
    {
//        AuxiliaryFn::StylePrint($_POST);
//        AuxiliaryFn::StylePrint(ShopFn::GetIdFromCart());
        ShopFn::AddToCart(BF::ClearCode("idProduct", "int", "post"), BF::ClearCode("prop", "array", "post"));
        print(json_encode(ShopFn::DesignCartInfo()));
    }
    else if($command == "AddToBalance")
    {
        ShopFn::AddToBalance(BF::ClearCode("idProduct", "int", "post"));

        print(json_encode(ShopFn::DesignBalanceInfo()));
    }
    else if($command == "DeleteBalance")
    {
        ShopFn::DeleteFromBalance(BF::ClearCode("idProduct", "int", "post"));

        print(json_encode(ShopFn::DesignBalanceInfo()));
    }
    else if($command == "LoginInSystem")
    {
        $login = BF::ClearCode("login", "str", "post");
        $password = BF::ClearCode("password", "str", "post");

        $checkUserInSystem = BF::CheckUserInSystem(BF::GeneratePass($login), BF::GeneratePass($password));

        if($checkUserInSystem == 1)
        {
            BF::LoginUser(BF::GeneratePass($login), BF::GeneratePass($password));
        }

        print($checkUserInSystem);
    }
    else if($command == "QuitFromSystem")
    {
        BF::QuitUser();
    }
    else if($command == "ClearCart")
    {
        ShopFn::ClearCart();
    }
    else if($command == "IfUserInSystem")
    {
        print(BF::IfUserInSystem());
    }
    else if($command == "RegisterUser")
    {
        $result = [
            "status" => 1,
            "cart" => 0
        ];

        $login = BF::ClearCode("Login", "str", "post");
        $password = BF::ClearCode("Password", "str", "post");

        R::exec("INSERT INTO Users(Users_name, Users_surname, Users_patronymic, Users_birth, Users_gender, Users_login, Users_password, Users_email, Users_phone) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)", [
            BF::ClearCode("Name", "str", "post"),
            BF::ClearCode("Surname", "str", "post"),
            BF::ClearCode("Patronymic", "str", "post"),
            BF::ClearCode("Birth", "str", "post"),
            BF::ClearCode("Gender", "str", "post"),
            BF::GeneratePass($login),
            BF::GeneratePass($password),
            BF::ClearCode("Email", "str", "post"),
            BF::ClearCode("Phone", "str", "post")
        ]);

        if(ShopFn::GetCountInCart() != 0)
        {
            $result["cart"] = 1;
        }

        print(json_encode($result));
    }
    else if($command == "UpdateInfoUser")
    {
        $result = [
            "status" => 1
        ];

        R::exec("UPDATE Users SET Users_name = ?, Users_surname = ?, Users_patronymic = ?, Users_birth = ?, Users_gender = ?, Users_email = ?, Users_phone = ? WHERE Users_id = ?", [
            BF::ClearCode("Name", "str", "post"),
            BF::ClearCode("Surname", "str", "post"),
            BF::ClearCode("Patronymic", "str", "post"),
            BF::ClearCode("Birth", "str", "post"),
            BF::ClearCode("Gender", "str", "post"),
            BF::ClearCode("Email", "str", "post"),
            BF::ClearCode("Phone", "str", "post"),
            BF::ClearCode("IdUser", "int", "post")
        ]);

        print(json_encode($result));
    }
    else if($command == "UpdateAccountInfoUser")
    {
        $login = BF::ClearCode("login", "str", "post");
        $password = BF::ClearCode("password", "str", "post");

        R::exec("UPDATE Users SET Users_login = ?, Users_password = ? WHERE Users_id = ?", [
            BF::GeneratePass($login),
            BF::GeneratePass($password),
            BF::ClearCode("idUser", "int", "post")
        ]);

        BF::LoginUser($login, $password);
    }
    else if($command == "CheckOut")
    {
        ShopFn::CheckOut();
    }
    else if($command == "AddAddress")
    {
        R::exec("INSERT INTO Address(Country, Region, City, Street, Build_numb, Porch, Apartment, Address_user_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?)", [
            BF::ClearCode("countryInput", "str", "post"),
            BF::ClearCode("regionInput", "str", "post"),
            BF::ClearCode("cityInput", "str", "post"),
            BF::ClearCode("streetInput", "str", "post"),
            BF::ClearCode("buildInput", "str", "post"),
            BF::ClearCode("porchInput", "str", "post"),
            BF::ClearCode("apartmentInput", "str", "post"),
            BF::ReturnInfoUser(BF::idUser)
        ]);
    }
    else if($command == "DeleteAddress")
    {
        R::exec("DELETE FROM Address WHERE ID_address = ?", [BF::ClearCode("idAddress", "int", "post")]);
    }
    else if($command == "DeleteFromCart")
    {
        ShopFn::DeleteFromCart(BF::ClearCode("idProduct", "int", "post"));

        print(json_encode(ShopFn::DesignCartInfo()));
    }
    else if($command == "MenuCookie")
    {
        setcookie("menu", BF::ClearCode("class", "str", "post"), time() + 3600 * 24 * 31, "/");  /* срок действия 1 час */
    }
    else if($command == "GetFilesFromDir")
    {
        $files = Files::StyleFiles(BF::ClearCode("dir", "str", "post"));

        $dir = '';
        $path = '';
        $breadcrumbs = "<div class='file-one-dir' data-dir='" . DIR_IMAGES_NON_ROOT . "'>Images</div>";

        $dir = explode("/Images/", BF::ClearCode("dir", "str", "post"));
        $dirExplode = explode("/", $dir[1]);

        foreach ($dirExplode as $value)
        {
            if($value != "")
            {
                $path .= $value . "/";

                $breadcrumbs .= "<div class='file-one-dir' data-dir='" . DIR_IMAGES_NON_ROOT . $path . "'>" . $value . "</div>";
            }
        }

        $breadcrumbs = "<div class='breadcrumbs'>" . $breadcrumbs . "</div>";

        print($breadcrumbs . $files);
    }
    else if($command == "UploadFile")
    {
        $dir = explode("/Images/", BF::ClearCode("dir", "str", "post"));

        $image = BF::UploadFile("file", "/Images/" . $dir[1]);

        AuxiliaryFn::StylePrint($image);
    }
    else if($command == "DeleteFile")
    {
        $dir = DIR_ROOT . BF::ClearCode("dir", "str", "post");

        if (is_dir($dir)) {
            rmdir($dir);
        }
        else
        {
            unlink($dir);
        }
    }
    else if($command == "CreateFolder")
    {
        mkdir(DIR_ROOT . BF::ClearCode("dir", "str", "post") . BF::CreateLinkFromString(BF::ClearCode("name", "str", "post")), 0700);
    }
    else if($command == "GetPropertiesFromId")
    {
        $child = R::getAll("SELECT * FROM Properties WHERE PropertiesValues_category = ?", [
            BF::ClearCode("id", "int", "post")
        ]);

        $tr = "";

        foreach ($child as $subValue)
        {
            $idProp = $subValue["PropertiesValues_id"];

            $tr .= '<tr>
                <td style="width: 30px"><input class="checkbox-property" data-id="' . $idProp . '" type="checkbox"></td>
                <td>' . $subValue["PropertiesValues_name"] . '</td>
                <td><div class="slct-modal-div-image">
                    <img src="">
                    <span class="slct-name">Выберите фото</span>
                    <button class="clear-button property-image" data-url=""><i class="fa fa-camera" aria-hidden="true"></i></button>
                </div></td>
                <td><input class="design-input" /></td>
            </tr>';
        }

        $table = <<<EOF
        <thead class="strong">
            <tr><th></th><th>Название</th><th>Фото товара</th><th>Цена</th></tr>
        </thead>
        <tbody>
            {$tr}
        </tbody>
EOF;

        print($table);
    }
    else if($command == "AddProduct")
    {
        $listImages = "";
        $imagePreview = "";

        $nameProduct = BF::CreateLinkFromString(BF::ClearCode("name", "str", "post"));

        $properties = json_decode($_POST["properties"], true);

        /*
         * UPLOAD IMAGES
         */

        for($i = 1; $i <= BF::ClearCode("imagesCount", "int", "post"); $i++)
        {
            $file = BF::UploadFile($i . "image", "/Images/Products/" . $nameProduct . "/");

            if($file["imageStatus"])
            {
                $listImages .= $nameProduct . "/" . $file["imageUploadedName"] . ";";
            }
        }

        $file = BF::UploadFile("imagePreview", "/Images/Products/" . $nameProduct . "/");

        if($file["imageStatus"])
        {
            $imagePreview = $nameProduct . "/" .$file["imageUploadedName"];
        }

        /*
         * ADD PRODUCT TO BD
         */

        R::exec("INSERT INTO Products (
            ProductName,
            ProductImages,
            ProductImagesPreview,
            ProductDescription,
            ProductPrice,
            ProductPurchasePrice,
            ProductLastPrice,
            ProductCategory,
            ProductSeoDesc,
            ProductSeoKeywords,
            ProductUrl,
            ProductVendorCode,
            ProductWeight,
            ProductDimensions,
            ProductCount,
            ProductType,
            ProductStatus,
            ProductUser
            ) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("images", "str", "post"),
            BF::ClearCode("imagePreview", "str", "post"),
            BF::ClearCode("description", "str", "post"),
            BF::ClearCode("price", "double", "post"),
            BF::ClearCode("purchasePrice", "int", "post"),
            BF::ClearCode("lastPrice", "int", "post"),
            BF::ClearCode("category", "int", "post"),
            BF::ClearCode("seoDesc", "str", "post"),
            BF::ClearCode("seoKeywords", "str", "post"),
            BF::CreateLinkFromString(BF::ClearCode("name", "str", "post")),
            BF::ClearCode("code", "str", "post"),
            BF::ClearCode("weight", "double", "post"),
            BF::ClearCode("dimensions", "str", "post"),
            BF::ClearCode("have", "int", "post"),
            BF::ClearCode("type", "int", "post"),
            BF::ClearCode("status", "int", "post"),
            BF::ReturnInfoUser(BF::idUser)
        ]);

        $lastProduct = R::getRow("SELECT ID_product FROM Products ORDER BY ID_product DESC");

        $wholesale = explode(";", $_POST["wholesale"]);

        foreach ($wholesale as $value)
        {
            $wholesaleChild = explode("*", $value);

            if(BF::ClearCode($wholesaleChild[1], "int") != 0)
            {
                R::exec("INSERT Wholesale(Wholesale_price, Wholesale_count, Wholesale_product) VALUES(?, ?, ?)", [
                    BF::ClearCode($wholesaleChild[0], "double"),
                    BF::ClearCode($wholesaleChild[1], "int"),
                    $lastProduct["ID_product"]
                ]);
            }
        }

        foreach ($properties as $key => $value)
        {
            foreach ($value as $subKey => $subValue)
            {
                R::exec("INSERT INTO PropertiesValues(Properties_img, Properties_price, Properties_product, Properties_id_value) VALUES(?, ?, ?, ?)", [
                    $subValue["pic"],
                    BF::ClearCode($subValue["price"], "double"),
                    $lastProduct["ID_product"],
                    $subKey
                ]);
            }
        }

        /*
         * ADD CHARACTERISTIC
         */

        foreach(BF::ClearCode("characteristics", "array", "post") as $key => $value)
        {
            AuxiliaryFn::StylePrint($value);

            R::exec("INSERT INTO CharacteristicsOutput (cOutput_id_Product, cOutput_id_Schema, cOutput_id_Value, cOutput_id_SubCategory) VALUES (?, ?, ?, ?)", [
                $lastProduct["ID_product"],
                $key,
                $value,
                BF::ClearCode("category", "int", "post")
            ]);
        }

        foreach(BF::ClearCode("characteristicsValue", "array", "post") as $key => $value)
        {
            AuxiliaryFn::StylePrint($value);

            R::exec("INSERT INTO CharacteristicsOutput (cOutput_id_Product, cOutput_id_Schema, cOutput_Value, cOutput_id_SubCategory) VALUES (?, ?, ?, ?)", [
                $lastProduct["ID_product"],
                $key,
                $value,
                BF::ClearCode("category", "int", "post")
            ]);
        }
//
        BF::AddActionToChronology("Добавление товара с ID [" . $lastProduct["ID_product"] . "]");

    }
    else if($command == "EditProduct")
    {
        R::exec("UPDATE Products SET ProductName = ?,
        ProductImages = ?,
        ProductImagesPreview = ?,
        ProductDescription = ?,
        ProductPrice = ?,
        ProductPurchasePrice = ?,
        ProductLastPrice = ?,
        ProductPublished = ?,
        ProductCategory = ?,
        ProductSeoDesc = ?,
        ProductSeoKeywords = ?,
        ProductUrl = ? WHERE ID_product = ?", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("images", "str", "post"),
            BF::ClearCode("imagePreview", "str", "post"),
            BF::ClearCode("description", "str", "post"),
            BF::ClearCode("price", "int", "post"),
            BF::ClearCode("purchasePrice", "int", "post"),
            BF::ClearCode("lastPrice", "int", "post"),
            BF::ClearCode("published", "int", "post"),
            BF::ClearCode("category", "int", "post"),
            BF::ClearCode("seoDesc", "str", "post"),
            BF::ClearCode("seoKeywords", "str", "post"),
            BF::CreateLinkFromString(BF::ClearCode("name", "str", "post")),
            BF::ClearCode("idProduct", "int", "post")
        ]);
//        $listImages = "";
//        $imagePreview = "";
//        $nameProduct = BF::CreateLinkFromString(BF::ClearCode("name", "str", "post"));
//
//        /*
//         * UPLOAD IMAGES
//         */
//
//        for($i = 0; $i <= BF::ClearCode("imagesCount", "int", "post"); $i++)
//        {
//            $file = BF::UploadFile($i . "image", "/Images/Products/" . $nameProduct . "/");
//
//            if($file["imageStatus"])
//            {
//                $listImages .= $file["imageUploadedName"] . ";";
//            }
//        }
//
//        $filePreview = BF::UploadFile("imagePreview", "/Images/Products/" . $nameProduct . "/");
//
//
//        if($filePreview["imageStatus"])
//        {
//            $imagePreview = $filePreview["imageUploadedName"];
//        }
//
//        /*
//         * ADD PRODUCT TO BD
//         */
//
//
//        if($listImages != "" && $imagePreview != "")
//        {
//            R::exec("UPDATE Products SET ProductName = ?,
//            ProductImages = ?,
//            ProductImagesPreview = ?,
//            ProductDescription = ?,
//            ProductPrice = ?,
//            ProductPurchasePrice = ?,
//            ProductLastPrice = ?,
//            ProductPublished = ?,
//            ProductCategory = ?,
//            ProductSeoDesc = ?,
//            ProductSeoKeywords = ?,
//            ProductUrl = ? WHERE ID_product = ?", [
//                BF::ClearCode("name", "str", "post"),
//                $listImages,
//                $imagePreview,
//                BF::ClearCode("description", "str", "post"),
//                BF::ClearCode("price", "int", "post"),
//                BF::ClearCode("purchasePrice", "int", "post"),
//                BF::ClearCode("lastPrice", "int", "post"),
//                BF::ClearCode("published", "int", "post"),
//                BF::ClearCode("category", "int", "post"),
//                BF::ClearCode("seoDesc", "str", "post"),
//                BF::ClearCode("seoKeywords", "str", "post"),
//                BF::CreateLinkFromString(BF::ClearCode("name", "str", "post")),
//                BF::ClearCode("idProduct", "int", "post")
//            ]);
//        }
//        else
//        {
//            R::exec("UPDATE Products SET ProductName = ?,
//            ProductDescription = ?,
//            ProductPrice = ?,
//            ProductPurchasePrice = ?,
//            ProductLastPrice = ?,
//            ProductPublished = ?,
//            ProductCategory = ?,
//            ProductSeoDesc = ?,
//            ProductSeoKeywords = ?,
//            ProductUrl = ? WHERE ID_product = ?", [
//                BF::ClearCode("name", "str", "post"),
//                BF::ClearCode("description", "str", "post"),
//                BF::ClearCode("price", "int", "post"),
//                BF::ClearCode("purchasePrice", "int", "post"),
//                BF::ClearCode("lastPrice", "int", "post"),
//                BF::ClearCode("published", "int", "post"),
//                BF::ClearCode("category", "int", "post"),
//                BF::ClearCode("seoDesc", "str", "post"),
//                BF::ClearCode("seoKeywords", "str", "post"),
//                BF::CreateLinkFromString(BF::ClearCode("name", "str", "post")),
//                BF::ClearCode("idProduct", "int", "post")
//            ]);
//        }
//
//
//        /*
//         * ADD CHARACTERISTIC
//         */
//
//        foreach(BF::ClearCode("characteristics", "array", "post") as $key => $value)
//        {
//            $count = R::getRow("SELECT COUNT(*) as Count, cOutput_id FROM CharacteristicsOutput
//            WHERE cOutput_id_Product = ?
//            AND cOutput_id_Schema = ?
//            AND cOutput_id_SubCategory = ?", [
//                BF::ClearCode("idProduct", "int", "post"),
//                $key,
//                BF::ClearCode("category", "int", "post")
//            ]);
//
//            if($count["Count"] > 0)
//            {
//                R::exec("UPDATE CharacteristicsOutput SET cOutput_id_Product = ?,
//                cOutput_id_Schema = ?,
//                cOutput_id_Value = ?,
//                cOutput_id_SubCategory = ? WHERE cOutput_id = ?", [
//                    BF::ClearCode("idProduct", "int", "post"),
//                    $key,
//                    $value,
//                    BF::ClearCode("category", "int", "post"),
//                    $count["cOutput_id"]
//                ]);
//            }
//            else
//            {
//                R::exec("INSERT INTO CharacteristicsOutput (cOutput_id_Product, cOutput_id_Schema, cOutput_id_Value, cOutput_id_SubCategory) VALUES (?, ?, ?, ?)", [
//                    BF::ClearCode("idProduct", "int", "post"),
//                    $key,
//                    $value,
//                    BF::ClearCode("category", "int", "post")
//                ]);
//            }
//        }
//
//        BF::AddActionToChronology("Редактирование товара с ID [" . BF::ClearCode("idProduct", "int", "post") . "]");
    }
    else if($command == "AddNews")
    {
        $imagePreview = '';
        $file = BF::UploadFile("imagePreview", "/Images/News/");


        if($file["imageStatus"])
        {
            $imagePreview = $file["imageUploadedName"];
        }

        R::exec("INSERT INTO News(news_title, news_img, news_content) VALUES(?, ?, ?)", [
            BF::ClearCode("name", "str", "post"),
            $imagePreview,
            BF::ClearCode("content", "str", "post")
        ]);
    }
    else if($command == "EditNews")
    {
        $imagePreview = '';
        $file = BF::UploadFile("imagePreview", "/Images/News/");


        if($file["imageStatus"])
        {
            $imagePreview = $file["imageUploadedName"];

            R::exec("UPDATE News SET news_title = ?, news_img = ?, news_content = ? WHERE news_id = ?", [
                BF::ClearCode("name", "str", "post"),
                $imagePreview,
                BF::ClearCode("content", "str", "post"),
                BF::ClearCode("idNews", "str", "post")
            ]);
        }
        else
        {
            R::exec("UPDATE News SET news_title = ?, news_content = ? WHERE news_id = ?", [
                BF::ClearCode("name", "str", "post"),
                BF::ClearCode("content", "str", "post"),
                BF::ClearCode("idNews", "int", "post")
            ]);
        }
    }
    else if($command == "DeleteNews")
    {
        R::exec("DELETE FROM News WHERE news_id = ?", [
            BF::ClearCode("idNews", "int", "post")
        ]);
    }
    else if($command == "DeleteProduct")
    {
        R::exec("DELETE FROM Products WHERE ID_product = ?", [BF::ClearCode("idProduct", "int", "post")]);
    }
    else if($command == "GetSubCategory")
    {
        $subCategory = R::getAll("SELECT * FROM SubCategory WHERE MainCategory_FK = ?", [BF::ClearCode("rootCategory", "int", "post")]);

        $selectRootCategory = AuxiliaryFn::ArrayToSelect($subCategory, "subCategory design-input", "ID_subCategory", "CategoryName", "Выберите из списка");

        print('<span class="header-blue">Дочерняя категория</span>' . $selectRootCategory);
    }
    else if($command == "GetSchemaValues")
    {
        $characteristicsSchema = R::getAll("SELECT * FROM CharacteristicsSchema WHERE cSchema_Category_FK = ?", [BF::ClearCode("category", "int", "post")]);

        $select = AuxiliaryFn::ArrayToSelect($characteristicsSchema, "characteristics-schema design-input", "ID_cSchema", "cSchema_Name", "Выберите из списка");

        print('<span class="header-blue">Шаг 2. Выберите характеристику</span>' . $select);
    }
    else if($command == "GetSubCategoryTable")
    {
        $subCategory = R::getAll("SELECT * FROM SubCategory WHERE MainCategory_FK = ?", [BF::ClearCode("rootCategory", "int", "post")]);

        $tr = '';

        foreach ($subCategory as $value)
        {
            $tr .= '<tr>
                <td>' . $value["ID_subCategory"] . '</td>
                <td><input class="design-input edit-sub-category' . $value["ID_subCategory"] . '" value="' . $value["CategoryName"] . '" /></td>
                <td>
                    <button class="save-subcategory btn btn-info" data-id="' . $value["ID_subCategory"] . '"><i class="fa fa-cloud" aria-hidden="true"></i></button>
                    <button class="delete-subcategory btn btn-danger" data-id="' . $value["ID_subCategory"] . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>';
        }

        print('<table class="table">' . $tr . '</table><hr /><input class="design-input text-sub-category" placeholder="Название" /> <button class="add-sub-category btn btn-info"><i class="fa fa-cloud" aria-hidden="true"></i> Создать подкатегорию</button>');
    }
    else if($command == "UpdateCategory")
    {
        R::exec("UPDATE Categories SET Categories_name = ?, Categories_image = ? WHERE Categories_id = ?", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("image", "str", "post"),
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "UpdateSubCategory")
    {
        R::exec("UPDATE SubCategory SET CategoryName = ? WHERE ID_subCategory = ?", [
            BF::ClearCode("text", "str", "post"),
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "DeleteCategory")
    {
        R::exec("DELETE FROM Categories WHERE Categories_id = ?", [
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "DeleteSubCategory")
    {
        R::exec("DELETE FROM SubCategory WHERE ID_subCategory = ?", [
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "AddCategory")
    {
        R::exec("INSERT INTO Categories (Categories_name) VALUES (?)", [
            BF::ClearCode("text", "str", "post")
        ]);
    }
    else if($command == "AddSubCategory")
    {
        R::exec("INSERT INTO Categories (Categories_name, Categories_parent, Categories_image) VALUES (?, ?, ?)", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("parent", "int", "post"),
            BF::ClearCode("image", "str", "post")
        ]);
    }
    else if($command == "AddSchemaValue")
    {
        R::exec("INSERT INTO CharacteristicsValue (cValueValue, cValueSchema_FK) VALUES (?, ?)", [
            BF::ClearCode("value", "str", "post"),
            BF::ClearCode("schema", "int", "post")
        ]);
    }
    else if($command == "GetCharacteristicsInCategory")
    {
        $data = ShopFn::GetCharacteristic(BF::ClearCode("category", "int", "post"));
        $selectCharacteristic = "";

        foreach ($data as $value)
        {
            $listValues = R::getAll("SELECT * FROM CharacteristicsValue WHERE cValueSchema_FK = ?", [$value["ID_cSchema"]]);

            $select = AuxiliaryFn::ArrayToSelect($listValues, "design-input characteristic" . $value["ID_cSchema"], "ID_cValue", "cValueValue", "Выберите из списка");

            $selectCharacteristic .= '<tr><td><span class="list-characteristic" data-value="' . $value["ID_cSchema"] . '">' . $value["cSchema_Name"] . '</td><td>' . $select . '</td><td><input class="design-input characteristic-val-' . $value["ID_cSchema"] . '" /></td></tr>';
        }

        $thead = '<thead class="strong"><th>Наименование</th><th>Значение из списка</th><th>Значение новое</th></thead>';

        $selectCharacteristic = '<tbody>' . $selectCharacteristic . '</tbody>';

        print($thead . $selectCharacteristic);
    }
    else if($command == "AddWish")
    {
        $count = R::getRow("SELECT COUNT(*) as Count FROM Wish WHERE Wish_id_product = ? AND Wish_user = ?", [
            BF::ClearCode("idProduct", "int", "post"),
            BF::ReturnInfoUser(BF::idUser)
        ]);

        if($count["Count"] == 0 && BF::ReturnInfoUser(BF::idUser) != 0)
        {
            R::exec("INSERT INTO Wish (Wish_id_product, Wish_user) VALUES (?, ?)", [
                BF::ClearCode("idProduct", "int", "post"),
                BF::ReturnInfoUser(BF::idUser)
            ]);

            print(json_encode(ShopFn::DesignWishInfo()));
        }
    }
    else if($command == "DeleteWish")
    {
        R::exec("DELETE FROM Wish WHERE Wish_id_product = ? AND Wish_user = ?", [
            BF::ClearCode("idProduct", "int", "post"),
            BF::ReturnInfoUser(BF::idUser)
        ]);

        print(json_encode(ShopFn::DesignWishInfo()));
    }
    else if($command == "DeleteFromBalance")
    {
        ShopFn::DeleteFromBalance(BF::ClearCode("idProduct", "int", "post"));

        print(json_encode(ShopFn::DesignBalanceInfo()));
    }
    else if($command == "SaveChangedCart")
    {
        foreach (BF::ClearCode("products", "array", "post") as $key => $value)
        {
            ShopFn::ChangeCountInCart($key, $value);
        }
    }
    else if($command == "AddReview")
    {
        R::exec("INSERT INTO Review(ReviewText, ID_user_FK, ID_product_FK) VALUES(?, ?, ?)", [
            BF::ClearCode("text", "str", "post"),
            BF::ReturnInfoUser(BF::idUser),
            BF::ClearCode("idProduct", "int", "post")
        ]);
    }
    else if($command == "AddCharacteristic")
    {
        R::exec("INSERT INTO CharacteristicsSchema(cSchema_Name, cSchema_Category_FK) VALUES(?, ?)", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("idCategory", "int", "post")
        ]);
    }
    else if($command == "GetSchemaValuesTable")
    {
        $subCategory = R::getAll("SELECT * FROM CharacteristicsSchema
 WHERE cSchema_Category_FK = ?", [BF::ClearCode("category", "int", "post")]);

        $tr = '';

        foreach ($subCategory as $value)
        {
            $tr .= '<tr>
                <td>' . $value["ID_cSchema"] . '</td>
                <td><input class="design-input edit-val-schema' . $value["ID_cSchema"] . '" value="' . $value["cSchema_Name"] . '" /></td>
                <td>
                    <button class="save-schema btn btn-info" data-id="' . $value["ID_cSchema"] . '"><i class="fa fa-cloud" aria-hidden="true"></i></button>
                    <button class="delete-schema btn btn-danger" data-id="' . $value["ID_cSchema"] . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>';
        }

        print('<br /><h3>Редактирование характеристик</h3><table class="table">' . $tr . '</table>');
    }
    else if($command == "SaveSchema")
    {
        R::exec("UPDATE CharacteristicsSchema SET cSchema_Name = ? WHERE ID_cSchema = ?", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("idSchema", "int", "post")
        ]);
    }
    else if($command == "SaveSchemaVal")
    {
        R::exec("UPDATE CharacteristicsValue SET cValueValue = ? WHERE ID_cValue = ?", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("idSchemaVal", "int", "post")
        ]);
    }
    else if($command == "DeleteSchema")
    {
        R::exec("DELETE FROM CharacteristicsSchema WHERE ID_cSchema = ?", [
            BF::ClearCode("idSchema", "int", "post")
        ]);
    }
    else if($command == "DeleteSchemaVal")
    {
        R::exec("DELETE FROM CharacteristicsValue WHERE ID_cValue = ?", [
            BF::ClearCode("idSchemaVal", "int", "post")
        ]);
    }
    else if($command == "GetSchemaValuesForEdit")
    {
        $schemaValues = R::getAll("SELECT * FROM CharacteristicsValue WHERE cValueSchema_FK = ?", [
            BF::ClearCode("idSchemaValue", "int", "post")
        ]);

        $tr = '';

        foreach ($schemaValues as $value)
        {
            $tr .= '<tr>
                <td>' . $value["ID_cValue"] . '</td>
                <td><input class="design-input edit-val-s-schema' . $value["ID_cValue"] . '" value="' . $value["cValueValue"] . '" /></td>
                <td>
                    <button class="save-schema-val btn btn-info" data-id="' . $value["ID_cValue"] . '"><i class="fa fa-cloud" aria-hidden="true"></i></button>
                    <button class="delete-schema-val btn btn-danger" data-id="' . $value["ID_cValue"] . '"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>';
        }

        print('<br /><h3>Редактирование значений характеристик</h3><table class="table">' . $tr . '</table>');
    }
    else if($command == "SaveOrderAdmin")
    {
        R::exec("UPDATE OrdersGroup SET OrdersGroup_status = ? WHERE OrdersGroup_id = ?", [
            BF::ClearCode("idStatus", "int", "post"),
            BF::ClearCode("idOrder", "int", "post")
        ]);

        if(BF::ClearCode("idStatus", "int", "post") == 5)
        {
            $orders = R::getAll("SELECT * FROM Orders WHERE Orders_id_group = ?", [
                BF::ClearCode("idOrder", "int", "post")
            ]);

            foreach ($orders as $value)
            {
                R::exec("UPDATE Products SET ProductRating = ProductRating + 1 WHERE ID_product = ?", [
                    $value["Orders_id_"]
                ]);
            }
        }
    }
    else if($command == "SendMail")
    {
        $message = 'Имя: ' . BF::ClearCode("name", "str", "post") . '\nТелефон:' . BF::ClearCode("phone", "str", "post") . '\nСообщение:' . BF::ClearCode("message", "str", "post") . '\n';

        mail("tmsweane@gmail.com", "Перезвон с сайта", $message);
    }
    else if($command == "GetRootCategory")
    {
        $category = R::getAll("SELECT * FROM Categories WHERE Categories_parent = 0");

        print(BF::GenerateList($category, '<div class="list-category-click" data-rule="root" data-id="?">?</div>', ["Categories_id", "Categories_name"]));
    }
    else if($command == "GetChildCategory")
    {
        $categoryId = BF::ClearCode("category", "int", "post");

        $selectedCategory = '<div class="strong">Номер категории: <span class="response-data-id">' . $categoryId . '</span></div>';

        $category = R::getAll("SELECT * FROM Categories WHERE Categories_parent = ?", [
            BF::ClearCode("category", "int", "post")
        ]);

        $list = BF::GenerateList($category, '<div class="list-category-click" data-rule="child" data-id="?">?</div>', ["Categories_id", "Categories_name"]);

        $breadcrumbs = BF::GetPathCategory(BF::ClearCode("category", "int", "post"));

        $body = <<<EOF
        <div class="">
            <div class="strong">Выбрана категория: <span class="response-data-name">{$breadcrumbs}</span></div>
            {$list}
            {$selectedCategory}
        </div>
EOF;
        print($body);
    }
    else if($command == "SaveProperty")
    {
        R::exec("UPDATE Properties SET PropertiesValues_name = ?, PropertiesValues_color = ? WHERE PropertiesValues_id = ?", [
            BF::ClearCode("valueProp", "str", "post"),
            BF::ClearCode("colorProp", "str", "post"),
            BF::ClearCode("idProp", "int", "post")
        ]);
    }
    else if($command == "AddNewPropChild")
    {
        R::exec("INSERT INTO Properties(PropertiesValues_name, PropertiesValues_color, PropertiesValues_category) VALUES(?, ?, ?)", [
            BF::ClearCode("name", "str", "post"),
            "#ffffff",
            BF::ClearCode("idParent", "int", "post")
        ]);
    }
    else if($command == "DeletePropChild")
    {
        R::exec("DELETE FROM Properties WHERE PropertiesValues_id = ?", [
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "AddPropParent")
    {
        R::exec("INSERT INTO PropertiesParent(PropertiesGroup_name) VALUES(?)", [
            BF::ClearCode("name", "str", "post")
        ]);
    }
    else if($command == "Search")
    {

    }
    else if($command == "ChangeCurrency")
    {
        setcookie("currency", BF::ClearCode("id", "int", "post"), time() + 86440, "/");
    }
    else if($command == "EditCurrency")
    {
        R::exec("UPDATE Currency SET
        Currency_name = ?,
        Currency_code = ?,
        Currency_symbol_left = ?,
        Currency_symbol_right = ?,
        Currency_value = ?
        WHERE Currency_id = ?", [
            BF::ClearCode("name", "str", "post"),
            BF::ClearCode("code", "str", "post"),
            BF::ClearCode("left", "str", "post"),
            BF::ClearCode("right", "str", "post"),
            BF::ClearCode("value", "double", "post"),
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "DeleteCurrency")
    {
        R::exec("DELETE FROM Currency
        WHERE Currency_id = ?", [
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "SaveBanner")
    {
        if(BF::ClearCode("id", "int", "post") > 0)
        {
            R::getRow("UPDATE Shares SET
                shares_title = ?,
                shares_img = ?,
                shares_url = ?,
                shares_category = ?
            WHERE shares_id = ?", [
                BF::ClearCode("title", "str", "post"),
                BF::ClearCode("image", "str", "post"),
                BF::ClearCode("url", "str", "post"),
                BF::ClearCode("category", "int", "post"),
                BF::ClearCode("id", "int", "post")
            ]);
        }
        else
        {
            R::getRow("INSERT INTO Shares
                (shares_title,
                shares_img,
                shares_url,
                shares_category)
            VALUES(?, ?, ?, ?)", [
                BF::ClearCode("title", "str", "post"),
                BF::ClearCode("image", "str", "post"),
                BF::ClearCode("url", "str", "post"),
                BF::ClearCode("category", "str", "post")
            ]);
        }
    }
    else if($command == "ViewBanner")
    {
        R::getRow("UPDATE Shares SET
        shares_view = ?
        WHERE shares_id = ?", [
            BF::ClearCode("view", "int", "post"),
            BF::ClearCode("id", "int", "post")
        ]);
    }
    else if($command == "SendPartnerQuery")
    {
        $partner = R::getRow("SELECT * FROM Partners WHERE Partners_user = ?", [
            BF::ReturnInfoUser(BF::idUser)
        ]);

        if(intval($partner["Partners_user"]) === 0)
        {
            R::exec("INSERT INTO Partners(Partners_user) VALUES(?)", [
                BF::ReturnInfoUser(BF::idUser)
            ]);
        }
    }
    else if($command == "SaveSettingsShop")
    {
        R::exec("UPDATE Users SET Users_image = ?, Users_company_name = ? WHERE Users_id = ?", [
            BF::ClearCode("image", "str", "post"),
            BF::ClearCode("name", "str", "post"),
            BF::ReturnInfoUser(BF::idUser)
        ]);
    }
    else if($command == "SaveStatusPartner")
    {
        $partner = R::getRow("SELECT * FROM Partners
        INNER JOIN Users ON Users.Users_id = Partners.Partners_user
        WHERE Partners_id = ?", [
            BF::ClearCode("id", "int", "post")
        ]);

        AuxiliaryFn::StylePrint($partner);

        R::exec("UPDATE Partners SET Partners_status = ? WHERE Partners_id = ?", [
            BF::ClearCode("status", "int", "post"),
            BF::ClearCode("id", "int", "post")
        ]);

        if(BF::ClearCode("status", "int", "post") == 1)
        {
            R::exec("UPDATE Users SET Users_permission = 555 WHERE Users_id = ?", [
                $partner["Users_id"]
            ]);
        }
        else
        {
            R::exec("UPDATE Users SET Users_permission = 0 WHERE Users_id = ?", [
                $partner["Users_id"]
            ]);
        }

    }
    else if($command == "BuyOneClick")
    {
        $id = ShopFn::CheckOut("click");

        if($id > 0)
        {
            echo "Спасибо за заказ № " . $id;
        }
        else
        {
            echo "Возникла ошибка при оформлении заказа, свяжитесь с нами!";
        }
    }
}
