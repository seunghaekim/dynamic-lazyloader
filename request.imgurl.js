var convertSizeByContainer = function(selector){
    try {
        var containerWidth = jQuery(selector).parent().width();
        var ratio = jQuery(selector).width() / jQuery(selector).height();
        var imageHeight = containerWidth / ratio;
    }
    catch(err){
        console.log(err);
        return false;
    }
    return {
        imageWidth: containerWidth,
        imageHeight: imageHeight
    }
}

var imageProcessor = function(images, index){
    if(index >= images.length){
        return true;
    }
    var data = convertSizeByContainer(images[index]);
    if(data == false){
        return imageProcessor(images, index + 1);
    }
    data.url = images[index].getAttribute('src');
    data.action = 'dynamicImageProcessor';
    jQuery.ajax({
        'type': 'post',
        'url': my_ajax_data.url,
        'data': data,
        success: function(response){
            if(response.result){
                images[index].setAttribute('data-src', response.url)
            }
            return imageProcessor(images, index + 1);
        },
        error: function(err){
            console.log(err);
        }
    })
};

(function(img){
    let images = document.querySelectorAll(img);
    imageProcessor(images, 0);
    lazyload(images);
})('.lazyload');
