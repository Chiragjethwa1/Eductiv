var [wdgt_index, keyword] = [0, 0];
const final_keywords = [];
const article_keyword = {};
// const root = "http://localhost/eductiv/";  // locally for XAMPP
const root = "http://localhost:8080/";
// const root = "/eductiv/";  // For current production

url = new URL(window.location.href);

function putColor(url, element) {
    const image = document.createElement('img');
    image.setAttribute('src', url);
    image.style.display = "none";
    image.onload = function() {
        var vibrant = new Vibrant(image, 256);
        var swatches = vibrant.swatches();
        $(element).css("background-color", swatches["DarkVibrant"].getHex() + "D0");
    }
}

function getArticleId() {
    if (url.searchParams.get("article") != null) {
        console.log(url.searchParams.get("article"));
        $("#article-modal").css("visibility", "visible");
    }
}

function getArticles(limit, offset, status) {
	$.ajax({
		type: "POST",
		url: root+"article.php",
		data: {
			service: "get",
			limit: limit,
			offset: offset,
			status: status
		},
		success: function(response) {
			var result = JSON.parse(response);
			if (result.response == "success") {
				var article = result.article;
				if (article.length > 1) {
					article.forEach(function(article) {
                        if (article.image == null) article.image = "general.jpg";
						$(".content").append('<div class="card-bg" style="background-image: url(\'storage/'+article.username+'/articles/'+article.id+'/'+article.image+'\');"><div class="card" data-article-id='+article.id+'><a href="#" class="field">'+article.field+'</a><a href="#" data-article-id='+article.id+' class="bookmark bi bi-bookmark"></a><a href="#" title="'+article.title+'" onclick=\'viewArticle('+article.id+');\'><h2 class="title">'+article.title+'</h2></a><div class="credit"><a href="#"><span class="user">'+article.name+'</a><p class="time">'+article.time+'</p></div><div class="reaction"><span class="likes bi bi-heart">&nbsp;40</span><span class="views bi bi-eye">&nbsp;6B</span></div></div></div>');
					});
                } else {
                    if (article.image == null) article.image = "general.jpg";
                    $(".content").append('<div class="card-bg" style="background-image: url(\'storage/'+article.username+'/articles/'+article.id+'/image/'+article.image+'\');"><div class="card"><a href="#" class="field">'+article.field+'</a><a href="#" data-article-id='+article.id+' class="bookmark bi bi-bookmark"></a><a href="#" title="'+article.title+'" onclick="viewArticle('+article.id+'");><h2 class="title">'+article.title+'</h2></a><div class="credit"><a href="#"><span class="user">'+article.name+'</a><p class="time">'+article.time+'</p></div><div class="reaction"><span class="likes bi bi-heart">&nbsp;40</span><span class="views bi bi-eye">&nbsp;6B</span></div></div></div>');
                }
				
				/*for (var i = 0; i < $(".article-card").length; i++) {
					var url = $('.card-bg:eq('+ i +')').css("background-image");
					url = url.replace('url("', "");
					url = url.replace('")', "");
					var element = '.article-card:eq('+ i +')';
					putColor(url, element);
				}*/
                $(".card-bg").each(function(index) {
                    var url = $(".card-bg").eq(index).css("background-image");
                    url = url.replace('url("', "");
                    url = url.replace('")', "");
                    putColor(url, $(".card").eq(index));
                });
			} else if (result.response == "error") {
				alert(result.message);
			}
		}
	});
}

