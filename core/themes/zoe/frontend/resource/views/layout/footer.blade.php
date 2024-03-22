<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery-1.9.1.min.js"><\/script>')</script>
<script src="http://cdn.leafletjs.com/leaflet-0.5.1/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mobile-detect/1.4.3/mobile-detect.min.js"></script>

<script src="{{ asset('theme/zoe/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('theme/zoe/js/jquery.fitvids.js') }}"></script>
<script src="{{ asset('theme/zoe/js/jquery.sequence-min.js') }}"></script>
<script src="{{ asset('theme/zoe/js/jquery.bxslider.js') }}"></script>
<script src="{{ asset('theme/zoe/js/main-menu.js') }}"></script>
<script src="{{ asset('theme/zoe/js/template.js') }}"></script>

<script>
    (function () {
        var md = new MobileDetect(window.navigator.userAgent);
        var platform;
        function elementInViewport(el) {
            var rect = el.getBoundingClientRect();
            return (
                rect.top    >= 0
                && rect.left   >= 0
                && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
            )
        }
        if (md.tablet()) {
            console.log('tablet');
            platform = 'tablet';
        } else if (md.mobile()) {
            console.log('mobile');
            platform = 'mobile';
        } else {
            console.log('pc');
            platform = 'pc';
        }
        let images = [...document.querySelectorAll('[lazy=load]')];
        let images_scroll = [...document.querySelectorAll('[lazy=scroll]')];
        const interactSettings = {
            root: document.querySelector('body'),
            rootMargin: '0px 0px 200px 0px'
        };
        var GetNameFile = function (str) {
            return str.split('\\').pop().split('/').pop();
        }
        function loadImage(image,cb,i) {
            let src = "";
            let _platform = platform;
            if (_platform === 'mobile') {
                if (image.dataset.hasOwnProperty('mobile')) {
                    src = image.dataset.mobile;
                } else {
                    _platform = 'tablet';
                }
            }
            if (_platform === 'tablet') {
                if (image.dataset.hasOwnProperty('tablet')) {
                    src = image.dataset.tablet;
                } else {
                    _platform = 'pc';
                }
            }
            if (_platform === 'pc') {
                src = image.dataset.src
            }
            image.removeAttribute('lazy');
            image.src = src;
            console.log( image.src );
            image.onload = () => {
                image.classList.add('loaded');
                image.removeAttribute('width');
                image.removeAttribute('height');

                if(cb) cb(i);
            };
        }
        function onIntersection(imageEntites) {
            imageEntites.forEach(image => {
                if (image.isIntersecting) {
                    observer.unobserve(image.target);
                    loadImage( image.target,function () {

                    },0);
                }
            });
        }
        let observer = new IntersectionObserver(onIntersection, interactSettings);
        images.forEach(image => observer.observe(image));
        function processScroll(){

            for (let i = 0; i < images_scroll.length; i++) {
                if(!images_scroll[i].classList.contains('loaded')){
                    if(elementInViewport(images_scroll[i]))  {
                        loadImage(images_scroll[i],function (i) {
                        },i);
                    }
                }
            }
            for (let i = 0; i < images_scroll.length; i++) {
                if(images_scroll[i].classList.contains('loaded')){
                    images_scroll.splice(i, 1);
                }
            }
            if(images_scroll.length === 0){
                removeEventListener('scroll',processScroll);
            }
        }
        processScroll();
        addEventListener('scroll',processScroll);
    });
    (function () {
        var md = new MobileDetect(window.navigator.userAgent);
        var platform;
        if (md.tablet()) {
            console.log('tablet');
            platform = 'tablet';
        } else if (md.mobile()) {
            console.log('mobile');
            platform = 'mobile';
        } else {
            console.log('pc');
            platform = 'pc';
        }
        document.addEventListener("DOMContentLoaded", function() {
            let lazyImages = [].slice.call(document.querySelectorAll('[lazyload=on]'));
            let active = false;
            function CheckLoadImg(lazyImage) {
                if(lazyImage.getAttribute("lazytype") === "load") return true;
                return (
                    lazyImage.getBoundingClientRect().top <= window.innerHeight &&
                    lazyImage.getBoundingClientRect().bottom >= 0) &&
                    getComputedStyle(lazyImage).display !== "none";
            }
            const lazyLoad = function() {
                if (active === false) {
                    active = true;
                    setTimeout(function() {
                        lazyImages.forEach(function(lazyImage) {
                            if (CheckLoadImg(lazyImage)) {
                                let src = "";
                                let _platform = platform;
                                if (_platform === 'mobile') {
                                    if (lazyImage.dataset.hasOwnProperty('mobile')) {
                                        src = lazyImage.dataset.mobile;
                                    } else {
                                        _platform = 'tablet';
                                    }
                                }
                                if (_platform === 'tablet') {
                                    if (lazyImage.dataset.hasOwnProperty('tablet')) {
                                        src = lazyImage.dataset.tablet;
                                    } else {
                                        _platform = 'pc';
                                    }
                                }
                                if (_platform === 'pc') {
                                    src = lazyImage.dataset.src
                                }
                                lazyImage.src =src;

                                if(lazyImage.dataset.hasOwnProperty('lazyImage.dataset')){
                                    lazyImage.srcset = lazyImage.dataset.srcset;
                                }

                                lazyImage.classList.add('loaded');
                                lazyImages = lazyImages.filter(function(image) {
                                    return image !== lazyImage;
                                });
                                lazyImage.onload = () => {
                                    lazyImage.classList.remove('loaded');
                                    lazyImage.removeAttribute('width');
                                    lazyImage.removeAttribute('height');
                                };
                                if (lazyImages.length === 0) {
                                    document.removeEventListener("scroll", lazyLoad);
                                    window.removeEventListener("resize", lazyLoad);
                                    window.removeEventListener("orientationchange", lazyLoad);
                                }
                            }
                        });
                        active = false;
                    }, 1);
                }
            };
            lazyLoad();
            document.addEventListener("scroll", lazyLoad);
            window.addEventListener("resize", lazyLoad);
            window.addEventListener("orientationchange", lazyLoad);
        });
    })();
    // $(function () {
    //    $(document).ready(function () {
    //       $(".edit-text").each(function () {
    //           $(this).hover(
    //               function() {
    //                   $(this).html($('<textarea  style="z-index: 999999;color:#000;width:'+($(this).width())+'px;height: '+($(this).height()*1.8)+'px;">'+($(this).text())+'</textarea>'));
    //               }, function() {
    //                   var val = $( this ).find("textarea").val();
    //                   $( this ).html(val);
    //               }
    //           );
    //       });
    //    });
    // });
</script>