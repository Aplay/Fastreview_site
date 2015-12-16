$(document).ready(function() {
var ANDMAG = {};
  
    ANDMAG.screenPixelRatio = function () {
        var retVal = 1;
        if (window.devicePixelRatio) {
            retVal = window.devicePixelRatio;
        } else if ("matchMedia" in window && window.matchMedia) {
            if (window.matchMedia("(min-resolution: 2dppx)").matches || window.matchMedia("(min-resolution: 192dpi)").matches) {
                retVal = 2;
            } else if (window.matchMedia("(min-resolution: 1.5dppx)").matches || window.matchMedia("(min-resolution: 144dpi)").matches) {
                retVal = 1.5;
            }
        }
        return retVal;
    }
    ANDMAG.getImageVersion = function( ) {
        var pixelRatio = ANDMAG.screenPixelRatio();    
        var width = window.innerWidth * pixelRatio;

        if (width > 768 && width <= 1280) {
            return "sm"; // 300x300
        } else if (width > 1280 && width <= 1600) {
            return "md"; // 400x400
        } else if (width > 1600 && width <= 1920) {
            return "lg"; // 500x500
        } else {
            return "def"; // default version 800x800
        }
    }
    ANDMAG.getBgVersion = function( ) {
        var pixelRatio = ANDMAG.screenPixelRatio();    
        var width = window.innerWidth * pixelRatio;

        if (width <= 480) {
            return "160";
        } else if (width > 480 && width <= 768) {
            return "300";
        } else if (width > 768 && width <= 1024) {
            return "500";
        } else if (width > 1024 && width <= 1280) {
            return "640"; 
        } else if (width > 1280 && width <= 1600) {
            return "800"; 
        } else if (width > 1600 && width <= 1920) {
            return "1000"; 
        } else {
            return "1000"; 
        }
    }
    ANDMAG.lazyloadImage = function( imageContainer ) {
      var imageVersion = ANDMAG.getBgVersion();

        if (!imageContainer || !imageContainer.children) {
            return;
        }
        var img = imageContainer.children[0];

        if (img) {
            var imgSRC = img.getAttribute("data-src-" + imageVersion);
            var altTxt = img.getAttribute("data-alt");

            if (imgSRC) {
                try {
                    var imageElement = new Image();
                    imageElement.src = imgSRC;
                    imageElement.setAttribute("class", "picture img-responsive");
                    imageElement.setAttribute("alt", altTxt ? altTxt : "");
                    imageElement.setAttribute("title", altTxt ? altTxt : "");
                    imageContainer.appendChild(imageElement);
                    $(imageContainer).parent('a.lazy-load-src').attr('href', imgSRC);
                } catch (e) {
                    console.log("img error" + e);
                }
                imageContainer.removeChild(imageContainer.children[0]);
            }
        }
    }
    ANDMAG.lazyloadBg = function( imageContainer ) {
      var imageVersion = ANDMAG.getBgVersion();

        if (!imageContainer || !imageContainer.children) {
            return;
        }
        var img = imageContainer.children[0];

        if (img) {
            var imgSRC = img.getAttribute("data-src-" + imageVersion);
            var altTxt = img.getAttribute("data-alt");
            if (imgSRC) {
                try {
                    $(imageContainer).append('<div class="item-gallery-bg" style="background-image:url('+ imgSRC +'); "></div><img class="undergal" alt="'+ altTxt +'" src="'+ imgSRC +'">');
                    $(imageContainer).next('a.lazy-load-src').attr('href', imgSRC);
                } catch (e) {
                    console.log("img error" + e);
                }
                $(imageContainer).children('noscript')[0].remove();
            }
        }
    }
    ANDMAG.startImagesLoad = function( options ) { 
       var lazyLoadedImages = document.getElementsByClassName("lazy-load-field");

        for (var i = 0; i < lazyLoadedImages.length; i++) {
            ANDMAG.lazyloadImage(lazyLoadedImages[i]);
        }
        

        var lazyLoadedBg = document.getElementsByClassName("lazy-load-bg");

        for (var i = 0; i < lazyLoadedBg.length; i++) {
            ANDMAG.lazyloadBg(lazyLoadedBg[i]);
        }
    }

    ANDMAG.startImagesLoad();
})


 