function viewArticle(id) {
    $.ajax({
		type: "POST",
		url: root+"article.php",
		data: {
			service: "get",
			id: id
		},
		dataType: "JSON",
		success: function(response) {
			if (response.response == "success") {
				var article = response.article.article;
                //console.log(response.article.content.length);
				var content;

                if (response.article.content.length === undefined) {
                    content = [];
                    content.push(response.article.content);
                } else {
                    content = response.article.content;
                }

                $(".article-container .field").text(article.field);
                $(".article-container .title").text(article.title);
				$(".article-container .user a").attr("href", article.username);
                $(".article-container .user a").text(article.user);
                $(".article-container .time").text("published " + article.time);
				$(".article-container .like").data('id', id);
                
                content.forEach(function(content) {
					switch (content.type) {
						case "TEXT":
							$(".article-body .segments").append('<div class="segment"><p>'+content.content+'</p></div>');
							break;

						case "IMAGE":
							$(".article-body .segments").append('<div class="segment"><img src=\'storage/'+article.username+'/articles/'+article.id+'/image/'+content.content+'\' loading="lazy"></div>');
							break;

                        case "LINK":
							$(".article-body .segments").append('<div class="segment"><a href='+content.content+'>'+content.content+'</a></div>');
							break;
					
						default:
							break;
					}
				});

                $(".article-container").css("background", $(".card[data-article-id="+id+"]").css("background-color"));

                $(".modal-body").css("background-image", $(".card[data-article-id="+id+"]").parents(".card-bg").css("background-image"));

                $("#article-modal").css("visibility", "visible");
			} else if (result.response == "error") {
				alert(result.message);
			}
		},
		beforeSend: function() {
			// add loader or something
		}
	});
}

function rmWidget(index) {
    $('.widget[data-index='+index+']').remove();
}

function rmKeyword(index) {
    $(".keyword[data-key-index="+index+"]").remove();
    if ($(".keywords").is(":empty")) {
        $(".keywords").html("&nbsp;&nbsp;&nbsp;&nbsp;No Keywords Featured");
        keyword = 0;
    }
}

