<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create token</title>
    <script src="https://code.jquery.com/jquery-4.0.0.min.js"
            integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
</head>
<body>
<h1>CIAOOOO</h1>
<div id="token"></div>
<form action="https://portale.alittlejag.uk/api/tokens" method="post" enctype="multipart/form-data">
    <p><input type="text" name="user_id" value="text default">
    <p><input type="text" name="max_uses" value="text default">
    <p>
        <button type="submit">Submit</button>
</form>

<button onclick="getUser()"> Get user</button>
<div id="user_data"></div>
</body>
<script>
    const token = getCookie("user_token");
    let user = {}
    document.getElementById('token').innerHTML = token;

    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function getUser() {
        $.ajax({
            url: "https://portale.alittlejag.uk/api/tokens/user",
            type: 'GET',
            dataType: 'json',
            headers: {
                "Authorization": "Bearer " + token
            },
            processData: false,
            success: function (response) {
                console.log("Data received:", response);
                user = response.data.user
                document.getElementById("user_data").innerText = JSON.stringify(user, )


            },
            error: function (xhr, status, error) {
                console.error("Error occurred:", error);
            }
        });
    }


</script>

</html>