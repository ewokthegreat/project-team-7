
<!doctype html>
<html>
<head>
    <!-- META -->
    <meta charset="utf-8">
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<!-- wrapper, to center website -->
<div class="wrapper">

    <!-- navigation -->
    <ul class="navigation">
        <li  >
            <a href="#">Index</a>
        </li>
        <li class="active">
            <a href="profile.php">Profiles</a>
        </li>
        <!-- for not logged in users -->
        <li>
            <a href="login.html">Login</a>
        </li>
        <li>
            <a href="register.html">Register</a>
        </li>
    </ul>

    <!-- my account -->
    <ul class="navigation right">
    </ul><div class="container">
    <h1>ADMINISTRATOR INTERFACE</h1>
    <div class="box">

        <!-- echo out the system feedback (error and success messages) -->

        <h3>What happens here?</h3>
        <div>
            This controller/action/view shows a list of all users in the system. We will use the server-side code to
            build things that use profile information of one each user.
        </div>
        <div>
            <table class="overview-table">
                <thead>
                <tr>
                    <td>Id</td>
                    <td>Avatar</td>
                    <td>Username</td>
                    <td>User's email</td>
                    <td>Activated ?</td>
                    <td>Score</td>
                    <td>Profile Link</td>
                    <td>Report Link</td>
                </tr>
                </thead>
                <tr class="active">
                    <td>1</td>
                    <td class="avatar">
                        <img src="avatars/1.jpg" />
                    </td>
                    <td>test</td>
                    <td>NOT VISIBLE IN THE DEMO</td>
                    <td>Yes</td>
                    <td>5</td>
                    <td>
                        <a href="#">Profile</a>
                    </td>
                    <td>
                        <a href="#">Report</a>
                    </td>
                </tr>
                <tr class="active">
                    <td>2</td>
                    <td class="avatar">
                        <img src="avatars/2.jpg" />
                    </td>
                    <td>jj</td>
                    <td>NOT VISIBLE IN THE DEMO</td>
                    <td>Yes</td>
                    <td>2</td>
                    <td>
                        <a href="#">Profile</a>
                    </td>
                    <td>
                        <a href="#">Report</a>
                    </td>
                </tr>
                <tr class="inactive">
                    <td>3</td>
                    <td class="avatar">
                        <img src="avatars/default.jpg" />
                    </td>
                    <td>benspanda</td>
                    <td>NOT VISIBLE IN THE DEMO</td>
                    <td>No</td>
                    <td>1</td>
                    <td>
                        <a href="#">Profile</a>
                    </td>
                    <td>
                        <a href="#">Report</a>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>
    <div class="footer"></div>
</div><!-- close class="wrapper" -->

</body>
</html>