$(document).ready(function () {
    const toggleId = "#header-toggle";
    const navId = "#nav-bar";
    const bodyId = "#body-pd";
    const headerId = "#header";

    const $toggle = $(toggleId),
        $nav = $(navId),
        $bodypd = $(bodyId),
        $headerpd = $(headerId);

    if (localStorage.getItem("sidebarState") === "open") {
        $nav.addClass("show");
        $toggle.addClass("bx-x");
        $bodypd.addClass("body-pd");
        $headerpd.addClass("body-pd");
    }

    $toggle.on("click", function () {
        $nav.toggleClass("show");
        $toggle.toggleClass("bx-x");
        $bodypd.toggleClass("body-pd");
        $headerpd.toggleClass("body-pd");

        localStorage.setItem("sidebarState", $nav.hasClass("show") ? "open" : "closed");
    });

    $("[data-bs-toggle='collapse']").on("click", function () {
        const $submenu = $(this).next(".collapse");
        $(".collapse").not($submenu).collapse("hide"); 
        $submenu.collapse("toggle"); 
    });

    $(".nav_link").on("click", function () {
        $(".nav_link").removeClass("active"); 
        $(this).addClass("active"); 

        const $submenu = $(this).closest(".collapse");
        if ($submenu.length) {
            $submenu.find("a").addClass("active");
        }
    });

  
});
$(document).ready(function () {
    const currentPath = window.location.pathname.split("?")[0].split("#")[0]; 
    const cleanPath =   BASE_URL + currentPath.replace(/^\//, ""); 

    

    $(".item-sidebar").each(function () {
        const linkPath = $(this).attr("href"); 
      
        if (cleanPath === linkPath) {
            $(this).addClass("active"); 

            const $submenu = $(this).closest(".collapse");
            if ($submenu.length) {
                $submenu.addClass("show"); 
                $submenu.prev(".nav_link").addClass("active"); 
            }
        }
    });
});

