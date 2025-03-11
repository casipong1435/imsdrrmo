<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply Activity</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 15px;
            margin: 1rem;
            padding: 0;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        
        }
        th,td{
            padding: 0.5rem;
            border: 1px solid black;
            text-align: center;
        }
        th{
            background: yellow;
            color: black;
            font-size: 15px;
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
            <span><b>SUPPLY ACTIVITY</b></span>
        </div>
        <br>
        <center>As of {{ "$date_from - $date_to" }}</center>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Date Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($supply_activities as $activity)
                    <tr>
                        <td>{{ $activity->item_name }}</td>
                        <td>{{ $activity->quantity }}</td>
                        <td>{{ $activity->created_at }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</body>
</html>
