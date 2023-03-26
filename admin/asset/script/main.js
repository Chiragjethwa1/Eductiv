
// obfuscation remains

$(document).ready(function() {
    $("#sendName").submit(function(e) {
        e.preventDefault();
        
        if ($('input[name="username"]').val().length == 0) {
            alert("Please enter Username");
        } else {
            $.ajax({
                method: "POST",
                accepts: "multipart/form-data",
                url: "/user",
                data: {
                    username: $('input[name="username"]').val()
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    alert(res.response.toUpperCase() + "\n" + res.message);
                }
            });
        }
    });


    /*$("#admin-auth").submit(function(e) {
        e.preventDefault();
        
        if ($('input[name="admin-user"]').val().length == 0) {
            alert("Please enter Username");
        } else if ($('input[name="admin-pwd"]').val().length == 0) {
            alert("Please enter password");
        } else {
            $.ajax({
                method: "POST",
                url: "/api/admin/",
                data: {
                    service: "enter",
                    adminuser: $('input[name="admin-user"]').val(),
                    adminpwd: $('input[name="admin-pwd"]').val()
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    alert(res.response.toUpperCase() + ": " + res.message);
                    window.location = "/api/admin/";
                }
            });
        }
    });*/
});


function getUser() {
    if ($('input[name="username"]').val() == '') {
        alert("Please enter Username");
    } else {
        $.ajax({
            method: "POST",
            accepts: "multipart/form-data",
            url: "EductivUser.php",
            data: {
                username: $('input[name="username"]').val()
            },
            success: function(response) {
                alert(response);
            }
        });
    }
}