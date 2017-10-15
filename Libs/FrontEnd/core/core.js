ajaxDir = "/requests/ajax?command=";
buffer = "";

$(document).ready(function()
{
    $('[data-toggle="popover"]').popover();

    $('[data-toggle="tooltip"]').tooltip();

    $('.blog-menu-button').on('click', function () {
        $('.blog-menu-slide').slideToggle('fast');
    });

    $(".add-prop").click(function () {
        input = $("<input>");

        input.attr("class", "design-input");
        input.attr("placeholder", "Название");

        idParent = $(this).attr("data-id");

        bootbox.dialog({
            size: "small",
            title: "Добавить новое свойство",
            message: input,
            buttons:
                {
                    success:
                        {
                            label: "Добавить",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Dispatcher",
                                    method: "post",
                                    data: {
                                        "query": "AddNewPropChild",
                                        "idParent": idParent,
                                        "name": input.val()
                                    },
                                    success: function ()
                                    {
                                        location.reload();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".add-prop-parent").click(function () {
        input = $("<input>");

        input.attr("class", "design-input");
        input.attr("placeholder", "Название");

        bootbox.dialog({
            size: "small",
            title: "Добавить новое свойство",
            message: input,
            buttons:
                {
                    success:
                        {
                            label: "Добавить",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Dispatcher",
                                    method: "post",
                                    data: {
                                        "query": "AddPropParent",
                                        "name": input.val()
                                    },
                                    success: function ()
                                    {
                                        location.reload();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".delete-property").click(function () {
        id = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "DeletePropChild",
                "id": id
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $(".mini-bars").mouseenter(function () {
        $(".bg-menu-black").fadeIn(300);
    });

    $(".mini-bars").mouseleave(function () {
        $(".bg-menu-black").fadeOut(300);
    });

    $(".menu-home").mouseenter(function () {
        $(".bg-menu-black").fadeIn(300);
    });

    $(".menu-home").mouseleave(function () {
        $(".bg-menu-black").fadeOut(300);
    });

    $(".click-menu").find("strong").click(function () {
        $(this).parents(".menu-top").toggleClass("s-hide");
    });

    $(".div-prop").click(function () {
        $(this).parents(".parent-prop").find(".div-prop").removeClass("active");
        $(this).addClass("active");

        if($(this).find(".text-prop").attr("data-img") != "")
        {
            $(".poduct-p-image-preview").find("img").attr("src", $(this).find(".text-prop").attr("data-img"));
        }
    });

    $(".zoom-click").click(function () {
        div = $("<div>");
        div.attr("class", "zoom-img");
        div.attr("data-id", $(this).attr("data-id"));

        divParentImg = $("<div>");
        divParentImg.attr("class", "parent-img");

        closeButton = $("<button>");
        closeButton.attr("class", "close-button delete-from-cart clear-button");
        closeButton.html('<i class="fa fa-angle-right" aria-hidden="true"></i><i class="fa fa-angle-left" aria-hidden="true"></i>');

        closeButton.click(function () {
            $(".zoom-img").removeClass("open");
            setTimeout(function () {
                $(".zoom-img").remove();
            }, 300);
        });

        leftButton = $("<button>");
        leftButton.attr("class", "left-button clear-button");
        leftButton.html('<i class="fa fa-angle-left" aria-hidden="true"></i>');

        leftButton.click(function () {
            thisId = parseInt($(".zoom-img").attr("data-id")) - 1;

            if ($(".zoom-click[data-id='" + thisId + "']").length > 0)
            {
                $(".parent-img").find("img").addClass("anim-l");

                setTimeout(function () {
                    $(".parent-img").find("img").removeClass("anim-l");
                    $(".zoom-img").attr("data-id", thisId);
                    $(".parent-img").find("img").attr("src", $(".zoom-click[data-id='" + thisId + "']").find("img").attr("src"));
                }, 300);

            }
        });

        rightButton = $("<button>");
        rightButton.attr("class", "right-button clear-button");
        rightButton.html('<i class="fa fa-angle-right" aria-hidden="true"></i>');

        text = $("<span>");
        text.attr("class", "header-text");
        text.html($("h1").html());

        rightButton.click(function () {
            thisId = parseInt($(".zoom-img").attr("data-id")) + 1;

            if ($(".zoom-click[data-id='" + thisId + "']").length > 0)
            {
                $(".parent-img").find("img").addClass("anim");

                setTimeout(function () {
                    $(".parent-img").find("img").removeClass("anim");
                    $(".zoom-img").attr("data-id", thisId);
                    $(".parent-img").find("img").attr("src", $(".zoom-click[data-id='" + thisId + "']").find("img").attr("src"));
                }, 300);
            }
        });

        img = $("<img>");
        img.attr("src", $(this).find("img").attr("src"));

        divParentImg.append(text);
        divParentImg.append(leftButton);
        divParentImg.append(img);
        divParentImg.append(rightButton);
        divParentImg.append(closeButton);

        div.append(divParentImg);

        $("body").append(div);
        setTimeout(function () {
            $(".zoom-img").addClass("open");
        }, 300);
    });

    $(".up-price-category").click(function () {

    });

    $(".heart-button").click(function () {
        parent = $(this);

        idProduct = $(this).attr("data-id");

        query = "AddWish";

        if($(this).hasClass("active"))
        {
            query = "DeleteWish";
        }

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "IfUserInSystem"
            },
            success: function (response)
            {
                if(response == 1)
                {
                    $.ajax({
                        url: ajaxDir + "Dispatcher",
                        method: "post",
                        data: {
                            "query": query,
                            "idProduct": idProduct
                        },
                        success: function (responseWish)
                        {
                            child = $(".one-product").find(".heart-button[data-id='" + idProduct + "']");

                            if(!child.length)
                            {
                                child = parent;
                            }

                            if(query == "AddWish")
                            {
                                child.addClass("active");
                                child.find("i").removeClass("fa-heart-o");
                                child.find("i").addClass("fa-heart");


                            }
                            else
                            {
                                child.removeClass("active");
                                child.find("i").removeClass("fa-heart");
                                child.find("i").addClass("fa-heart-o");
                            }

                            wishInfo = JSON.parse(responseWish);

                            // $(".one-product").find(".heart-button[data-id='" + idProduct + "']").removeClass("active");
                            // $(".one-product").find(".heart-button[data-id='" + idProduct + "']").find("i").removeClass("fa-heart");
                            // $(".one-product").find(".heart-button[data-id='" + idProduct + "']").find("i").addClass("fa-heart-o");

                            $(".top-div-wish").find(".drop-down-top").removeClass("false");
                            $(".top-div-wish").find(".drop-down-top").removeClass("active");
                            $(".top-div-wish").find(".drop-down-top").addClass(wishInfo.class);

                            $(".top-div-wish").find("button").find(".count").removeClass("false");
                            $(".top-div-wish").find("button").find(".count").removeClass("active");
                            $(".top-div-wish").find("button").find(".count").addClass(wishInfo.class);

                            $(".top-div-wish").find("button").find(".count").html(wishInfo.count);
                            $(".top-div-wish").find(".drop-down-top").html(wishInfo.html);
                        }
                    });

                    return;
                }

                PrintStyle("Сначала войдите, чтобы добавить в мои желания!", "bad");
            }
        });
    });

    // $(".menu-li").hover(function () {
    //     $(".img-preview-menu").find("img").attr("src", $(this).attr("data-img"));
    // });

    $('#main-slider').owlCarousel({
        margin:10,
        loop:true,
        autoPlay: 4000,
        autoWidth:true,
        items:1,
        pagination: true,
        nav: true,
        dots: true
    });

    $('.product-list-images').owlCarousel({
        margin:10,
        loop:true,
        items:4,
        pagination: true,
        nav: true,
        dots: true
    });

    $(".checkout-confirm").click(function () {
        CheckOut();
    });

    $(".clear-cart").click(function () {
        ClearCart();
    });

    $(".buy-in-click").click(function () {
        $(".modal-design-bg").fadeIn(100);
        $(".modal-design").addClass("open-m");
    });

    $(".buyinclick").click(function () {
        name = $(".one-click-name").val();
        phone = $(".one-click-phone").val();
        city = $(".one-click-city").val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            data: {
                "query": "BuyOneClick",
                "name": name,
                "phone": phone,
                "city": city
            },
            method: "post",
            success: function (response)
            {
                PrintStyle(response);
                // location.href = "/user/orders";
            }
        });
    });

    $(".close-modal-design").click(function () {
        $(this).parents(".modal-design").removeClass("open-m");
        $(".modal-design-bg").fadeOut(300);
    });

    $(".add-characteristic").click(function () {
        // subcategory = $("div[data-key='ojansd23asd']").find(".slct-num").html();
        subcategory = $(this).attr("data-id");
        name = $(".name-characteristic ").val();

        if(typeof(subcategory) == 'undefined' || subcategory == 0)
        {
            return;
        }

        if(name == "")
        {
            alert("Заполните все поля!");

            return;
        }

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "AddCharacteristic",
                "idCategory": subcategory,
                "name": name
            },
            success: function ()
            {
                // alert("Добавлено!");

                location.reload();

                $(".name-characteristic").val("")
            }
        });
    });

    $(".register-button").click(function () {
        surname = $(".register-surname").val();
        name = $(".register-name").val();
        patronymic = $(".register-patronymic").val();
        birth = $(".register-birth").val();
        gender = $(".register-gender").val();
        email = $(".register-email").val();
        phone = $(".register-phone").val();
        login = $(".register-login").val();
        password = $(".register-password").val();
        repeatPassword = $(".register-repeat-password").val();

        checkEmail = email.indexOf("@") + 1;

        if(password != repeatPassword)
        {
            alert("Пароли не совпадают!");
        }
        else
        {
            if(checkEmail && surname != "" && name != "" && patronymic != "" && phone != "" && login != "" && password != "")
            {
                $.ajax({
                    url: ajaxDir + "Dispatcher",
                    method: "post",
                    data: {
                        "query": "RegisterUser",
                        "Surname": surname,
                        "Name": name,
                        "Patronymic": patronymic,
                        "Birth": birth,
                        "Gender": gender,
                        "Email": email,
                        "Phone": phone,
                        "Login": login,
                        "Password": password
                    },
                    success: function (response)
                    {
                        dataResponse = JSON.parse(response);

                        if(dataResponse.status == 1 && dataResponse.cart == 0)
                        {
                            setTimeout(function () {
                                LogInSystem(login, password, "/user/information")
                            }, 1500);
                        }
                        else if(dataResponse.status == 1 && dataResponse.cart == 1)
                        {
                            setTimeout(function () {
                                LogInSystem(login, password, "/checkout/")
                            }, 1500);
                        }
                    }
                });
            }
            else
            {
                alert("Заполните все поля!");
            }
        }
    });

    $(".update-register-button").click(function () {
        idUser = $(".idUser").val();
        surname = $(".register-surname").val();
        name = $(".register-name").val();
        patronymic = $(".register-patronymic").val();
        birth = $(".register-birth").val();
        gender = $(".register-gender").val();
        email = $(".register-email").val();
        phone = $(".register-phone").val();

        checkEmail = email.indexOf("@") + 1;

        if(checkEmail && surname != "" && name != "" && patronymic != "" && phone != "")
        {
            $.ajax({
                url: ajaxDir + "Dispatcher",
                method: "post",
                data: {
                    "query": "UpdateInfoUser",
                    "Surname": surname,
                    "Name": name,
                    "Patronymic": patronymic,
                    "Birth": birth,
                    "Gender": gender,
                    "Email": email,
                    "Phone": phone,
                    "IdUser": idUser
                },
                success: function ()
                {
                    location.reload();
                }
            });
        }
        else
        {
            alert("Заполните все поля!");
        }
    });

    $(".update-info-user-button").click(function () {
        idUser = $(".idUser").val();
        login = $(".register-login").val();
        password = $(".register-password").val();
        repeatPassword = $(".register-repeat-password").val();

        if(password != repeatPassword)
        {
            alert("Пароли не совпадают!");
        }
        else
        {
            if(login != "" && password != "")
            {
                $.ajax({
                    url: ajaxDir + "Dispatcher",
                    method: "post",
                    data: {
                        "query": "UpdateAccountInfoUser",
                        "login": login,
                        "password": password,
                        "idUser": idUser

                    },
                    success: function ()
                    {
                        location.reload();
                    }
                });
            }
            else
            {
                alert("Заполните все поля!");
            }
        }

    });

    $(".add-address").click(function () {
        divParent = $("<div>");

        countryInput = $("<input>");
        countryInput.attr("class", "design-input margin-bottom");
        countryInput.attr("placeholder", "Страна");

        regionInput = $("<input>");
        regionInput.attr("class", "design-input margin-bottom");
        regionInput.attr("placeholder", "Область");

        cityInput = $("<input>");
        cityInput.attr("class", "design-input margin-bottom");
        cityInput.attr("placeholder", "Город");

        streetInput = $("<input>");
        streetInput.attr("class", "design-input margin-bottom");
        streetInput.attr("placeholder", "Улица");

        buildInput = $("<input>");
        buildInput.attr("class", "design-input margin-bottom");
        buildInput.attr("placeholder", "Номер дома");

        porchInput = $("<input>");
        porchInput.attr("class", "design-input margin-bottom");
        porchInput.attr("placeholder", "Подъезд");

        apartmentInput = $("<input>");
        apartmentInput.attr("class", "design-input margin-bottom");
        apartmentInput.attr("placeholder", "Квартира");

        divParent.append(countryInput);
        divParent.append(regionInput);
        divParent.append(cityInput);
        divParent.append(streetInput);
        divParent.append(buildInput);
        divParent.append(porchInput);
        divParent.append(apartmentInput);

        bootbox.dialog({
            size: "small",
            title: "Добавление нового адреса доставки",
            message: divParent,
            buttons:
                {
                    success:
                        {
                            label: "Добавить адрес",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Dispatcher",
                                    method: "post",
                                    data: {
                                        "query": "AddAddress",
                                        "countryInput": countryInput.val(),
                                        "regionInput": regionInput.val(),
                                        "cityInput": cityInput.val(),
                                        "streetInput": streetInput.val(),
                                        "buildInput": buildInput.val(),
                                        "porchInput": porchInput.val(),
                                        "apartmentInput": apartmentInput.val()
                                    },
                                    success: function ()
                                    {
                                        location.reload();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".delete-address").click(function () {
        idAddress = $(this).attr("data-id");

        bootbox.dialog({
            size: "small",
            title: "Удаление адреса",
            message: " ",
            buttons:
                {
                    success:
                        {
                            label: "Да, удалить",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Dispatcher",
                                    method: "post",
                                    data: {
                                        "query": "DeleteAddress",
                                        "idAddress": idAddress
                                    },
                                    success: function ()
                                    {
                                        location.reload();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".add-product").click(function () {
        formData = new FormData();

        imagePreview = $(".product-preview");

        formData.append("query", "AddProduct");
        formData.append("name", $(".product-name").val());
        formData.append("description", $(".product-description").val());
        formData.append("price", $(".prod-price").val());
        formData.append("purchasePrice", $(".product-purchase-price").val());
        formData.append("lastPrice", $(".product-lastPrice").val());
        formData.append("category", $(".select-category-char-3").find(".slct-num").html());
        formData.append("seoDesc", $(".product-seo-desc").val());
        formData.append("seoKeywords", $(".product-seo-keywords").val());
        formData.append("status", $(".product-status").val());
        formData.append("type", $(".product-type").val());
        formData.append("code", $(".product-vendor-code").val());
        formData.append("weight", $(".product-netto").val());
        formData.append("have", $(".product-have").val());
        formData.append("dimensions", $(".product-dimensions-length").val() + ";" + $(".product-dimensions-width").val() + ";" + $(".product-dimensions-height").val());

        formData.append("imagePreview", $(".product-preview").attr("data-url"));

        /*
            Собираем изображения товара
         */

        listImages = "";
        listWholesale = "";

        $(".product-image-one").each(function (i, e) {
            if($(e).attr("data-url") != "")
            {
                listImages += $(e).attr("data-url") + ";";
            }
        });

        $(".whole-sale-price").each(function (i, e) {
            price = $(e).val();
            count = $(e).parents("tr").find(".whole-sale-count").val();

            listWholesale += price + "*" + count + ";";
        });

        formData.append("wholesale", listWholesale);
        formData.append("images", listImages);

        dataCharacteristic = {};

        $(".list-characteristic").each(function (i, e) {
            idValue = $(e).attr("data-value");

            if($(".characteristic-val-" + idValue).val() == "")
            {
                dataCharacteristic[idValue] = $(".characteristic" + idValue).val();
                formData.append("characteristics[" + idValue + "]", $(".characteristic" + idValue).val());
            }
            else
            {
                dataCharacteristic[idValue] = $(".characteristic-val-" + idValue).val();
                formData.append("characteristicsValue[" + idValue + "]", $(".characteristic-val-" + idValue).val());
            }
        });

        objPropParent = {};

        $(".prop-block").each(function (i, e) {
            idProp = $(e).attr("data-id");
            objProp = {};
            objPropLine = {};

            $(this).find(".checkbox-property").each(function (y, el) {
                if($(el).is(":checked"))
                {
                    objProp = {};

                    idVal = $(el).attr("data-id");
                    picVal = $(el).parents("tr").find(".property-image").attr("data-url");
                    priceVal = $(el).parents("tr").find("price-prop").val();

                    objProp["pic"] = picVal;
                    objProp["price"] = priceVal;

                    objPropLine[idVal] = objProp;
                }
            });

            objPropParent[idProp] = objPropLine;
        });

        formData.append("properties", JSON.stringify(objPropParent));
        console.log(objPropParent);

        console.log(formData);

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            success: function ()
            {
                PrintStyle("Продукт успешно добавлен!");
                // location.reload();
            }
        });
    });

    $(".edit-product").click(function () {
        formData = new FormData();

        imagePreview = $(".product-preview");

        formData.append("query", "EditProduct");
        formData.append("name", $(".product-name").val());
        formData.append("description", $(".product-description").val());
        formData.append("price", $(".product-price").val());
        formData.append("purchasePrice", $(".product-purchase-price").val());
        formData.append("lastPrice", $(".product-lastPrice").val());
        formData.append("category", $(".product-category").val());
        formData.append("published", $(".product-published").val());
        formData.append("idProduct", $(".product-id").val());
        formData.append("seoDesc", $(".product-seo-desc").val());
        formData.append("seoKeywords", $(".product-seo-keywords").val());
        formData.append("imagePreview", $(".product-preview").attr("data-url"));

        listImages = "";

        $(".product-image-one").each(function (i, e) {
            if($(e).attr("data-url") != "")
            {
                listImages += $(e).attr("data-url") + ";";
            }
        });

        formData.append("images", listImages);

        // dataCharacteristic = {};
        //
        // $(".list-characteristic").each(function (i, e) {
        //     idValue = $(e).attr("data-value");
        //
        //     dataCharacteristic[idValue] = $(".characteristic" + idValue).val();
        //
        //     formData.append("characteristics[" + idValue + "]", $(".characteristic" + idValue).val());
        // });

        formData.append("imagesCount", $(".list-files").length);

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            success: function ()
            {
                location.reload();
            }
        });
    });

    $(".save-category").click(function () {
        id = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "UpdateCategory",
                "id": id,
                "name": $(".edit-root-category" + id).val(),
                "image": $(".cat-img-" + id).attr("data-url")
            },
            success: function ()
            {
                PrintStyle("Успешно сохранено!");
                // location.reload();
            }
        });
    });

    $(".save-one-order").click(function () {
        idOrder = $(this).attr("data-id");
        idStatus = $(".select-status").val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "SaveOrderAdmin",
                "idOrder": idOrder,
                "idStatus": idStatus
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $(".delete-category").click(function () {
        id = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "DeleteCategory",
                "id": id
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $('body').on('click', '.delete-subcategory', function () {
        id = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "DeleteSubCategory",
                "id": id
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $('body').on('click', '.save-subcategory', function () {
        id = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "UpdateSubCategory",
                "id": id,
                "text": $(".edit-sub-category" + id).val()
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $('body').on('click', '.add-sub-category', function () {
        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "AddSubCategory",
                "text": $(".text-sub-category").val(),
                "root": $(".category-root").val()
            },
            success: function (response)
            {
                location.reload();
            }
        });
    });

    $('body').on('change', '.subCategoryPaste > .subCategory', function () {
        category = $(this).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "GetSchemaValuesTable",
                "category": category
            },
            success: function (response)
            {
                $(".charListPaste").html(response);
            }
        });
    });

    $('body').on('click', '.save-schema', function () {
        idSchema = $(this).attr("data-id");
        nameSchema = $(".edit-val-schema" + $(this).attr("data-id")).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "SaveSchema",
                "idSchema": idSchema,
                "name": nameSchema
            },
            success: function ()
            {
                // alert("Сохранено!");
                location.reload();
                // $(".charListPaste").html(response);
            }
        });
    });

    $('body').on('click', '.delete-schema', function () {
        idSchema = $(this).attr("data-id");
        tr = $(this).parents("tr");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "DeleteSchema",
                "idSchema": idSchema
            },
            success: function ()
            {
                // tr.remove();
                location.reload();
            }
        });
    });

    $('body').on('click', '.save-schema-val', function () {
        idSchema = $(this).attr("data-id");
        nameSchema = $(".edit-val-s-schema" + idSchema).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "SaveSchemaVal",
                "idSchemaVal": idSchema,
                "name": nameSchema
            },
            success: function ()
            {
                // alert("Сохранено!");
                location.reload();
            }
        });
    });

    $('body').on('click', '.delete-schema-val', function () {
        idSchema = $(this).attr("data-id");
        tr = $(this).parents("tr");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "DeleteSchemaVal",
                "idSchemaVal": idSchema
            },
            success: function ()
            {
                // tr.remove();
                location.reload();
            }
        });
    });

    // $('body').on('change', '.CategoryNewPaste > .subCategory', function () {
    //     category = $(this).val();
    //
    //     $.ajax({
    //         url: ajaxDir + "Dispatcher",
    //         type: "POST",
    //         method: "post",
    //         data: {
    //             "query": "GetSchemaValues",
    //             "category": category
    //         },
    //         success: function (response)
    //         {
    //             $(".SchemeNewPaste").html(response);
    //         }
    //     });
    //
    // });
    $(".select-category-char-2").click(function () {
        SweaneModal($(this), function (id, name) {
            $.ajax({
                url: ajaxDir + "Dispatcher",
                type: "POST",
                method: "post",
                data: {
                    "query": "GetSchemaValues",
                    "category": id
                },
                success: function (response)
                {
                    $(".SchemeNewPaste").html(response);
                }
            });
        });
    });

    $(".select-category-char-3").click(function () {
        SweaneModal($(this), function (id, name) {
            $.ajax({
                url: ajaxDir + "Dispatcher",
                type: "POST",
                method: "post",
                data: {
                    "query": "GetCharacteristicsInCategory",
                    "category": id
                },
                success: function (response)
                {
                    $(".characteristicTable").html(response);
                }
            });
        });

    });

    $('body').on('change', '.SchemeNewPaste > .characteristics-schema', function () {
        idSchema = $(this).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "GetSchemaValuesForEdit",
                "idSchemaValue": idSchema
            },
            success: function (response)
            {
                $(".charListValPaste").html(response);
            }
        });
    });

    $('body').on('change', '.checkCharacteristic .subCategory', function () {
        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "GetCharacteristicsInCategory",
                "category": $(this).val()
            },
            success: function (response)
            {
                $(".characteristicTable").html(response);
            }
        });
    });

    $(".add-characteristic-value").click(function () {

        valueC = $(".value-characteristic").val();
        schemaC = $(this).attr("data-id");

        if(valueC == "" || schemaC == 0)
        {
            alert("Проверьте данные!");

            return;
        }

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "AddSchemaValue",
                "value": valueC,
                "schema": schemaC
            },
            success: function (response)
            {
                // alert("Добавлено!");
                location.reload();
                // $(".value-characteristic").val("")
            }
        });
    });

    $(".add-root-category").click(function () {
        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "AddCategory",
                "text": $(".text-root-category").val()
            },
            success: function (response)
            {
                location.reload();
            }
        });
    });

    $(".rootCategoryNew").change(function () {
        rootCategory = $(this).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "GetSubCategory",
                "rootCategory": rootCategory
            },
            success: function (response)
            {
                $(".CategoryNewPaste").html(response);
            }
        });
    });

    $(".rootCategory").change(function () {
        rootCategory = $(this).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "GetSubCategory",
                "rootCategory": rootCategory
            },
            success: function (response)
            {
                $(".subCategoryPaste").html(response);
            }
        });
    });

    $(".category-root").change(function () {
        rootCategory = $(this).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "GetSubCategoryTable",
                "rootCategory": rootCategory
            },
            success: function (response)
            {
                $(".subCategoryPaste").html(response);
            }
        });
    });

    $(".delete-product").click(function () {
        idProduct = $(this).attr("data-id");

        tr = $(this).parents("tr");

        bootbox.dialog({
            size: "small",
            title: "Хотите удалить товар?",
            message: " ",
            buttons:
                {
                    success:
                        {
                            label: "Да, удалить",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Dispatcher",
                                    type: "POST",
                                    method: "post",
                                    data: {
                                        "query": "DeleteProduct",
                                        "idProduct": idProduct
                                    },
                                    success: function ()
                                    {
                                        PrintStyle("Товар успешно удален!");
                                        tr.remove();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".login-show").click(function () {
        $(".drop-user").toggleClass("active");
    });

    $(".btn-login").click(function () {
        LogInSystem();
    });

    $(".quit-user").click(function () {
        QuitFromSystem();
    });

    $(".carrot-radio").click(function () {
        $(this).toggleClass("active");
    });

    $(".filter-search").click(function () {
        listVar = [];

        priceStart = $(".price-start").val();
        priceEnd = $(".price-end").val();

        $(".task-one-list").each(function (i, e) {
            dataId = $(this).attr("data-id");

            if($(this).find("strong").hasClass("active"))
            {
                listVar.push(dataId);
            }
        });

        $("#listVar").val(listVar);
        $("#listPrice").val(priceStart + "," + priceEnd);

        $("#form").submit();
    });

    $(".list-sort").change(function () {
        $("#sortProducts").val($(this).val());

        $("#form").submit();
    });

    $(".list-count").change(function () {
        $("#listCount").val($(this).val());

        $("#form").submit();
    });

    $(".view-list").click(function () {
        $("#view").val("list");

        $("#form").submit();
    });

    onePixel = $(".go-button").attr("data-value") / $(".go-button").width();

    if($(".go-button").length > 0)
    {
        $(".go-button").find(".left").mousedown(function () {
            my_Y = event.pageY - $(this).position().top;
            my_X = event.pageX - $(this).position().left;

            widthScroll = $(this).parent().width();
            priceAll = $(this).parent().attr("data-value");

            parent = $(this);

            $("body").mousemove(function () {
                parent.css("left", (event.pageX - my_X) + "px");
                // parent.css("top", (event.pageY - my_Y) + "px");

                if(parent.position().left < 0)
                {
                    parent.css("left", "0px");
                }
                else if(parent.position().left > (parent.parent().width() - 20))
                {
                    parent.css("left", (parent.parent().width() - 20) + "px");
                }

                if(parent.position().left > ($(".go-button").find(".right").position().left - 20))
                {
                    parent.css("left", ($(".go-button").find(".right").position().left - 20) + "px");
                }

                price = (parent.position().left) * onePixel + parseInt($(".go-button").find(".left").attr("data-value"));

                $(".price-start").val(parseInt(price));

                $(".go-button").find("i").css("left", ($(".go-button").find(".left").position().left + 20) + "px");
            });
        });

        // onePixel = $(".go-button").attr("data-value") / $(".go-button").width();
        onePixelRight = $(".go-button").attr("data-value") / ($(".go-button").width() - 20);

        left = ($(".price-start").val() - $(".go-button").find(".left").attr("data-value")) / onePixel;

        $(".go-button").find(".left").css("left", left + "px");
        // $(".go-button").find(".left").css("left", (($(".go-button").find(".left").attr("data-value") - $(".price-start").val()) * onePixel) + "px");
        // $(".go-button").find(".left").css("left", ($(".price-start").val() * $(".go-button").width() / $(".go-button").attr("data-value")) + "px");
        // $(".go-button").find(".right").css("right", "initial");
        // console.log(onePixel);
        // console.log((($(".go-button").find(".right").attr("data-value") - $(".price-end").val())));
        // console.log((($(".go-button").find(".right").attr("data-value") - $(".price-end").val()) * onePixel));
        // console.log(parseInt($(".go-button").attr("data-value") / 2) );
        // pol = parseInt($(".go-button").attr("data-value") / 2);
        // console.log(parseInt($(".go-button").find(".right").attr("data-value")) + pol - $(".price-end").val());

        right = ($(".go-button").find(".right").attr("data-value") - $(".price-end").val()) / onePixel - 1;


        $(".go-button").find(".right").css("right", right + "px");
        // $(".go-button").find(".right").css("right", ((parseInt($(".go-button").find(".right").attr("data-value")) - pol - $(".price-end").val()) * onePixelRight) + "px");
        // $(".go-button").find(".right").css("left", ($(".price-end").val() * ($(".go-button").width() - 20) / $(".go-button").attr("data-value")) + "px");

        $(".go-button").find("i").css("left", ($(".go-button").find(".left").position().left + 20) + "px");
        $(".go-button").find("i").css("right", ($(".go-button").width() - $(".go-button").find(".right").position().left) + "px");

        $("body").mouseup(function (e) {
            $(document).off('mousemove');
            $('body').off('mousemove');
            $("body").unbind('mousemove');
        });

        $(".price-start").change(function () {
            widthScroll = $(".go-button").width();
            priceAll = $(".go-button").attr("data-value");

            x = (widthScroll * $(this).val()) / priceAll;

            $(".go-button").find(".left").css("left", x + "px");
        });

        $(".go-button").find(".right").mousedown(function (e) {
            my_X = event.pageX - $(this).position().left;

            widthScroll = $(this).parent().width();
            priceAll = $(this).parent().attr("data-value");

            parent = $(this);

            $("body").mousemove(function () {
                parent.css("right", "initial");
                parent.css("left", (event.pageX - my_X) + "px");

                if(parent.position().left < 0)
                {
                    parent.css("left", "0px");
                }
                else if(parent.position().left > (parent.parent().width() - 20))
                {
                    parent.css("left", (parent.parent().width() - 20) + "px");
                }

                if(parent.position().left < ($(".go-button").find(".left").position().left + 20))
                {
                    parent.css("left", ($(".go-button").find(".left").position().left + 20) + "px");
                }

                // price = parent.position().left * priceAll / (widthScroll - 20);
                //
                // $(".price-end").val(parseInt(price - 130));

                price = (parent.position().left) * onePixelRight + parseFloat($(".go-button").find(".left").attr("data-value"));

                console.log(onePixel);

                // if(price == (parseFloat($(".go-button").find(".right").attr("data-value")) - onePixel))
                // {
                //     price = $(".go-button").find(".right").attr("data-value");
                // }

                $(".price-end").val(parseInt(price));

                $(".go-button").find("i").css("right", ($(".go-button").width() - $(".go-button").find(".right").position().left) + "px");
            });
        });
    }

    $(".view-block").click(function () {
        $("#view").val("block");

        $("#form").submit();
    });

    $(".add-banner").click(function () {
        $(".table tbody").find("tr:last-child").clone().appendTo(".table");
        $(".table tbody").find("tr:last-child").find(".save-banner").attr("data-id", "0");
    });

    $(".selected-filter").click(function () {
        listVar = [];
        dataId = parseInt($(this).attr("data-id"));

        $(".selected-filter").each(function (i, e) {
            id = parseInt($(e).attr("data-id"));

            if(dataId != id)
            {
                listVar.push(id);
            }
        });

        $("#listVar").val(listVar);

        $("#form").submit();
    });

    $(".clear-filter").click(function () {
        listVar = [];

        $("#listVar").val(listVar);

        $("#form").submit();
    });

    inCard = [];



    $(".select-prop-parent").find("li").click(function () {
        dataId = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "GetPropertiesFromId",
                "id": dataId
            },
            success: function (response)
            {
                $(".propTable").html(response);
                $(".propTable").attr("data-id", dataId);
            }
        });
    });

    $(".prop-block").find(".list-div").find("button").click(function () {
        console.log("asd");
        $(this).parents(".prop-block").find(".table-div").slideToggle();
    });

    $(".d-top-left-menu").find(".open-menu").click(function () {
        $("body").toggleClass("vis");

        classBody = '';

        if($("body").hasClass("vis"))
        {
            classBody = "vis";
        }

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "MenuCookie",
                "class": classBody
            },
            success: function ()
            {

            }
        });
    });

    $(".tab-manager").find("li").click(function () {
        $(".tab-manager").find("li").removeClass("active");

        $(this).addClass("active");

        tab = $(this).attr("data-class");

        $(".one-tab").fadeOut(100);
        $(".tab-" + tab).fadeIn(100);
    });

    $(".check-file").click(function () {
        url = $(".file-one.active").attr("data-dir");

        buffer.find("button").attr("data-url", url);
        buffer.find("img").attr("src", url);

        $(".modal-file").removeClass("active");
        $(".modal-file-bg").fadeOut(300);
    });

    $(".close-file-m").click(function () {
        $(".modal-file").removeClass("active");
        $(".modal-file-bg").fadeOut(300);
    });

    // $(".file-one-dir").click(function () {
    //     dir = $(this).attr("data-dir") + "/";
    //
    //     $.ajax({
    //         url: ajaxDir + "Dispatcher",
    //         method: "post",
    //         data: {
    //             "query": "GetFilesFromDir",
    //             "dir": dir
    //         },
    //         success: function (response)
    //         {
    //             $(".modal-file").html(response);
    //         }
    //     });
    // });

    $(".save-cart-change").click(function () {
        listVar = [];

        formData = new FormData();

        $(".cart-one-change").each(function (i, e) {
            countProduct = $(e).val();
            idProduct = $(e).attr("data-id");

            formData.append("products[" + idProduct + "]", countProduct);
        });

        formData.append("query", "SaveChangedCart");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            success: function ()
            {
                location.reload();
            }
        });
    });

    $(".head-strong").click(function () {
        $(".head-strong").removeClass("active");

        $(this).addClass("active");

        $(".div-b").removeClass("vis");

        $("." + $(this).attr("data-class")).addClass("vis");
    });

    $(".review-product").click(function () {
        text = $(this).parents(".one-product").find(".review-text").val();
        idProduct = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "AddReview",
                "text": text,
                "idProduct": idProduct
            },
            success: function ()
            {
                alert("Спасибо за оставленный отзыв!");
            }
        });
    });

    $(".balance-button").click(function () {
        parent = $(this);

        idProduct = $(this).attr("data-id");

        query = "AddToBalance";

        if($(this).hasClass("active"))
        {
            query = "DeleteBalance";
        }

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": query,
                "idProduct": idProduct
            },
            success: function (response)
            {
                // location.reload(response);

                child = $(".one-product").find(".balance-button[data-id='" + idProduct + "']");

                if(!child.length)
                {
                    child = parent;
                }

                if(query == "AddToBalance")
                {
                    child.addClass("active");
                }
                else
                {
                    child.removeClass("active");
                }

                balanceInfo = JSON.parse(response);

                $(".top-div-balance").find(".drop-down-top").removeClass("false");
                $(".top-div-balance").find(".drop-down-top").removeClass("active");
                $(".top-div-balance").find(".drop-down-top").addClass(balanceInfo.class);

                $(".top-div-balance").find("button").find(".count").removeClass("false");
                $(".top-div-balance").find("button").find(".count").removeClass("active");
                $(".top-div-balance").find("button").find(".count").addClass(balanceInfo.class);

                $(".top-div-balance").find("button").find(".count").html(balanceInfo.count);
                $(".top-div-balance").find(".drop-down-top").html(balanceInfo.html);
            }
        });
    });




    // var divPos = {};
    // var header = ".header";
    // var headerWidth = $(header).width() / 2;
    // var headerHeight = $(header).height() / 2;
    // var offset = $(header).offset();
    //
    // $(header).mousemove(function (e) {
    //     divPos = {
    //         left: e.pageX - offset.left,
    //         top: e.pageY - offset.top,
    //         leftDel: (e.pageX - offset.left) / 100,
    //         topDel: (e.pageY - offset.top) / 100
    //     };
    //
    //     console.log(headerWidth + " " + divPos.left);
    //
    //     if(divPos.left < headerWidth && divPos.top < headerHeight)
    //     {
    //         console.log(1);
    //         leftDiv = divPos.leftDel;
    //         topDiv = divPos.topDel;
    //     }
    //     else if(divPos.left > headerWidth && divPos.top < headerHeight)
    //     {
    //         console.log(2);
    //         leftDiv = -divPos.leftDel;
    //         topDiv = divPos.topDel;
    //     }
    //     else if(divPos.left < headerWidth && divPos.top > headerHeight)
    //     {
    //         console.log(3);
    //         leftDiv = divPos.leftDel;
    //         topDiv = -divPos.topDel;
    //     }
    //     else
    //     {
    //         console.log(4);
    //         leftDiv = -divPos.leftDel;
    //         topDiv = -divPos.topDel;
    //     }
    //
    //     $(header).css("transform", "translate(" + leftDiv + "px, " + topDiv + "px) scale(1.2)");
    //
    // });
    //
    // $(header).mouseleave(function (e) {
    //     $(header).css("transform", "translate(0px, 0px) scale(1)");
    // });

    $(".head-menu").find("button").click(function () {
        $(this).parent().find("ul").css("width", ($(window).width() - 30) + "px");
        $(this).parent().find("ul").toggle();
    });

    $(".switch-language").find("a").click(function () {

        getLang = $(this).attr("data-lang");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "SetCookie",
                "value": getLang
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $(".form-request").find("button").click(function () {

        thisStep = parseInt($(".form-request").attr("data-step"));
        step = thisStep;

        if($(this).attr("data-path") == "next" && thisStep < 4)
        {
            if($(".form-request div[class='step-" + thisStep + "']").find(".need").val() != "")
            {
                $(".form-request div[class^='step']").css("display", "none");
                step = thisStep + 1;
                $(".form-request div[class='step-" + step + "']").css("display", "block");
            }
            else
            {
                $(".form-request div[class='step-" + thisStep + "']").find(".need").addClass("need-dismiss");
            }
        }
        else if($(this).attr("data-path") == "back" && thisStep > 1)
        {
            if($(".form-request div[class='step-" + thisStep + "']").find(".need").val() != "")
            {
                $(".form-request div[class^='step']").css("display", "none");
                step = thisStep - 1;
                $(".form-request div[class='step-" + step + "']").css("display", "block");
            }
        }

        $(".form-request").attr("data-step", step);
    });

    $(".send-request-btn").click(function () {
        alert();
    });

    $(".callback-phone").click(function () {
        input = $("<input>");
        input.attr("class", "design-input margin-bottom");
        input.attr("placeholder", "Ваше имя");

        inputPhone = $("<input>");
        inputPhone.attr("class", "design-input margin-bottom");
        inputPhone.attr("placeholder", "Номер телефона");

        inputComment = $("<textarea>");
        inputComment.attr("class", "design-textarea");
        inputComment.attr("placeholder", "Сообщение");

        div = $("<div>");

        div.append(input);
        div.append(inputPhone);
        div.append(inputComment);

        bootbox.dialog({
            size: "small",
            title: "Обратный звонок",
            message: div,
            buttons:
                {
                    success:
                        {
                            label: "Перезвонить мне",
                            className: "btn btn-info",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Dispatcher",
                                    method: "post",
                                    data: {
                                        "query": "SendMail",
                                        "name": input.val(),
                                        "phone": inputPhone.val(),
                                        "message": inputComment.val()
                                    },
                                    success: function ()
                                    {
                                        alert("Спасибо за оставленную заявку! Мы с Вам скоро свяжемся!");
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".set-executor").click(function () {
        taskId = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "ListUsers"
            },
            success: function (select)
            {
                bootbox.dialog({
                    size: "small",
                    title: "Хотите начать работу над этим заданием?",
                    message: select,
                    buttons:
                        {
                            success:
                                {
                                    label: "Да, начать работу",
                                    className: "button button-small button-white",
                                    callback: function()
                                    {
                                        $.ajax({
                                            url: ajaxDir + "Tasks",
                                            method: "post",
                                            data: {
                                                "query": "SetUserOnTask",
                                                "taskId": taskId,
                                                "userId": $("#UsersSelect").val()
                                            },
                                            success: function ()
                                            {
                                                location.reload();
                                            }
                                        });
                                    }
                                }
                        }
                });
            }
        });

    });

    $(".add-sub-category-btn").click(function () {
        parent = $(this).attr("data-id");

        div = $("<div>");

        input = $("<input>");
        input.attr("class", "design-input");
        input.attr("style", "width: 100%");
        input.attr("placeholder", "Название подкатегории");

        div.append(input);

        bootbox.dialog({
            size: "small",
            title: "Добавление подкатегории",
            message: div,
            buttons:
                {
                    success:
                        {
                            label: "Добавить",
                            className: "btn btn-info",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Dispatcher",
                                    method: "post",
                                    data: {
                                        "query": "AddSubCategory",
                                        "parent": parent,
                                        "name": input.val()
                                    },
                                    success: function ()
                                    {
                                        location.reload();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".modal-start-task").click(function () {
        taskId = $(".data-task-id").val();

        div = $("<div>");
        div.text("Каждое задание - это как шаги, шаги к завершению проекта. " +
            "А как нам всем известно, завершение проекта - это всегда деньги!");

        bootbox.dialog({
            size: "small",
            title: "Хотите начать работу над этим заданием?",
            message: div,
            buttons:
                {
                    success:
                        {
                            label: "Да, начать работу",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Tasks",
                                    method: "post",
                                    data: {
                                        "query": "start",
                                        "taskId": taskId
                                    },
                                    success: function ()
                                    {
                                        location.reload();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".modal-stop-task").click(function () {
        taskId = $(".data-task-id").val();

        div = $("<div>");
        div.text("Отличная работа, до новых встреч!");

        bootbox.dialog({
            size: "small",
            title: "Вы все проверили?",
            message: div,
            buttons:
                {
                    success:
                        {
                            label: "Да, завершить задание",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Tasks",
                                    method: "post",
                                    data: {
                                        "query": "stop",
                                        "taskId": taskId
                                    },
                                    success: function ()
                                    {
                                        location.href = "/dashboard/tasks";
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".modal-new-task").click(function () {
        orderId = $(".data-order-id").val();

        text = $("<textarea>");

        text.attr("class", "textarea-sweane");
        text.attr("placeholder", "Текст задания");

        div = $("<div>");

        div.append(text);

        bootbox.dialog({
            size: "small",
            title: "Создание нового задания",
            message: div,
            buttons:
                {
                    success:
                        {
                            label: "Создать",
                            className: "button button-small button-white",
                            callback: function()
                            {
                                $.ajax({
                                    url: ajaxDir + "Tasks",
                                    method: "post",
                                    data: {
                                        "query": "add",
                                        "orderId": orderId,
                                        "text": text.val()
                                    },
                                    success: function ()
                                    {
                                        location.reload();
                                    }
                                });
                            }
                        }
                }
        });
    });

    $(".sign-in").click(function () {
        logInSystem();
    });

    $(".password").keydown(function (e) {
        if(e.keyCode == 13)
        {
            logInSystem();
        }
    });

    $(".quit-from-dashboard").click(function () {
        QuitFromSystem();
    });

    $(".search-button").click(function()
    {
        $(".home-search").toggleClass("scale");
    });
    $(".search-article-button").click(function () {
        query = $(this).parent().find("input").val();

        SearchInTable(query);
    });
    $(".remove-search").click(function()
    {
        $(".home-search").toggleClass("scale");
    });
    // $(".login-button").click(function()
    // {
    //     img = $("<img>");
    //     img.attr("src", "/Images/Home/user-modal.svg");
    //     img.attr("class", "user-logo");
    //
    //     btnLogin = $("<input>");
    //     btnLogin.attr("class", "login");
    //     btnLogin.attr("placeholder", "Логин");
    //
    //     btnPassword = $("<input>");
    //     btnPassword.attr("class", "password");
    //     btnPassword.attr("placeholder", "Пароль");
    //     btnPassword.attr("type", "password");
    //
    //     divModal = $("<div>");
    //     divModal.attr("class", "carrot-login");
    //     divModal.append(img);
    //     divModal.append(btnLogin);
    //     divModal.append(btnPassword);
    //
    //     result = "asd";
    //     bootbox.dialog({
    //         size: "small",
    //         title: "Вход в систему",
    //         message: divModal,
    //         buttons:
    //             {
    //                 success:
    //                     {
    //                         label: "Войти",
    //                         className: "button button-small button-white",
    //                         callback: function()
    //                         {
    //                             logInSystem();
    //                         }
    //                     }
    //             }
    //     });
    // });

    $("body").on("click", ".list-category-click", function () {
        category = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "GetChildCategory",
                "category": category,
                "rule": $(this).attr("data-rule")
            },
            success: function (response)
            {
                $(".bootbox-body").html(response);
            }
        });
    });

    $(".select-category-char").click(function () {
        SweaneModal($(this), function (id, name) {
            $.ajax({
                url: ajaxDir + "Dispatcher",
                type: "POST",
                method: "post",
                data: {
                    "query": "GetSchemaValuesTable",
                    "category": id
                },
                success: function (response)
                {
                    $(".charListPaste").html(response);
                }
            });
        });
    });

    $("#upload-file-image").change(function () {
        var file_data = $(this).prop('files')[0];

        var form_data = new FormData();

        dir = $("#upload-file-image").attr("data-dir");

        form_data.append('query', "UploadFile");
        form_data.append('file', file_data);
        form_data.append('dir', dir);

        // alert(form_data);

        $.ajax({
            url: ajaxDir + "Dispatcher", // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(){
                // $(".body-file").html(GetFileFromDir(dir));
                GetFileFromDir(dir, function (response) {
                    $(".body-file").html(response);
                });
            }
        });
    });

    $(".delete-file-image").click(function () {
        dir = $("#upload-file-image").attr("data-dir");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "DeleteFile",
                "dir": $(".file-one.active").attr("data-dir")
            },
            success: function ()
            {
                GetFileFromDir(dir, function (response) {
                    $(".body-file").html(response);
                });
            }
        });
    });

    $(".create-folder").click(function () {
        $(".menu-file-under").toggle();
    });

    $(".menu-file-under").find("button").click(function () {
        nameFolder = $(".name-folder").val();
        dir = $("#upload-file-image").attr("data-dir");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            type: "POST",
            method: "post",
            data: {
                "query": "CreateFolder",
                "dir": dir,
                "name": nameFolder
            },
            success: function ()
            {
                GetFileFromDir(dir, function (response) {
                    $(".body-file").html(response);
                });
            }
        });
    });

    Sweane.Placeholder.Init(".design-input");

    $(window).scroll(function ()
    {
        if(returnPositionScreen() > 100)
        {
            $(".go-top-button").addClass("active");
        }
        else
        {
            $(".go-top-button").removeClass("active");
        }
    });

    $(".go-top-button").click(function () {
        $(".go-top-button").removeClass("active");
        $("body").animate({scrollTop:0}, '500', 'swing');
    });



    $(".buttons-parent").find(".top-button").click(function () {
        parent = $(this);

        if(!$(this).parent().hasClass("active"))
        {
            $(".buttons-parent").find(".top-button").parent().removeClass("active");
            // $(".login-show").removeClass("active");
            // $(".drop-user").removeClass("active");
            $(this).parent().addClass("active");

            // if($(".login-show").hasClass("active"))
            // {
            //
            // }

            if(!parent.hasClass("login-show") && $(".drop-user").hasClass("active"))
            {
                $(".drop-user").removeClass("active");
            }
        }
        else
        {
            $(".buttons-parent").find(".top-button").parent().removeClass("active");
        }



        // if(!$(this).hasClass("active") && $(this).hasClass("login-show"))
        // {
        //     $(".top-button").parent().removeClass("active");
        //     $(this).addClass("active");
        // }
        // else if($(this).hasClass("active") && $(this).hasClass("login-show"))
        // {
        //     $(".top-button").parent().removeClass("active");
        //     $(this).removeClass("active");
        // }
    });
    //
    // $("div").click(function () {
    //     console.log($(this).parents(".buttons-parent").length);
    //     console.log($(".buttons-parent").find("span[data-toggle='tooltip']").hasClass("active"));
    //
    //     if($(this).parents(".buttons-parent").length == 0 && $(".buttons-parent").find("span[data-toggle='tooltip']").hasClass("active"))
    //     {
    //         $(".buttons-parent").find("span").removeClass("active");
    //         $(".top-button").removeClass("active");
    //         $(".drop-down-top").removeClass("active");
    //     }
    // });

    $(".search-button").click(function () {
        input = $(".input-search").val();
        path = $(".input-search").attr("data-path");

        // $.ajax({
        //     url: ajaxDir + "Dispatcher",
        //     type: "POST",
        //     method: "post",
        //     data: {
        //         "query": "Search",
        //         "input": input,
        //         "path": path
        //     },
        //     success: function (response)
        //     {
        //         $(".result-search").html(response);
        //     }
        // });
    });

    $(".drop-menu").click(function () {
        $(this).find("ul").slideToggle();
    });

    $(".d-left-menu").css("height", ($(window).height() - 61) + "px");

    $(".add-wholesale").click(function () {
        trHtml = $(".base-wholesale").html();

        tr = $("<tr>");

        tr.append(trHtml);

        $(this).parents("tbody").prepend(tr);
    });

    $("body").on("click", ".remove-wholesale", function () {
        $(this).parents("tr").remove();
    });

    $(".change-currency").find("li").click(function () {
        id = $(this).find("a").attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "ChangeCurrency",
                "id": id
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $(".save-currency").click(function () {
        id = $(this).attr("data-id");

        name = $(this).parents("tr").find(".name-currency").val();
        code = $(this).parents("tr").find(".code-currency").val();
        left = $(this).parents("tr").find(".left-currency").val();
        right = $(this).parents("tr").find(".right-currency").val();
        value = $(this).parents("tr").find(".value-currency").val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "EditCurrency",
                "id": id,
                "name": name,
                "code": code,
                "left": left,
                "right": right,
                "value": value
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    $(".save-currency").click(function () {
        id = $(this).attr("data-id");

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "DeleteCurrency",
                "id": id
            },
            success: function ()
            {
                location.reload();
            }
        });
    });

    // $("*[data-toggle='tooltip']").each(function (i, e) {
    //     span = $("<span>");
    //
    //     span.text($(e).attr("title"));
    //     span.attr("class", "text-title");
    //
    //     $(e).addClass("tooltip-carrot");
    //
    //     $(e).append(span);
    // });

    $("*[data-toggle='tooltip']").each(function (i, e) {
        parent = $(e);

        $(e).mouseleave(function () {
            parent.attr("aria-describedby", "");
            $(".tooltip").remove();
        });
    });
    
    // $(".one-product").mouseenter(function () {
    //     parent = $(this);
    //
    //     parent.addClass("active");
    //
    //     setTimeout(function () {
    //         parent.removeClass("active");
    //     }, 500);
    // });

    $(".send-partner-query").click(function () {
        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "SendPartnerQuery"
            },
            success: function ()
            {
                // location.reload();
            }
        });
    });

    $(".save-settings-shop").click(function () {
        image = $(".image").attr("data-url");
        name = $(".name").val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "SaveSettingsShop",
                "name": name,
                "image": image
            },
            success: function ()
            {
                PrintStyle("Успешно сохранено!");
            }
        });
    });
    
    $(".status-partner").change(function () {
        id = $(this).parents("td").find("input").val();

        status = $(this).val();

        $.ajax({
            url: ajaxDir + "Dispatcher",
            method: "post",
            data: {
                "query": "SaveStatusPartner",
                "id": id,
                "status": status
            },
            success: function ()
            {
                PrintStyle("Успешно сохранено!");
            }
        });
    });

});

function returnPositionScreen() {
    return positionWindow = $(window).scrollTop();
}

// function checkElementOnScreen(element)
// {
//     newElement = $("body").find(element).not(".i-see");
//
//     positionWindow = $(window).scrollTop() + 400;
//
//     newElement.each(function (i, e) {
//         if(positionWindow >= $(e).attr("data-offset"))
//         {
//             $(e).addClass("i-see");
//         }
//     });
// }

function SweaneModal(element, callback) {
    // alert(element.attr("data-key"));
    key = element.attr("data-key");

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "GetRootCategory",
            "rule": $(this).attr("data-rule")
        },
        success: function (response)
        {
            bootbox.dialog({
                size: "small",
                title: "Выбор категории",
                message: response,
                buttons:
                    {
                        success:
                            {
                                label: "Выбрать",
                                className: "btn btn-yellow",
                                callback: function(response)
                                {
                                    idData = $(".response-data-id").html();
                                    nameData = $(".response-data-name").html();

                                    $("div[data-key='" + key + "']").find(".slct-num").html(idData);
                                    $("div[data-key='" + key + "']").find(".slct-name").html(nameData);

                                    callback(idData, nameData);
                                    // $.ajax({
                                    //     url: ajaxDir + "Tasks",
                                    //     method: "post",
                                    //     data: {
                                    //         "query": "add",
                                    //         "orderId": orderId,
                                    //         "text": text.val()
                                    //     },
                                    //     success: function ()
                                    //     {
                                    //         location.reload();
                                    //     }
                                    // });
                                }
                            }
                    }
            });
        }
    });
}

$("body").on("click", ".add-to-cart", function () {
// $(".add-to-cart").click(function () {
    idProduct = $(this).attr("data-id");
    listProp = [];

    $(".div-prop").each(function (i, e) {
        if($(e).hasClass("active"))
        {
            listProp.push($(e).attr("data-parent"));
        }
    });

    name = $(this).parent().find("a").find("strong").html();

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "AddToCart",
            "idProduct": idProduct,
            "prop": listProp
        },
        success: function (respone)
        {
            if(name == 'undefined')
            {
                name = $(".product-one-page").find("h1").html();
            }

            PrintStyle("Товар '" + name + "' успешно добавлен в корзину!");

            cartInfo = JSON.parse(respone);

            $(".top-div-cart").find(".drop-down-top").removeClass("false");
            $(".top-div-cart").find(".drop-down-top").removeClass("active");
            $(".top-div-cart").find(".drop-down-top").addClass(cartInfo.class);

            $(".top-div-cart").find("button").find(".count").removeClass("false");
            $(".top-div-cart").find("button").find(".count").removeClass("active");
            $(".top-div-cart").find("button").find(".count").addClass(cartInfo.class);

            $(".top-div-cart").find("button").find(".count").html(cartInfo.count);
            $(".top-div-cart").find(".drop-down-top").html(cartInfo.html);
        }
    });

    parent = $(this);

    setTimeout(function () {
        parent.removeClass("add-to-cart");
        parent.addClass("added-to-cart");

        i = $("<i>");

        i.attr("class", "fa fa-check");
        i.attr("aria-hidden", "true");

        parent.html(i);
        parent.append("В корзине");
    }, 300);
});

$("body").on("click", ".added-to-cart", function () {
    idProduct = $(this).attr("data-id");

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "DeleteFromCart",
            "idProduct": idProduct
        },
        success: function (respone)
        {
            cartInfo = JSON.parse(respone);

            $(".top-div-cart").find(".drop-down-top").removeClass("false");
            $(".top-div-cart").find(".drop-down-top").removeClass("active");
            $(".top-div-cart").find(".drop-down-top").addClass(cartInfo.class);

            $(".top-div-cart").find("button").find(".count").removeClass("false");
            $(".top-div-cart").find("button").find(".count").removeClass("active");
            $(".top-div-cart").find("button").find(".count").addClass(cartInfo.class);

            $(".top-div-cart").find("button").find(".count").html(cartInfo.count);
            $(".top-div-cart").find(".drop-down-top").html(cartInfo.html);
        }
    });

    $(this).addClass("add-to-cart");
    $(this).removeClass("added-to-cart");

    i = $("<i>");

    i.attr("class", "fa fa-shopping-basket");
    i.attr("aria-hidden", "true");

    $(this).html(i);
    $(this).append("В корзину");
});

