"use strict";

var nav = null;
var map = null;
var ps = null;
var bounds = null;

var latitude = null;
var longitude = null;

var showList = false;
var placeOverlay = new daum.maps.CustomOverlay({zIndex:1});
var contentNode = document.createElement('div');
var markers = [];
var currCategory = '';

var point_x = 0;
var point_y = 0;

var count = 0;
var count2 = 0;

window.onload = function()
{
    requestPosition();
};

function requestPosition()
{
    if (nav == null)
    {
        nav = window.navigator;
    }
    if (nav != null)
    {
        var geoloc = nav.geolocation;
        if (geoloc != null)
        {
            geoloc.getCurrentPosition(successCallback);
        }
        else
        {
            alert("geolocation not supported");
        }
    }
    else
    {
        alert("Navigator not found");
    }
}

function successCallback(position)
{
    count = 0;
    count2 = 0;
    latitude = position.coords.latitude.toFixed(6);
    longitude = position.coords.longitude.toFixed(6);
    var List = document.getElementById("List");
    document.getElementById("total").innerHTML = "";
    while (List.hasChildNodes()){List.removeChild(List.firstChild);}
    removeMarker();
    load_map();
}

function load_map()
{
    var mapContainer = document.getElementById('map'),
        mapOption = {
            center: new daum.maps.LatLng(latitude,longitude),
            level: 3
        };

    map = new daum.maps.Map(mapContainer, mapOption);
    ps = new daum.maps.services.Places(map);

    var mapTypeControl = new daum.maps.MapTypeControl();
    var zoomControl = new daum.maps.ZoomControl();
    var corePosition  = new daum.maps.LatLng(latitude,longitude);
    var marker = new daum.maps.Marker({position: corePosition});

    marker.setMap(map);
    map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);
    map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

    daum.maps.event.addListener(map, 'idle', searchPlaces);
    contentNode.className = 'placeinfo_wrap';

    addEventHandle(contentNode, 'mousedown', daum.maps.event.preventMap);
    addEventHandle(contentNode, 'touchstart', daum.maps.event.preventMap);

    placeOverlay.setContent(contentNode);

    addCategoryClickEvent();
}

function addEventHandle(target, type, callback)
{
    if (target.addEventListener)
    {
        target.addEventListener('category_bg' + type, callback);
    }
    else
    {
        target.attachEvent('category_bg on' + type, callback);
    }
}

function searchPlaces()
{
    if (!currCategory){return;}
    placeOverlay.setMap(null);
    removeMarker();
    ps.categorySearch(currCategory, placesSearchCB, {useMapBounds:true});
}

function placesSearchCB( status, data, pagination )
{
    var List = document.getElementById("List");
    document.getElementById("total").innerHTML = "";
    while (List.hasChildNodes()){List.removeChild(List.firstChild);}

    if (status === daum.maps.services.Status.OK)
    {
        count = data.places.length;
        document.getElementById("total").innerHTML = "Total : " + count + " 개";

        for (var i = 0; i < data.places.length; i ++)
        {
            searchAddressToCoordinate(data.places[i]);
        }
        displayPlaces(data.places);
    }
    else if (status === daum.maps.services.Status.ZERO_RESULT)
    {
        var content = "";
        if (currCategory === "BK9"){content = "은행";}
        else if (currCategory === "FD6"){content = "식당";}
        else if (currCategory === "PM9"){content = "약국";}
        else if (currCategory === "CE7"){content = "카페";}
        else if (currCategory === "CS2"){content = "편의점";}
        var div = document.createElement("div");
        div.innerHTML = "주변에 " + content + " 가(이) 없습니다.";
        $("List").appendChild(div);
    }
    else if (status === daum.maps.services.Status.ERROR)
    {
        alert("GPS ERROR");
    }
}

function displayPlaces(places)
{
    var order = document.getElementById(currCategory).getAttribute('data-order');

    for (var i = 0; i < places.length; i ++)
    {
        var marker = addMarker(new daum.maps.LatLng(places[i].latitude, places[i].longitude), order);
        (function(marker, place) {
            daum.maps.event.addListener(marker, 'click', function() {
                displayPlaceInfo(place);
            });
        })(marker, places[i]);
    }
}

function addMarker(position, order)
{
    var imageSrc = 'http://t1.daumcdn.net/localimg/localimages/07/mapapidoc/places_category.png';
    var imageSize = new daum.maps.Size(27, 28);
    var imgOptions =  {
            spriteSize : new daum.maps.Size(72, 208),
            spriteOrigin : new daum.maps.Point(46, (order*36)),
            offset: new daum.maps.Point(11, 28)
        },
        markerImage = new daum.maps.MarkerImage(imageSrc, imageSize, imgOptions),
            marker = new daum.maps.Marker({
            position: position,
            image: markerImage
        });
    marker.setMap(map);
    markers.push(marker);
    return marker;
}

function removeMarker()
{
    for ( var i = 0; i < markers.length; i++ )
    {
        markers[i].setMap(null);
    }
    markers = [];
}

