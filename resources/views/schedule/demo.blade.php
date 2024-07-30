@extends('home')

@section('content')
    <style>
        div#left {
            margin-left: 40px;
            float: left;
            width: 220px;
        }

        div#center,
        div#right {
            float: left;
            width: 220px;
            margin-left: 50px;
        }

        /* ul,
        ol {
            list-style: none;
            border: 1px solid black;
            padding: 0;
        }

        li {
            padding: 0 10px;
            height: 25px;
            line-height: 25px;
        }

        li:nth-child(odd) {
            background-color: #CCC;
        }

        li:nth-child(even) {
            background-color: white;
        }

        li:hover {
            cursor: move;
        } */

        .box {
            min-height: 100px;
            height: auto;
            width: 100px;
            padding: 10px;
            border-width: 4px;
            border-style: solid;
            position: absolute;
        }

        .day {
            min-height: 100px;
            height: auto;
            width: 100px;
            padding: 10px;
            border-width: 4px;
            border-style: solid;
            position: absolute;
        }

        .day div {
            background-color: #00122f;
            padding-top: 2px;
            padding-bottom: 2px;
            margin-bottom: 5px;
            border-radius: 3px;
            padding-right: 1px;
            color: white;
            padding-left: 3px;
        }

        #day1 {
            border-color: orange;
            left: 10px;
            top: 100px;
            width: 150px;
        }

        #day2 {
            border-color: blue;
            left: 200px;
            top: 100px;
            width: 150px;
        }

        #day3 {
            border-color: green;
            left: 390px;
            top: 100px;
            width: 150px;
        }

        #day4 {
            border-color: red;
            left: 580px;
            top: 100px;
            width: 150px;
        }

        #day5 {
            border-color: darkturquoise;
            left: 770px;
            top: 100px;
            width: 150px;
        }

        .instructions {
            color: red;
        }

        #reorder ul {
            margin-left: 20px;
            width: 200px;
            border: 1px solid black;
            list-style: none;
            padding: 0;
        }

        #reorder li {
            padding: 2px 20px;
            height: 25px;
            line-height: 25px;
        }

        #update-button,
        #update-message {
            height: 30px;
            margin-left: 20px;
            width: 200px;
            font-weight: bold;
        }

        ol.indexpage {
            margin-top: 30px;
            font-family: sans-serif;
            list-style: decimal;
            border: none;
            margin-left: 50px;
        }

        .indexpage li {
            border: none;
            background-color: white;
        }
    </style>

    <div style="height:500px;">

    <div class='day' id='day1'>
        <h4>Monday</h4>
        <div id='3'>Breakfast</div>
        <div id='4'>Lunch</div>
        <div id='10'>Dinner</div>
    </div>
    <div class='day' id='day2'>
        <h4>Tuesday</h4>
        <div id='1'>Meeting with Jack</div>
        <div id='7'>Working lunch</div>
        <div id='8'>Phone call with Sarah</div>
        <div id='9'>Team meeting</div>
        <div id='12'>HR Review</div>
    </div>
    <div class='day' id='day3'>
        <h4>Wednesday</h4>
        <div id='5'>Progress update</div>
        <div id='6'>Call Simon</div>
    </div>
    <div class='day' id='day4'>
        <h4>Thursday</h4>
        <div id='2'>Drinks with Bob</div>
        <div id='11'>Weekly report</div>
    </div>
    <div class='day' id='day5'>
        <h4>Friday</h4>
        <div id='13'>Zoom meeting</div>
        <div id='14'>Email Jo</div>
        <div id='15'>Company Meal</div>
    </div>

</div>

@section('script')
    <script>
        $(function() {
            $(".day").sortable({
                connectWith: ".day",
                cursor: "move",
                helper: "clone",
                items: "> div",
                stop: function(event, ui) {
                    var $item = ui.item;
                    var eventLabel = $item.text();
                    var newDay = $item.parent().attr("id");

                    console.log($item[0].id, eventLabel, newDay);

                    // Here's where am ajax call will go

                }
            }).disableSelection();
        });

        $(document).ready(function() {

            $('.day div').draggable({
                helper: 'clone',
                cursor: 'move'
            });

            $('.day').droppable({
                tolerance: 'pointer',
                drop: function(event, ui) {
                    var id = $(ui.draggable).attr('id');
                    var eventItem = $(ui.draggable).html();
                    var day = $(this).attr('id');

                    // Here's where am ajax call will go 

                    $(ui.draggable).remove();
                    $('#' + day).append('<div id="' + id + '">' + eventItem + '</div>');
                    $('div#' + id).draggable({
                        helper: 'clone',
                        cursor: 'move'
                    });
                    $(this).css('min-height', 'auto');

                }
            });

        });
    </script>
@endsection

@endsection