$("body").on("click", ".view-banner", function () {
    id = $(this).attr("data-id");

    if($(this).find(".fa-eye-slash").length > 0)
    {
        $(this).find("i").removeClass("fa-eye-slash");
        $(this).find("i").addClass("fa-eye");

        view = 1;
    }
    else
    {
        $(this).find("i").removeClass("fa-eye");
        $(this).find("i").addClass("fa-eye-slash");

        view = 0;
    }

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "ViewBanner",
            "id": id,
            "view": view
        },
        success: function ()
        {
            PrintStyle("Успешно сохранен!");
        }
    });
});

$("body").on("click", ".save-banner", function () {
// $(".save-banner").click(function () {
    id = $(this).attr("data-id");

    var tr = $(this).parents("tr");

    image = tr.find(".product-preview").attr("data-url");
    title = tr.find(".title").val();
    url = tr.find(".url").val();
    category = tr.find(".category-partner").val();


    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "SaveBanner",
            "id": id,
            "image": image,
            "title": title,
            "url": url,
            "category": category
        },
        success: function ()
        {
            PrintStyle("Успешно сохранен!");
        }
    });
});

$("body").on("click", ".delete-product-in-cart", function () {
    idProduct = $(this).attr("data-id");

    if($(this).parents("tr").length > 0)
    {
        $(this).parents("tr").remove();
    }

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "DeleteFromCart",
            "idProduct": idProduct
        },
        success: function (respone)
        {
            cartInfo = JSON.parse(respone);

            $(".top-div-cart").find(".drop-down-top").removeClass("false");
            $(".top-div-cart").find(".drop-down-top").removeClass("active");
            $(".top-div-cart").find(".drop-down-top").addClass(cartInfo.class);

            $(".top-div-cart").find("button").find(".count").html(cartInfo.count);
            $(".top-div-cart").find(".drop-down-top").html(cartInfo.html);
        }
    });
});

