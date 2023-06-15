gsap.registerPlugin(ScrollTrigger);

// On Page Load
function pageLoad() {
  let tl = gsap.timeline();

  tl.to(".main-wrapper", {
    opacity: 1,
    ease: "Quint.easeOut",
    duration: 1
  });
  tl.from("[loading=fadeup]", {
    opacity: 0,
    y: "20rem",
    stagger: { each: 0.1, from: "start" },
    ease: "Quint.easeOut",
    duration: 1
  });
  tl.from("[loading=boxinggloves]", {
    opacity: 0,
    y: "50rem",
    x: "-50rem",
    stagger: { each: 0.1, from: "start" },
    ease: "Quint.easeOut",
    duration: 1,
    delay: -1
  });
  tl.from(".navbar", {
    opacity: 0,
    y: "-100%",
    ease: "Quint.easeOut",
    duration: 1,
    delay: -0.5
  });
}
pageLoad();

$(".navbar--menu-link-close").on("click", function () {
  $(".navbar--menu-link").click();
});

$(".account--close-account").on("click", function () {
  $(".account--link.is--mobile").click();
});

$(document).ready(function () {
  var scrollTop = 0;
  $(window).scroll(function () {
    scrollTop = $(window).scrollTop();
    if (scrollTop >= 100) {
      $(".navbar").addClass("is--scrolled");
    } else if (scrollTop < 100) {
      $(".navbar").removeClass("is--scrolled");
    }
  });
});

// accordion --------------------- //
$(".faq--response").css("height", "0px");
$(".faq--question").on("click", function () {
  // Close other accordions when opening new one
  if (!$(this).hasClass("open")) {
    $(".faq--question.open").click();
  }
  // Save the sibling of the toggle we clicked on
  let sibling = $(this).siblings(".faq--response");
  let animationDuration = 500;

  if ($(this).hasClass("open")) {
    // Close the content div if already open
    sibling.animate({ height: "0px" }, animationDuration);
  } else {
    // Open the content div if already closed
    sibling.css("height", "auto");
    let autoHeight = sibling.height();
    sibling.css("height", "0px");
    sibling.animate({ height: autoHeight }, animationDuration, function () {
      sibling.css("height", "auto");
    });
  }
  // Open and close the toggle div
  $(this).toggleClass("open");
});

// Create a function to update the cart count
function updateCartCount() {
  jQuery.ajax({
    url: wc_cart_fragments_params.ajax_url,
    type: "POST",
    data: {
      action: "woocommerce_get_refreshed_fragments"
    },
    success: function (response) {
      jQuery("#cart-count").html(
        response.fragments["div.cart-count"].replace(/<\/?[^>]+(>|$)/g, "")
      );
    }
  });
}

// Call the updateCartCount function when a cart action occurs
jQuery(document).on("added_to_cart removed_from_cart", updateCartCount);

$(".cart--link").on("click", function () {
  $(".cc-compass").click();
});
