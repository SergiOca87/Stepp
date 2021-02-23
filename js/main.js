/* start main.js */

var j = jQuery.noConflict();

j(document).ready(function($) {
    //Menu
    $(".menu-handle").on("click", function() {
        $(this).toggleClass("active");
        $(".mobile-navigation-menu").toggleClass("active");

        if ($("body").hasClass("home")) {
            $(".site-header__button").toggleClass("active");
        }
    });

    //Go back (under construction pages or 404)
    $(".go-back").on("click", function(e) {
        e.preventDefault();
        window.history.back();
    });

    //Insights Modal
    $(".insights-toggle").on("click", function(e) {
        e.preventDefault();
        $(".modal").toggleClass("active");
    });

    //Close modal if click is outside modal body
    $(".modal").on("click", function(e) {
        if ($(e.target).hasClass("modal", "active")) {
            $(".modal").removeClass("active");
        }
    });

    //Open modal on vocid blog page
    if (
        $("body").hasClass("page-template-covid-blog") &&
        $(".covid-blog").hasClass("popup")
    ) {
        function openModal() {
            $(".modal").toggleClass("active");
        }
        setTimeout(openModal, 3000);
    }

    //IE Fix for Object-fit images on IE
    if (document.documentMode || /Edge/.test(navigator.userAgent)) {
        jQuery(".object-fit-img-wrap img").each(function() {
            var t = jQuery(this),
                s = "url(" + t.attr("src") + ")",
                p = t.parent(),
                d = jQuery("<div></div>");

            p.append(d);
            d.css({
                height: t.parent().css("height"),
                "background-size": "cover",
                "background-repeat": "no-repeat",
                "background-position": "50% 20%",
                "background-image": s,
            });
            t.hide();
        });
    }

    //Loader - Properties
    $(window).on("load", function() {
        $(".lds-ring").fadeOut("slow");
    });

    // function removeRing(){
    // 	$('.lds-ring').fadeOut('slow');
    //   }
    // setTimeout(removeRing, 3000);

    //Active menu Item on Properties Page
    if (
        $("body").hasClass("post-type-archive-property") ||
        $("body").hasClass("property-template-default")
    ) {
        $(".menu-item-825").addClass("current-menu-item");
    } else if ($("body").hasClass("single-team")) {
        $(".menu-item-76").addClass("current-menu-item");
    }

    $(".testimonials__slider").slick({
        arrows: true,
        infinite: true,
        speed: 1000,
        prevArrow: $(".testimonials__slider__prev"),
        nextArrow: $(".testimonials__slider__next"),
    });

    $(".homepage__hero__slider").slick({
        arrows: true,
        dots: true,
        fade: true,
        speed: 1500,
        autoplay: true,
        autoplaySpeed: 5000,
        appendDots: ".homepage__hero__slider__dots",
        prevArrow: $(".slick-prev"),
        nextArrow: $(".slick-next"),
    });

    AOS.init();

    var base_url = window.location.origin;

    function filterNews(id) {
        //clicked element to have an active class

        //Get the category name based on id
        var $category;

        //For each of the id numbers return a category name
        switch (id) {
            case "=63":
                $category = "#awards";
                break;
            case "=61":
                $category = "#headliness";
                break;
            case "=59":
                $category = "#multifamily blog";
                break;
            case "=62":
                $category = "#sales long beach";
                break;
            case "=60":
                $category = "#sales los angeles";
                break;
            case "=64":
                $category = "#sales santa monica";
                break;
            default:
                $category = "";
        }

        $(".news__items__content").html('<div class="spinner"></div>'); //load and render JSON

        $.getJSON(
            base_url +
                "/wp-json/wp/v2/posts/?categories" +
                id +
                "&per_page=100",
            function(posts) {
                //Loop through each of the filtered posts

                posts.forEach(function(item) {
                    // var markup = `
                    // 	<div class="cell large-4 medium-6" data-aos="fade-up">
                    // 		<div class="news__item">
                    // 			<div class="news__item__image__wrap mb-md">
                    // 				<img src="${item.images.medium}">
                    // 			</div>
                    // 			<div class="news__item__top">
                    // 				<span class="news__item__date">${ moment( item.date ).format('MMMM Do, YYYY') }</span>
                    // 				<span class="news__item__category">
                    // 					${$category}
                    // 				</span>
                    // 			</div>
                    // 			<div class="news__item__body">
                    // 				<a href="${item.link}"><h3 class="news__item__title blue mt-sm mb-sm">${item.title.rendered}</h3></a>
                    // 				<div class="news__item__text mb-sm">${item.excerpt.rendered}</div>
                    // 				<a href="${item.link}" class="news__item__link">
                    // 					<img src="http://demo.inmotionrealestate.com/stepp/wp-content/themes/stepp_theme/assets/img/arrow-black.svg" alt="Link arrow">
                    // 				</a>
                    // 			</div>
                    // 		</div>
                    // 	</div>`

                    var markup = '\n\t\t\t<div class="cell large-4 medium-6" data-aos="fade-up">\n\t\t\t\t<div class="news__item">\n\t\t\t\t\t<div class="news__item__image__wrap mb-md">\n\t\t\t\t\t\t<img src="'
                        .concat(
                            item.images.medium,
                            '">\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class="news__item__top">\n\t\t\t\t\t\t<span class="news__item__date">'
                        )
                        .concat(
                            moment(item.date).format("MMMM Do, YYYY"),
                            '</span>\n\t\t\t\t\t\t<span class="news__item__category">\n\t\t\t\t\t\t\t'
                        )
                        .concat(
                            $category,
                            '\n\t\t\t\t\t\t</span>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class="news__item__body">\n\t\t\t\t\t\t<a href="'
                        )
                        .concat(
                            item.link,
                            '"><h3 class="news__item__title blue mt-sm mb-sm">'
                        )
                        .concat(
                            item.title.rendered,
                            '</h3></a>\n\t\t\t\t\t\t<div class="news__item__text mb-sm">'
                        )
                        .concat(
                            item.excerpt.rendered,
                            '</div>\n\t\t\t\t\t\t<a href="'
                        )
                        .concat(
                            item.link,
                            '" class="news__item__link">\n\t\t\t\t\t\t\t<img src="http://steppcommercial.com/wp-content/themes/stepp_theme/assets/img/arrow-black.svg" alt="Link arrow">\n\t\t\t\t\t\t</a>\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t</div>'
                        );

                    //$('.news__items__content .spinner').remove();
                    $(".news__items__content .spinner").remove();
                    //Append each result
                    $(".news__items__content").append(markup);
                });
            }
        );
    }

    //cat item on click, pass the class and use that to filter
    //Can DRY
    $(".news__category__list").on("click", function(e) {
        e.preventDefault();
        var target = e.target;

        var el;

        if ($(target).hasClass("cat-item")) {
            var el = target;

            $(el)
                .siblings()
                .removeClass("active");
            $(el).addClass("active");

            var secondClass = $(el)
                .attr("class")
                .split(" ")[1];
            var parts = secondClass.split("-");
            var id = parts.pop();

            filterNews("=" + id);
        } else if (
            $(target)
                .parent()
                .hasClass("cat-item")
        ) {
            var el = $(target).parent();

            $(el)
                .siblings()
                .removeClass("active");
            $(el).addClass("active");

            var secondClass = $(el)
                .attr("class")
                .split(" ")[1];
            var parts = secondClass.split("-");
            var id = parts.pop();

            filterNews("=" + id);
        } else if ($(target).hasClass("cat-item-all")) {
            /*
			$(target).siblings().removeClass('active');
			$(target).addClass('active');
			filterNews("/posts");
			*/
            location.reload(true);
        }
    });

    //Remove contact card border
    $(".contact__card")
        .last()
        .css("borderBottom", "none");

    // Single Property testimonials__slider $('.slider-for').slick({
    $(".slider-for").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: ".slider-nav",
    });
    $(".slider-nav").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: ".slider-for",
        dots: true,
        centerMode: true,
        focusOnSelect: true,
    });
});

/* end main.js */
