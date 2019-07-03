function initMap(latitude, longitude) {
    let myLatLng = new google.maps.LatLng(latitude,longitude)

    let mapOptions = {
        zoom: 16,
        center: myLatLng
    }

    let map = new google.maps.Map(document.getElementById('map'), mapOptions)

    let marker = new google.maps.Marker({
        position: myLatLng,
        map: map
    });
}
window.onload= function(){
    initMap(48.8905888,2.4497205)
}
let allServices = document.querySelectorAll('.initMap')
allServices.forEach(function (service) {
    service.addEventListener('click',function(){
        initMap(service.getAttribute('data-latitude'), service.getAttribute('data-longitude'))
        console.log(service)
    })
})