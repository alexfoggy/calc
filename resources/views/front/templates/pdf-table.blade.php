<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- <meta name="viewport" content="width=1920, initial-scale=1.0"> -->
    <link type="text/css" media="all" rel="stylesheet" href="{{ asset('/front-assets/css/main.css') }}">

</head>

<body class="container">


<table>
    <tr class="active">
        <th>День/Время</th>
        <th class='day1'>Понедельник</th>
        <th class='day2'>Вторник</th>
        <th class='day3'>Среда</th>
        <th class='day4'>Четверг</th>
        <th class='day5'>Пятница</th>
        <th class='day6'>Суббота</th>
    </tr>
    @php
        $i = 0;
    @endphp
    @for($y = 0; $y < $curientTable->row_count; $y++)
        <tr class="active">
            <th>С <span class='timefrom' data-timefrom='{{$defacBody[$i]}}/{{$defacBody[$i+1]}}'>{{$defacBody[$i]}}</span> до <span class='timeto'
                                                                                                                                    data-timeto='9:30'>{{$defacBody[$i+1]}}</span></th>
            <td>{{$defacBody[$i+2]}}</td>
            <td>{{$defacBody[$i+3]}}</td>
            <td>{{$defacBody[$i+4]}}</td>
            <td>{{$defacBody[$i+5]}}</td>
            <td>{{$defacBody[$i+6]}}</td>
            <td>{{$defacBody[$i+7]}}</td>
        </tr>
        @php
            $i = $i + 8;
        @endphp
    @endfor


</table>




</body>

</html>