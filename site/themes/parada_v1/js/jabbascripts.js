'use strict';
import Modernizr from './vendor/modernizr-custom';
import device from './vendor/device';
import slick from 'slick-carousel';
import Instafeed from 'instafeed.js'
import $ from 'jquery';
import jQuery from 'jquery';

$('.slider').slick({
    arrows: false,
    autoplay: true,
    autoplaySpeed: 4000,
    dots: true,
    draggable: false,
    pauseOnFocus: false,
    pauseOnHover: false
});

// var feed = new Instafeed({
//     get: 'tagged',
//     tagName: 'jhony0311',
//     clientId: 'fc5b9f916b8649c1a79b6131304903ff',
//     accessToken: '177311424.1677ed0.823d8dcdb28144f9bd71f05f8265a9e3'
// });
// feed.run();