$("body").on("click", ".delete-product-from-wish", function () {
// $(".delete-product-from-wish").click(function () {
    idProduct = $(this).attr("data-id");

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "DeleteWish",
            "idProduct": idProduct
        },
        success: function (response)
        {
            // location.reload();
            wishInfo = JSON.parse(response);

            $(".one-product").find(".heart-button[data-id='" + idProduct + "']").removeClass("active");
            $(".one-product").find(".heart-button[data-id='" + idProduct + "']").find("i").removeClass("fa-heart");
            $(".one-product").find(".heart-button[data-id='" + idProduct + "']").find("i").addClass("fa-heart-o");

            $(".top-div-wish").find(".drop-down-top").removeClass("false");
            $(".top-div-wish").find(".drop-down-top").removeClass("active");
            $(".top-div-wish").find(".drop-down-top").addClass(wishInfo.class);

            $(".top-div-wish").find("button").find(".count").removeClass("false");
            $(".top-div-wish").find("button").find(".count").removeClass("active");
            $(".top-div-wish").find("button").find(".count").addClass(wishInfo.class);

            $(".top-div-wish").find("button").find(".count").html(wishInfo.count);
            $(".top-div-wish").find(".drop-down-top").html(wishInfo.html);
        }
    });
});

$("body").on("click", ".delete-from-balance", function () {
// $(".delete-from-balance").click(function () {
    idProduct = $(this).attr("data-id");

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "DeleteFromBalance",
            "idProduct": idProduct
        },
        success: function (response)
        {
            balanceInfo = JSON.parse(response);

            $(".top-div-balance").find("button").find(".count").removeClass("false");
            $(".top-div-balance").find("button").find(".count").removeClass("active");
            $(".top-div-balance").find("button").find(".count").addClass(balanceInfo.class);

            $(".top-div-balance").find(".drop-down-top").removeClass("active");
            $(".top-div-balance").find(".drop-down-top").removeClass("false");
            $(".top-div-balance").find(".drop-down-top").addClass(balanceInfo.class);

            $(".top-div-balance").find("button").find(".count").html(balanceInfo.count);
            $(".top-div-balance").find(".drop-down-top").html(balanceInfo.html);
        }
    });
});