$(document).ready(function() {
    try {
        // Remove "Powered by 000webhost" tag
        document.querySelector('body div.container + div').remove()
    } catch (error) {
        console.log(error);
    }
    getArticleId();

    $("#article-keyword").click(function() {
        $(".keywords").toggle();
    });

    $("#search-bar").keyup(function(){
		if ($(this).val().length != 0) {
            $(".search-result").show();
			$(".search-result").css("visibility", "visible");
			$(".search-result").text("Search results for " + $(this).val());
		} else {
			$(".search-result").hide();
		}
	});

	$("#search-bar").focusin(function(){
		if ($(this).val().length != 0) {
			$(".search-result").show();
            $(".search-result").css("visibility", "visible");
			$(".search-result").text("Search results for " + $(this).val());
		}
	});

	$("#search-bar").focusout(function(){
		$(".search-result").hide();
	});

    $('#search-bar').on('input', function(e) {
        if('' == this.value) {
            $(".search-result").hide();
            $(".search-result").css("visibility", "hidden");
            $(".search-result").empty();
        }
    });

    /*$('#article-keyword-input').focusin(function() {
        $('.article-meta .search-result').css("visibility", "visible");
    });*/
    

    /*$(".card-bg").each(function(index) {
        var url = $(".card-bg").eq(index).css("background-image");
        url = url.replace('url("', "");
        url = url.replace('")', "");
        putColor(url, $(".card").eq(index));
    });*/

    $(".menu ul li a").click(function() {
        if ($(this).children("span").text() !== "Login" && $(this).children("span").text() != "Signup") {
            $(".active").removeClass("active");
            $(this).addClass("active");
        }

        switch ($(this).children("span").text()) {
            case "Signup":
                $("#signup-modal").css("visibility", "visible");
                break;

            case "Login":
                $("#login-modal").css("visibility", "visible");
                break;

            case "Post":
                $("#post-modal").css("visibility", "visible");
                $.ajax({
                    url: root+"general?service=fields&field=null",
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.response == 'success') {
                            data.field.forEach((item) => {
                                $("#article-field-input").append("<option value="+item.field+">"+item.field+"</option>");
                            });
                        }
                    }
                });
                break;
        }
    });

    $(".card .title").click(function() {
        url.searchParams.set("article", 5);
        getArticleId();
    });

    /*$("#article-keyword-input").keyup(function() {
        $('.article-meta .search-result').css("visibility", "visible");
    });*/

    $(".close").click(function() {
        url.searchParams.delete("article");
        $('#'+$(this).parent().parent().prop("id")).css("visibility", "hidden");
        $(".article-body .segments").empty();
    });

    $("#article-field-input").change(function() {
        var field = $("#article-field-input").find(':selected').text()
        if (field != "Select field") {

            $.ajax({
                url: root+"/general?service=fields&field="+field,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if (data.response == 'success') {
                        article_keyword[field] = data.field.keyword.split(';');
                    }
                }
            });
        }
    });

    $("#post-form .article-meta #article-keyword-input").focusin(function() {
        $('.article-meta .search-result').empty();
        if ($("#article-field-input").find(':selected').text() != "Select field") {
            article_keyword[$("#article-field-input").find(':selected').text()].forEach(element => {
                $('.article-meta .search-result').append('<i>'+element+'</i>');
            });
            $('.article-meta .search-result').append('<i>'+'</i>');
            $('.article-meta .search-result').css("visibility", "visible");
        }
    });

    /*$("#article-keyword-input").focusout(function() {
        $('.article-meta .search-result').empty();
        $('.article-meta .search-result').css("visibility", "hidden");
    });*/

    $('#article-keyword-input').on('input', function(e) {
        if('' == this.value) {
            $('.article-meta .search-result').empty();
            $('.article-meta .search-result').css("visibility", "hidden");
        }
    });

    $(".bookmark").click(function() {
        $(this).toggleClass("bi-bookmark-fill bi-bookmark");
        $(this).css("visibility", "visible");
        //console.log($(this).hasClass("bi-bookmark-fill"));
    });

    $('.card').mouseover(function() {
        $(this).children(".bookmark").css("visibility", "visible");
    });
    
    $('.card').mouseout(function() {
        if (!$(this).children(".bookmark").hasClass("bi-bookmark-fill")) {
            $(this).children(".bookmark").css("visibility", "hidden");
        }
    });

    $(".pwd-eye").mouseover(function() {
        $(this).siblings("#pwd-cfm-input").prop('type', 'text');
        $(this).siblings("#pwd-input").prop('type', 'text');
        $(this).siblings("#pwd-lgn-input").prop('type', 'text');
    });

    $(".pwd-eye").mouseout(function() {
        $(this).siblings("#pwd-cfm-input").prop('type', 'password');
        $(this).siblings("#pwd-input").prop('type', 'password');
        $(this).siblings("#pwd-lgn-input").prop('type', 'password');
    });


    // check username
    $("input[id='username-input']").keyup(function() {
		if ($("input[id='username-input']").val().length >= 12) {
			$.ajax({
				url: root+"user",
				type: "GET",
				data: {
					service: "check-user",
					username: $("input[id='username-input']").val(),
					email: $("input[id='email-input']").val(),
				},
				dataType: "JSON",
				success: function(data) {
					if (data.response == "success") {
						$("#info").text(data.message);
						$("input[id='username-input']").parent().css("border-color","green");
					} else {
						$("#info").text(data.message);
						$("input[id='username-input']").css("border","solid 1px red");
					}
				}
			});
		} else {
			/*$("input[id='username-input']").css("border","solid 1px red");
			$("#info").text("too short");*/
		}
	});

    $("#login-form").submit(function(e) {
		e.preventDefault()
		$.ajax({
			url: root+"user/",
			type: "POST",
			data: {
				service: "enter",
				username: $("#username-lgn-input").val(),
				email: '',
				password: $("#pwd-lgn-input").val()
			},
            beforeSend: function() {
                $(".notification").append('<div class="notify">Autheticating...<button class="cls-note bi bi-x"></button></div>');
            },
			dataType: "JSON",
			/*beforeSend: function() {
				if ($("#username-input").val().length < 12) {alert("Username is too short!");}
				if ($("#username-input").val().length > 20) {alert("Username is too long!");}
				if ($("#password-input").val().length < 8) {alert("Password is too short!");}
				if ($("#password-input").val().length > 12) {alert("Password is too long!");}
			},*/
			success: function(response) {
				if (response.response == "success" && response.message) {
					//console.log(response.message);
                    //window.location.assign('dashboard');
                    $(".notification").append('<div class="notify">Logged in Successfully!<button class="cls-note bi bi-x"></button></div>');
                    window.setTimeout(function () {
                        location.href = "dashboard";
                    }, 2000);
				} else {
					alert('Login attempt failed, please try again');
				}
			},
			error: function(xhr) {
				alert("Error occured! Check your internet connection!");
			}
		});
	});

    $("#logout").click(function() {
		$.ajax({
			url: root+"user/?service=exit",
			type: "POST",
			contentType: "application/json",
			dataType: "JSON",
			success: function(response) {
				if (response.response == "success" && response.message) {
					window.location.assign('dashboard');
				} else {
					alert('Logout attempt failed, please try again');
				}
			},
			error: function(xhr) {
				alert("Error occured! Check your internet connection!");
			}
		});
	});

    $("#signup-form").submit(function(e) {
        e.preventDefault();
        var role = $("#role-input").is(":checked")?"contributor":'';
        if ($("#pwd-input").val() == $("#pwd-cfm-input").val()) {
            //e.preventDefault()
            $.ajax({
                url: root+"user",
                type: "POST",
                data: {
                    service: "add",
                    first_name: $("#first-name-input").val(),
                    last_name: $("#last-name-input").val(),
                    email: $("#email-input").val(),
                    username: $("#username-input").val(),
                    password: $("#pwd-cfm-input").val(),
                    role: role,
                    bio: 'null',
                    profile_image: 'null'

                },
                dataType: "JSON",
                /*beforeSend: function() {
                    if ($("#username-input").val().length < 12) {alert("Username is too short!");}
                    if ($("#username-input").val().length > 20) {alert("Username is too long!");}
                    if ($("#password-input").val().length < 8) {alert("Password is too short!");}
                    if ($("#password-input").val().length > 12) {alert("Password is too long!");}
                },*/
                success: function(response) {
                    if (response.response == "success" && response.message) {
                        $(".notification").append('<div class="notify">Account Created!<button class="cls-note bi bi-x"></button></div>');
                    window.setTimeout(function () {
                        location.href = root;
                    }, 2000);
                    } else {
                        alert('Login attempt failed, please try again');
                    }
                },
                error: function(xhr) {
                    alert("Error occured! Check your internet connection!");
                }
            });
        }
	});

    $(".widgets button").click(function() {
        var elmnt;
        switch ($(this).data('wdgt')) {
            case 't':
                elmnt = '<div class="widget" data-index='+wdgt_index+' data-wdgt-type="t"><textarea name="text[]" class="text" placeholder="Add Text"></textarea><div><button onclick="rmWidget('+wdgt_index+');" class="cls bi bi-x"></button></div></div>';
                break;

            case 'i':
                elmnt = '<div class="widget" data-index='+wdgt_index+' data-wdgt-type="i"><div class="image"><label for="contentImg'+wdgt_index+'" class="bi bi-image-fill">&nbsp;&nbsp;Add / Change Image</label><div class="file-path">No Image Uploaded</div><input type="file" name="file[]" id="contentImg'+wdgt_index+'" accept="image/*" style="display: none;"><img src="asset/image/img3.jpg" style="width: 30%;float: right;"></div><div><button onclick="rmWidget('+wdgt_index+');" class="cls bi bi-x"></button></div></div>';
                break;
        
            case 'v':
                elmnt = '<div class="widget" data-index='+wdgt_index+' data-wdgt-type="v"><div class="image"><label for="contentVid'+wdgt_index+'" class="bi bi-camera-video-fill">&nbsp;&nbsp;Add / Change Video</label><div class="file-path">No Video Uploaded</div><video src="asset/image/PPDb-PPT-TEASER.mp4" controls></video><input type="file" name="file[]" id=\"contentVid'+wdgt_index+'" accept="video/*" style="display: none;"></div><div><button onclick="rmWidget('+wdgt_index+');" class="cls bi bi-x"></button></div>';
                break;

            case 'l':
                elmnt = '<div class="widget" data-index='+wdgt_index+' data-wdgt-type="l"><input type="url" class="link" name="link[]" placeholder="Add URL&nbsp;&nbsp;&nbsp;&nbsp;Example: http://www.somewhere.com"><div><button onclick="rmWidget('+wdgt_index+');" class="cls bi bi-x"></button></div></div>';
                break;

            case 'f':
                elmnt = '<div class="widget" data-index='+wdgt_index+' data-wdgt-type="f"><div class="image"><label for="contentImg'+wdgt_index+'" class="bi bi-file-earmark-fill">&nbsp;&nbsp;Attach / Change File</label><div class="file-path">No File Attached</div><input type="file" name="file[]" id="contentImg'+wdgt_index+'" style="display: none;"></div><div><button onclick="rmWidget('+wdgt_index+');" class="cls bi bi-x"></button></div></div>';
                break;
        }
        $(".article-content-area").append(elmnt);
        wdgt_index++;
    });


    $("#article-keyword-input").keypress(function(e) {
        if(e.which == 13 & $(this).val() != '') {
            if (keyword == 0) {$(".keywords").empty()}
            $(".keywords").append('<div class="keyword" data-key-index='+keyword+'><button type="button" onclick="rmKeyword('+keyword+');">'+$(this).val()+'<span class="bi bi-x"></span></button></div>');
            final_keywords.push($(this).val());
            $(this).val('');
            keyword++;
            e.preventDefault();
        }
    });

    $("#post-form").submit(function(e) {
        e.preventDefault();
        var bind = '';

        $(".widget[data-wdgt-type]").each(index => {
            switch ($(".widget[data-wdgt-type]").eq(index).data("wdgt-type")) {
                case 't':
                    bind += 't';
                break;

                case 'i':
                    bind += 'i';
                    break;
            
                case 'v':
                    bind += 'v';
                    break;

                case 'l':
                    bind += 'l';
                    break;

                case 'f':
                    bind += 'f';
                    break; 
            }
        });

        var keys = '';
        for (let index = 0; index < final_keywords.length; index++) {       
            keys += final_keywords[index] + ';';
        }

        //console.log(bind);
        var post_form = $('#post-form')[0];
        var form_data = new FormData(post_form);
        form_data.append("service", "add");
        form_data.append("keyword", keys);
        form_data.append("pattern", bind);
        if ($("#status").is(":checked")) {
            form_data.append("status", "DRAFT");
        } else {
            form_data.append("status", "null");
        }
        
        $.ajax({
            url: root+"article.php",
            type: "POST",
            enctype: "multipart/form-data",
            contentType: false,
            processData: false,
            data: form_data,
            success: function(data) {
                console.log(data);
            }
        });
    });

    $("#course-post-form").submit(function(e) {
        e.preventDefault();
        var bind = '';

        $("#course-post-form .widget[data-wdgt-type]").each(index => {
            switch ($(".widget[data-wdgt-type]").eq(index).data("wdgt-type")) {
                case 't':
                    bind += 't';
                break;

                case 'i':
                    bind += 'i';
                    break;
            
                case 'v':
                    bind += 'v';
                    break;

                case 'l':
                    bind += 'l';
                    break;

                case 'f':
                    bind += 'f';
                    break; 
            }
        });

        var keys = '';
        for (let index = 0; index < final_keywords.length; index++) {       
            keys += final_keywords[index] + ';';
        }
        console.log(bind);

        //console.log(bind);
        var post_form = $('#course-post-form')[0];
        var form_data = new FormData(post_form);
        form_data.append("service", "add");
        form_data.append("keyword", keys);
        form_data.append("pattern", bind);
        if ($("#status").is(":checked")) {
            form_data.append("status", "DRAFT");
        } else {
            form_data.append("status", "null");
        }
        
        $.ajax({
            url: root+"course.php",
            type: "POST",
            enctype: "multipart/form-data",
            contentType: false,
            processData: false,
            data: form_data,
            success: function(data) {
                console.log(data);
            }
        });
    });
});