function displayPlaceInfo (place)
{
    var content = '<div class="placeinfo">' +
                    '   <a class="title" href="' + place.placeUrl + '" target="_blank" title="' + place.title + '">' + place.title + '</a>';
    if (place.newAddress)
    {
        content += '    <span title="' + place.newAddress + '">' + place.newAddress + '</span>' +
                    '  <span class="jibun" title="' + place.address + '">(지번 : ' + place.address + ')</span>';
    }
    else
    {
        content += '    <span title="' + place.address + '">' + place.address + '</span>';
    }
    content += '    <span class="tel">' + place.phone + '</span>' +
                '</div>' +
                '<div class="after"></div>';
    contentNode.innerHTML = content;
    placeOverlay.setPosition(new daum.maps.LatLng(place.latitude, place.longitude));
    placeOverlay.setMap(map);
}

function addCategoryClickEvent()
{
    var category = document.getElementById('category');
    var children = category.children;

    for (var i = 0; i < children.length; i ++)
    {
        children[i].onclick = onClickCategory;
    }
}

function onClickCategory()
{
    var id = this.id;
    var className = this.className;

    var List = document.getElementById("List");
    while (List.hasChildNodes()){List.removeChild(List.firstChild);}

    placeOverlay.setMap(null);

    if (className === 'category_bg on')
    {
        currCategory = '';
        changeCategoryClass();
        removeMarker();
    }
    else
    {
        currCategory = id;
        changeCategoryClass(this);
        searchPlaces();
    }
}

function changeCategoryClass(el)
{
    var category = document.getElementById('category');
    var children = category.children;

    for (var i = 0; i < children.length; i ++ )
    {
        children[i].className = 'category_bg';
    }
    if (el)
    {
        el.className = 'category_bg on';
    }
}

function searchAddressToCoordinate(data)
{
    naver.maps.Service.geocode({
        address: data.address
    }, function(status, response) {
        if (status === naver.maps.Service.Status.ERROR) {
            return ;
        }
        var item = response.result.items[0];
        var point = new naver.maps.Point(item.point.x, item.point.y);

        point_x = point.x;
        point_y = point.y;

        var div = document.createElement("div");
        var list = document.createElement("ul");
        var content1 = document.createElement("li");
        var content2 = document.createElement("li");
        var content3 = document.createElement("li");
        var point = document.createElement("li");

        content1.innerHTML = "이름 : " + data.title;
        content2.innerHTML = "주소 : " + data.address;
        content3.innerHTML =  "전화번호 : " + data.phone;

        point.innerHTML = "거리 : <span id=\"point\">" + calcDistance(latitude,longitude,point_y,point_x) + "</span> m";

        list.appendChild(content1);
        list.appendChild(content2);
        list.appendChild(content3);
        list.appendChild(point);
        div.appendChild(list);
        div.onclick = function () {
            displayPlaceInfo(data);
        }
        div.addClassName("lists");
        $("List").appendChild(div);

        count2 += 1;

        if (count2 === count)
        {
            count2 = 0;
            sortList();
        }
    });
}

function calcDistance(lat1, lon1, lat2, lon2)
{
     var theta = lon1 - lon2;
     var dist = Math.sin(deg2rad(lat1)) * Math.sin(deg2rad(lat2)) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.cos(deg2rad(theta));
     dist = Math.acos(dist);
     dist = rad2deg(dist);
     dist = dist * 60 * 1.1515;
     dist = dist * 1.609344;
     return Number(dist*1000).toFixed(2);
 }

function deg2rad(deg)
{
    return (deg * Math.PI / 180);
}

function rad2deg(rad)
{
    return (rad * 180 / Math.PI);
}

function sortList()
{
    var list = $$("div#List div.lists");
    var contentList = document.getElementById("List");
    while (contentList.hasChildNodes()){contentList.removeChild(contentList.firstChild);}
    var k;
    for (var i = 1; i < list.length; i ++)
    {
        k = i;
        while (k != 0 && list[k].select('span#point')[0].innerText < list[k - 1].select('span#point')[0].innerText)
        {
            var temp = list[k];
            list[k] = list[k - 1];
            list[k - 1] = temp;
            k -= 1;
        }
    }
    for (var i = 0; i < list.length; i ++)
    {
        contentList.appendChild(list[i]);
    }
}

function showAll(cnt,board_number)
{
    var things = $$("div.board2 div.category");
    document.getElementById(board_number).innerHTML = cnt;
    for (var i = 0; i < things.length; i ++)
    {
        things[i].up('div').removeClassName("dissapper");
    }
}

function showOne(title,board_number,cnt)
{
    var things = $$("div.board2 div.category");
    document.getElementById(board_number).innerHTML = cnt;
    for (var i = 0; i < things.length; i ++)
    {
        if (things[i].innerText === title) things[i].up('div').removeClassName("dissapper");
        else {
            things[i].up('div').addClassName("dissapper");
        }
    }
}

function showTwo(title,board_number,cnt)
{
    var things = $$("div.board2 div.category");
    document.getElementById(board_number).innerHTML = cnt;
    for (var i = 0; i < things.length; i ++)
    {
        if (things[i].innerText === title) things[i].up('div').removeClassName("dissapper");
        else {
            things[i].up('div').addClassName("dissapper");
        }
    }
}