$("body").on("click", ".file-one-dir", function () {
    dir = $(this).attr("data-dir") + "/";

    $("#upload-file-image").attr("data-dir", dir);

    GetFileFromDir(dir, function (response) {
        $(".body-file").html(response);
    });
});

$("body").on("click", ".file-one-file", function () {
    $(".file-one").removeClass("active");

    if($(this).hasClass("file-one-file"))
    {
        $(".preview-file").find("img").attr("src", $(this).attr("data-dir"));
    }

    $(this).toggleClass("active");
});

$("body").on("dblclick", ".file-one-file", function () {
    $(".file-one").removeClass("active");

    if($(this).hasClass("file-one-file"))
    {
        $(".preview-file").find("img").attr("src", $(this).attr("data-dir"));
    }

    $(this).toggleClass("active");

    url = $(".file-one.active").attr("data-dir");

    buffer.find("button").attr("data-url", url);
    buffer.find("img").attr("src", url);

    $(".modal-file").removeClass("active");
    $(".modal-file-bg").fadeOut(300);
});

$("body").on("click", ".save-property", function () {
    dataId = $(this).attr("data-id");

    valueProp = $(".edit-property" + dataId).val();
    colorProp = $(".color-property" + dataId).val();

    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "SaveProperty",
            "idProp": dataId,
            "valueProp": valueProp,
            "colorProp": colorProp
        },
        success: function ()
        {
            location.reload();
        }
    });
});

