<?php

if (key !== 'Backspace' && key !== 'Tab') {
        if (isNaN(key) || key == ' ') {
            alert("Please press a numeric key");
            event.preventDefault();
        }
        else {
            if (document.getElementById(event_field).value.length == 2)
                document.getElementById(event_field).value = document.getElementById(event_field).value + '-';

            if (document.getElementById(event_field).value.length == 5)
                document.getElementById(event_field).value = document.getElementById(event_field).value + '-';
        }
    }
    else if (key == 'Tab') {
        if (document.getElementById(event_field).value.length == 1) {
            document.getElementById(event_field).value = 0 + document.getElementById(event_field).value + '-';
            event.preventDefault();
        }
        else if (document.getElementById(event_field).value.length == 4) {
            const dateArray = document.getElementById(event_field).value.split("-");
            if (dateArray[1].length == 1) {
                dateArray[1] = 0 + dateArray[1];
            }
            document.getElementById(event_field).value = dateArray.join("-");
            document.getElementById(event_field).value = document.getElementById(event_field).value + "-";
            event.preventDefault();
        }
        else if (document.getElementById(event_field).value.length > 7) {
            const dateArray = document.getElementById(event_field).value.split("-");
            if (dateArray[2].length == 3) {
                alert("Date format should be dd/mm/yy OR dd/mm/yyyy");
                event.preventDefault();
            }

            if (dateArray[2].length == 2) {
                let current_year = new Date().getFullYear().toString().substr(2);
                if (dateArray[2] >= 0 && dateArray[2] <= current_year) {
                    dateArray[2] = 20 + dateArray[2];
                    document.getElementById(event_field).value = dateArray.join("-");
                }
                else {
                    dateArray[2] = 19 + dateArray[2];
                    document.getElementById(event_field).value = dateArray.join("-");
                }
            }
        }
        else {
            alert("Date format should be dd/mm/yy OR dd/mm/yyyy");
            event.preventDefault();
        }

    }

    ?>