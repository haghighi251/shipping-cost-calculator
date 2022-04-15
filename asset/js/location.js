/**
 * This file is trying to get user location based on browser GPS. 
 * If GPS would be off, In the second level it will try to get the user's postal code.
 * With the user postal code and google API, we can get the user location. 
 * This distance will be used for calculating shipping costs. 
 * If for each reason, this file will be crashed or not work (for example js conflict error), 
 * We could be able to get the user distance from the user postal code on the cart page. 
 */
$ = jQuery;
var location_has_loaded = CheckCookie('location_has_loaded');
$(document).ready(function () {
    if (!location_has_loaded) {
        setCookie('location_has_loaded', true, 1);
        //trying to get the user location from the GPS
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            //trying to get user location from the postal code.
            GetDistanceFromZipCode();
        }
    }
});

//We got the user location from the GPS and right now we have to update our db on the server.
function showPosition(position) {
    $.ajax({
        type: 'POST',
        url: scc.ajaxurl,
        data: {
            action: 'set_user_location',
            Latitude: position.coords.latitude,
            Longitude: position.coords.longitude,
            unique_location_code: scc.unique_location_code,
            user_ip: scc.user_ip,
            user_id: scc.user_id
        },
        success: function (data) {
            data = $.parseJSON(data);
            if (data.success) {} else {}
        },
        error: function (response) {}
    });
}

//This function will run when GPS returns an error.
function showError(error) {
    DeleteCookie('location_has_loaded');
    GetDistanceFromZipCode();
}

//This function set a Cookie for 24 hr. after this time alert will show to the user.
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

//Checking cookie for showing an alert.
function CheckCookie(CookieName) {
    var Cookie = getCookie(CookieName);
    if (Cookie != "")
        return true;
    else
        return false;
}

//This function will delete the cookie
function DeleteCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

//returns the cookie
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

//getting distance from zip code
function GetDistanceFromZipCode() {
    Swal.fire({
        title: '<strong>Set Address</strong>',
        icon: 'info',
        html: '<p>Allow us to access your location for delivery and calculating shipping costs.</p><br>\n\
     <input type="text" name="zipcode" value="" id="scc_address_zipcode" placeholder="Enter Your Zip Code"/>',
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: 'Get Address with Zip Code',
        confirmButtonAriaLabel: '!!!',
        cancelButtonText: 'Later!!!',
        cancelButtonAriaLabel: '@@@',
        inputAttributes: {autocapitalize: 'off'},
        showLoaderOnConfirm: true,
        preConfirm: (login) => {
            return $.ajax({
                type: 'POST',
                url: scc.ajaxurl,
                data: {
                    action: 'get_address_with_zipcode',
                    zipcode: $("#scc_address_zipcode").val(),
                    unique_location_code: scc.unique_location_code,
                    user_ip: scc.user_ip,
                    user_id: scc.user_id
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    setCookie('location_has_loaded', true, 1);
                    //var distance = data.distance;
                    if (data.success) {} else {}
                },
                error: function (response) {}
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    });
}