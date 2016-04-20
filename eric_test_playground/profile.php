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
                        <td>First Name</td>
                        <td>Last Name</td>
                        <td>Email</td>
                        <td>Profile Link</td>
                        <td>Report Link</td>
                    </tr>
                    </thead>

                    <tbody id="all-applicant-list"></tbody>
                </table><!--/.overview-table-->

            </div>
        </div>
    </div>

    <div class="footer"></div>
</div><!-- close class="wrapper" -->

<script type="application/javascript" src="js/lib/mustache.js"></script>
<script type="application/javascript" src="js/admin.js"></script>

<script id="all-applicant-template" type="x-tmpl-mustache">

{{ #applicants }}
<tr class="active">
    <td class="id">{{ applicantID }}</td>
    <td class="avatar">
        <img src="{{ profilePicture }}" />
    </td>
    <td class="first-name">{{ firstName }}</td>
    <td class="last-name">{{ lastName }}</td>
    <td class="email">{{ email }}</td>
    <td class="profile-link">
        <a href="{{ profileLink }}">Profile</a>
    </td>
    <td>
        <a class="report-link" data-id="{{ applicantID }}" href="#">Report</a>
    </td>
</tr>

<tr>
    <td></td>
    <td colspan="6">
        <table class="scan-table">
            <tr>
                <td>ID</td>
                <td>Date</td>
                <td>Link</td>
                <td>Score</td>
            </tr>

            <tbody id="{{ applicantID }}-scans"></tbody>

        </table><!--/#scan-table-->
    </td>
</tr>

{{ /applicants }}

</script><!--/#all-applicant-template-->

<script id="applicant-scan-template" type="x-tmpl-mustache">

{{ #scans }}
<tr>
    <td>{{ scanID }}</td>
    <td>{{ date }}</td>
    <td><a class="view-report" data-path="{{ path }}" href="#">View</a></td>
    <td>{{ score }}</td>
</tr>
{{ /scans }}

</script>
</body>
</html>