$("body").on("click", ".slct-modal-div-image", function () {
    $(".modal-file").addClass("active");
    $(".modal-file-bg").fadeIn(300);

    buffer = $(this);
});

function GetFileFromDir(dir, callback) {
    $.ajax({
        url: ajaxDir + "Dispatcher",
        method: "post",
        data: {
            "query": "GetFilesFromDir",
            "dir": dir
        },
        success: function (response)
        {
            callback(response);
        }
    });
}

function RedirectTo(link) {
    location.href = link;
}

function SearchInTable(query) {
    $.ajax({
        url: ajaxDir + "searchArticles",
        data: {
            "query": query
        },
        method: "post",
        success: function (result)
        {
            $(".result-search-article").html(result);
        }
    });
}

function CheckOut() {
    name = $(".checkout-name").val();
    delivery = $(".checkout-delivery").val();
    payment = $(".checkout-payment").val();
    comment = $(".checkout-comment").val();

    $.ajax({
        url: ajaxDir + "Dispatcher",
        data: {
            "query": "CheckOut",
            "name": name,
            "delivery": delivery,
            "payment": payment,
            "comment": comment
        },
        method: "post",
        success: function ()
        {
            location.href = "/user/orders";
        }
    });
}

function ClearCart() {
    $.ajax({
        url: ajaxDir + "Dispatcher",
        data: {
            "query": "ClearCart"
        },
        method: "post",
        success: function ()
        {
            location.reload();
        }
    });
}

