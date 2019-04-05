<?php header('Access-Control-Allow-Origin: *'); ?>

<html>
<head>
</head>
<body style="text-align:center;">
<h1>Don't forget to disable CORS in your browser</h1>

how much data you want to scrap?
<input id="total" />
<br/>
<br/>
<button id="btn-ok" onclick="doScrapping()">Do it!</button>
<br/>
<br/>
<div id="container">

</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    var url = "https://9gag.com/v1/group-posts/group/default/type/hot";
    var nextCursor = "";
    var totalWanted = 10;
    var totalScrapped = 0;

    function doScrapping(){
        totalWanted = document.getElementById("total").value;
        var theUrl = url;
        if(nextCursor!=""){
            theUrl = url+"?"+nextCursor;
        }

        $.ajax({
            method: "GET",
            url: theUrl,
        })
            .done(function (result) {
                console.log(result);
                var posts = result['data']['posts'];
                nextCursor = result['data']['nextCursor'];
                for(var i=0;i<posts.length;i++){
                    var post = posts[i];
                    saveToDb(post);
                }
                if(totalScrapped<totalWanted) {
                    setTimeout(doScrapping, 1000);
                }

            });
    }

    function saveToDb(post){
        var data = { //Fetch form data
            "_token": "{{ csrf_token() }}",
            'post'     : JSON.stringify(post) //Store name fields value
        };
        $.ajax({
            method: "POST",
            url: '{{route("insert_memes")}}',
            data: data,
        })
        .done(function (result) {
            if(result!=0 && result != "0"){
                $("#container").append(result);
            }else{
                $("#container").append('duplicate <br/>');
            }
        });
        totalScrapped += 1;
    }

</script>

</html>
