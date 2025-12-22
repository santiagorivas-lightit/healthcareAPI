<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet"  href="https://beyondco.de/css/default.css">
        <title>Appointment Notification</title>
    </head>
    <body style="background: url('https://beyondco.de/img/monotone_software.png') top right no-repeat;
        background-position-x: calc(100% + 0px);
        background-position-y: -140px;
        ">
        <div class="container px-4 md:px-8 mx-auto pt-4 flex flex-col">
            <div>
                <h1 class="text-3xl font-bold font-mono underline underline-offset-4"> Your appointment was successfully created! </h1>
                <p class="pt-2 text-lg font-serif"> Remember to be at least 15 minutes early to the clinic {{$clinic->name}} </p>
                <div class="text-dark-blue-800 pt-4">
                    <h2 class="text-2xl font-bold font-mono underline underline-offset-4"> Your appointment data: </h2>
                    <ul class="list-disc text-lg font-serif">
                        <li> Appointment Number: {{$appointmentNumber}} </li>
                        <li> Time: {{$startingTime}} </li>
                        <li> Doctor: {{$doctor->name}} </li>
                        <li> Clinic Address: {{$clinic->address}} </li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