function QuitFromSystem() {
    $.ajax({
        url: ajaxDir + "Dispatcher",
        data: {
            "query": "QuitFromSystem"
        },
        method: "post",
        success: function ()
        {
            location.reload();
        }
    });
}

function LogInSystem(login, password, goToLink)
{
    if(typeof login == 'undefined' && typeof password == 'undefined')
    {
        login = $(".login-input").val();
        password = $(".password-input").val();
    }

    $.ajax({
        url: ajaxDir + "Dispatcher",
        data: {
            "query": "LoginInSystem",
            "login": login,
            "password": password
        },
        method: "post",
        type: "post",
        success: function (result)
        {
            resultCheckedUser = parseInt(result);

            if(resultCheckedUser == 1)
            {
                if(typeof goToLink == 'undefined')
                {
                    location.reload();

                    return;
                }

                location.href = goToLink;
            }
        }
    });
}

function PrintStyle(text, type) {
    if(!$(".design-alert").length)
    {
        div = $("<div>");

        div.attr("class", "design-alert clearfix " + type);

        span = $("<span>");
        span.html(text);

        div.append(span);

        button = $("<button>");
        button.attr("class", "clear-button");

        button.append('<i class="fa fa-times" aria-hidden="true"></i>');

        button.click(function () {
            div.removeClass("show-alert");

            setTimeout(function() {
                RemoveStyleAlert(div)
                }, 200
            );
        });

        div.append(button);

        $("body").append(div);
    }

    setTimeout(ShowStyleAlert, 200);
}

