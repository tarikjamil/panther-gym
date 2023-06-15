document.addEventListener("DOMContentLoaded", function () {
  var mainSlider = new Splide("#main-slider", {
    type: "fade",
    perPage: 1,
    perMove: 1,
    gap: "0rem",
    breakpoints: {
      991: { perPage: 1, arrows: false },
      767: { perPage: 1, arrows: false },
      479: { perPage: 1, arrows: false }
    }
  }).mount();

  var thumbnails = document.querySelectorAll("#thumbnails img");

  thumbnails.forEach(function (thumbnail) {
    thumbnail.addEventListener("click", function () {
      var index = parseInt(this.getAttribute("data-index"), 10);
      mainSlider.go(index);

      // Remove is--active class from all thumbnails
      thumbnails.forEach(function (otherThumbnail) {
        otherThumbnail.classList.remove("is--active");
      });

      // Add is--active class to the clicked thumbnail
      this.classList.add("is--active");
    });
  });

  var filterOptions = document.querySelectorAll(
    '[aria-label="color"] [data-node-type="add-to-cart-option-pill"]'
  );
  var productImages = document.querySelectorAll(".products-els img");

  filterOptions.forEach(function (filterOption) {
    filterOption.addEventListener("click", function () {
      var selectedColor = this.getAttribute("data-option-name").toLowerCase();
      var firstMatchingThumbnail = null;

      productImages.forEach(function (productImage) {
        var imageAlt = productImage.getAttribute("alt").toLowerCase();
        if (imageAlt.indexOf(selectedColor) === -1) {
          productImage.style.display = "none";
        } else {
          productImage.style.display = "block";

          // Finding the first matching thumbnail
          if (!firstMatchingThumbnail) {
            thumbnails.forEach(function (thumbnail) {
              var thumbnailAlt = thumbnail.getAttribute("alt").toLowerCase();
              if (
                thumbnailAlt.indexOf(selectedColor) !== -1 &&
                !firstMatchingThumbnail
              ) {
                firstMatchingThumbnail = thumbnail;
              }
            });
          }
        }
      });

      // Simulate click on the first matching thumbnail
      if (firstMatchingThumbnail) {
        firstMatchingThumbnail.click();
      }
    });
  });

  // Automatically click the first filter after page loads
  if (filterOptions.length > 0) {
    filterOptions[0].click();
  }
});
