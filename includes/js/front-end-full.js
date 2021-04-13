function ftgallerycopy(id)
{
    try
    {
        var ftgallerycopyText = document.getElementById(id);
        ftgallerycopyText.select();
        ftgallerycopyText.setSelectionRange(0, 99999); /*For mobile devices*/
        document.execCommand('ftgallerycopy');

        jQuery('#'+ id).parent().find('.ftg-share-text').hide();
        jQuery('#'+ id).parent().find('.ftg-text-copied').show();

    }
    catch(e)
    {
        alert(e);
    }
};

jQuery(window).on("load",function() {

    if (ftgPremiumOption.enable_right_click == 'true'){
        jQuery(document).bind("contextmenu", function (event) {
            event.preventDefault();
        });
        // window.ondragstart = function() { return false; }
        jQuery(document).ready(function () {
            jQuery('img').on('dragstart', function (event) {
                event.preventDefault();
            });
        });
    }

    jQuery('.ft-wp-gallery-masonry .ft-gallery-variations-text select').on('change', function() {
        // Additional JavaScript
        jQuery('.ft-wp-gallery-masonry').masonry('reloadItems');
        setTimeout(function () {
            // Do something after 3 seconds
            jQuery('.ft-wp-gallery-masonry').masonry('layout');
        }, 200);
    });


    jQuery.fn.masonry&&jQuery(".masonry").hasClass("ft-wp-gallery-masonry")&&(jQuery(".ft-wp-gallery-masonry").masonry(),setTimeout(function(){jQuery(".ft-wp-gallery-masonry").masonry("reloadItems"),jQuery(".ft-wp-gallery-masonry").masonry("layout")},600))}),






    jQuery(document).ready(



        function(){
            jQuery.fn.ftsShare=function(){
                jQuery(".fts-share-wrap").each(function(){
                    var r=jQuery(this);
                    r.find(".ft-gallery-link-popup").unbind().bind("click",function(){
                        r.find(".ft-gallery-share-wrap").toggle()
                    })
                })
            },
            jQuery.fn.ftsShare&&jQuery.fn.ftsShare()});

// https://www.w3schools.com/js/js_comparisons.asp
// >	greater than   x > 8	true
// <	less than      x < 8	true
// https://www.slickremix.com/betablog/2017/09/20200/
jQuery(document).ready(slickremixFTGalleryImageResizing);
jQuery(window).on('resize',slickremixFTGalleryImageResizing);

function slickremixFTGalleryImageResizing() {


    setTimeout(function () {
        // Do something after 3 seconds
        jQuery('.ft-gallery-download').removeClass('lightbox-added');
    }, 200);


    // This is the container for our instagram images
    var ftsBlockCenteredAttr = jQuery('.ft-wp-gallery-centered');
    // This is the container for the instagram image post
    var ftsImageSize = jQuery('.slicker-ft-gallery-placeholder');
    // How many colums do we want to show
    var ftsInstagramColumns = ftsBlockCenteredAttr.attr('data-ftg-columns');
    // The margin in between photos so we can subtract that from the total %
    var ftsInstagramMargin = ftsBlockCenteredAttr.attr('data-ftg-margin');
    // The margin without the px and we multiply it by 2 because the margin is on the left and right
    var ftsInstagramMarginfinal = parseFloat(ftsInstagramMargin) * 2;
    // Get the Instagram container .width() so we can keep track of the container size
    var ftsContainerWidth = ftsBlockCenteredAttr.width() ;
    // Force columns so the images to not scale up from 376px-736px.
    // This keeps the aspect ratio for the columns and is in the if statements below where you will see ftsContainerWidth <= '376' && ftsForceColumns === 'no' and ftsContainerWidth <= '736' && ftsForceColumns === 'no'
    var ftsForceColumns = ftsBlockCenteredAttr.attr('data-ftg-force-columns');
    // we or each option so if someone tries something other than that it will go to else statement
    if(ftsInstagramColumns === '1' ||
        ftsInstagramColumns === '2' ||
        ftsInstagramColumns === '3' ||
        ftsInstagramColumns === '4' ||
        ftsInstagramColumns === '5' ||
        ftsInstagramColumns === '6' ||
        ftsInstagramColumns === '7' ||
        ftsInstagramColumns === '8') {
        // if the container is 376px or less we force the image size to be 100%
        if (ftsContainerWidth <= '376' && ftsForceColumns === 'no') {
            var og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
        }
        // if the container is 736px or less we force the image size to be 50%
        else if (ftsContainerWidth <= '736' && ftsForceColumns === 'no') {
            var og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
        }
        else {
            if (ftsInstagramColumns === '8') {
                var og_size = 'calc(12.5% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '7') {
                var og_size = 'calc(14.28571428571429% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '6') {
                var og_size = 'calc(16.66666666666667% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '5') {
                var og_size = 'calc(20% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '4') {
                var og_size = 'calc(25% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '3') {
                var og_size = 'calc(33.33333333333333% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '2') {
                var og_size = 'calc(50% - ' + ftsInstagramMarginfinal + 'px)';
            }
            else if (ftsInstagramColumns === '1') {
                var og_size = 'calc(100% - ' + ftsInstagramMarginfinal + 'px)';
            }
        }

        ftsImageSize.css({'width': og_size});
        var ftsImageHeight = ftsImageSize.width();
        ftsImageSize.css({
            'width': og_size,
            'height': ftsImageHeight,
            'margin': ftsInstagramMargin
        });
        return false;
    }
    else {
        var ftsImageWidth = ftsBlockCenteredAttr.attr('data-ftg-width')  ? ftsBlockCenteredAttr.attr('data-ftg-width') : '325px';
        // alert(ftsInstagramMargin)
        ftsImageSize.css({
            'width': ftsImageWidth,
            'height': ftsImageWidth,
            'margin': ftsInstagramMargin
        });
        return false;
    }

}