function ShowStyleAlert() {
    $(".design-alert").addClass("show-alert");
}

function RemoveStyleAlert(div) {
    div.remove();
}

var ReturnCircle = function(classElement)
{
		this.widthParent = $(classElement).parent().width();

		$(classElement).css("height", this.widthParent);

    this.colorLine = "#efefef";
    this.colorPercent = "#555";

    this.setColorLine = function (color) {
        this.colorLine = color;
    };

    this.setColorPercent = function (color) {
        this.colorPercent = color;
    };

    this.draw = function () {
        var el = $(classElement); // get canvas

        var options = {
            percent:  el.attr('data-percent') || 25,
            size: el.attr('data-size') || this.widthParent,
            lineWidth: el.attr('data-line') || 15,
            rotate: el.attr('data-rotate') || 0
        };

        var canvas = document.createElement('canvas');
        var span = document.createElement('span');
        span.textContent = options.percent + '%';

        if (typeof(G_vmlCanvasManager) !== 'undefined') {
            G_vmlCanvasManager.initElement(canvas);
        }

        var ctx = canvas.getContext('2d');
        canvas.width = canvas.height = options.size;

        el.append(span);
        el.append(canvas);

        ctx.translate(options.size / 2, options.size / 2); // change center
        ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI); // rotate -90 deg

        var radius = (options.size - options.lineWidth) / 2;

        var drawCircle = function(color, lineWidth, percent) {
            percent = Math.min(Math.max(0, percent || 1), 1);
            ctx.beginPath();
            ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, false);
            ctx.strokeStyle = color;
            ctx.lineCap = 'round'; // butt, round or square
            ctx.lineWidth = lineWidth
            ctx.stroke();
        };

        drawCircle(this.colorLine, options.lineWidth, 100 / 100);
        drawCircle(this.colorPercent, options.lineWidth, options.percent / 100);
    };
}

