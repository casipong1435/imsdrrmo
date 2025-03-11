<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INCIDENT REPORT</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 15px;
            margin: 1rem;
            padding: 0;
        }


        .header {
            text-align: center;
            margin-bottom: 10px;
            margin-top: 75px;
            line-height: 5px;
        }

        .header img {
            height: 50px;
            
        }

        .title {
            font-size: 25px;
            text-align: center;
            margin: 50px 0;

        }

        .content{
            font-size: 15px;
        }
        .footer{
            margin: 50px 0;
        }
        p{
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="images/logo/tangubcity.png" alt="TANGUB CITY" style="position: absolute; top: -30px; width: 70; height: 65">
            <img src="images/logo/bagongpilipinas.png" alt="Bagong Pilipans" style="position: absolute; left: 300px; top: -40px; width: 80; height: 80">
            <img src="images/logo/cdrrmc.png" alt="CSWD" style="position: absolute; right: 0; top: -55px; width: 100; height: 100">
            <p style="font-size: 14px">Republic of the Philippines</p>
            <p><b>CITY OF TANGUB</b></p>
            <p>TANGUB CITY DISASTER RISK REDUCTION AND MANAGEMENT OFFICE</p>
            <p>Hotline #09981847340/09510898633/09187118529</p>
        </div>
        <div class="title">
            <span><b>INCIDENT REPORT</b></span>
        </div>
        <p><b>INFORMANT: </b>{{ $data['informant'] }}</p>
        <p><b>DATE: </b>{{ $date }}</p>
        <p><b>TIME OF INCIDENT: </b> {{ $time }}</p>
        <br>
        <div class="content">
            <p style="margin-bottom: 50px"><b>TYPE OF INCIDENT: {{ $data['incident_type'] }} Incident</b></p>
            <p style="text-align: justify; text-indent: 40px">
                RATIONALE: {{ $data['rationale'] }}.
            </p>
        </div>
        <br>
        <br>
        <p><b>CASULATIES: </b>{{ $data['casualties'] }}</p>
        <p><b>LOCATION/INCIDENT AREA: </b>{{ $data['location'] }}</p>
        <div class="footer">
            <div style="line-height: 1px">
                <p><b>ENGR. GERONIMO N. MANTUHAC</b></p>
                <p>OIC-CDRRMO Officer</p>
            </div>
        </div>
    </div>
</body>
</html>