var Sweane = {
    Modal: {
        Draw: function (data) {
            divModal = $("<div>");
            divData = $("<div>");

            divModal.attr("class", "sweane-modal");
            divData.attr("class", "sweane-modal-data");

            button = $("<button>");

            button.attr("class", "sweane-modal-close");
            button.append('<i class="fa fa-times" aria-hidden="true"></i>');

            divData.append(data);

            divModal.append(button);
            divModal.append(divData);

            $("body").append(divModal);
        }
    },

    Placeholder: {
        Init: function (element) {
            $(element).each(function (i, e) {
                placeholderText = $(e).attr("data-placeholder");

                placeholderElement = $("<span>");

                placeholderElement.text(placeholderText);
                placeholderElement.attr("class", "input-placeholder");

                $(e).wrap("<div class='shell-input'></div>");

                $(e).parent().append(placeholderElement);

                $(e).parent().click(function () {
                    $(this).find(".input-placeholder").addClass("focus");
                    $(this).find("input").focus();
                });

                $(e).focusin(function () {
                    console.log("sad");
                    $(e).parent().find(".input-placeholder").addClass("focus");
                });

                $(e).focusout(function () {
                    if($(this).val() == "")
                    {
                        $(e).parent().find(".input-placeholder").removeClass("focus");
                    }
                });
            });
        }
    }
}






