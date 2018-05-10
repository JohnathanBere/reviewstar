$(document).ready(function() {
    // put any required scripts here
    $('.bs-datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    var timer;

    $("#reviewstar_bookbundle_book_bookTitle").on("keyup", function() {
        clearTimeout(timer);

        var fetchFunc = mutateStateForBookInserts;

        var data = {
            "bookTitle": $("#reviewstar_bookbundle_book_bookTitle").val()
        };

        // Set a timer to retrieve the request within 2 seconds.
        timer = setTimeout(function () {
            // We check if the checkbox has been ticked, then we auto populate
            if ($(".apiCheck").is(":checked")) {
                bookSearchRequest(data, fetchFunc)
            }
        }, 2000);
    });

    $("#search").on("keyup", function() {
        clearTimeout(timer);

        var fetchFunc = mutateStateForBookSearch;

        var data = {
            "inputField": $("#search").val()
        };

        // Set a timer to retrieve the request within 2 seconds.
        timer = setTimeout(function () {
            bookSearchRequest(data, fetchFunc)
        }, 2000);
    });

    function bookSearchRequest(inputData, fetch) {
        $.ajax({
            url: $(".googlePathUrl").val(),
            type: "POST",
            dataType: "json",
            data: inputData,
            async: true,
            // We map the pre-existing data from Google Books.
            success: function (data) {
                fetch(data);
            }
        })
    }

    function mutateStateForBookInserts(data) {
        $("#reviewstar_bookbundle_book_bookSynopsis").val(data.description);
        $("#reviewstar_bookbundle_book_bookPublisher").val(data.publisher);
        $("#reviewstar_bookbundle_book_bookPublishdate").val(data.publishedDate);
        $("#reviewstar_bookbundle_book_bookAuthor").val(data.authors[0]);
    }

    function mutateStateForBookSearch(data) {
        $("#results-container").empty();
        data.forEach(function(d) {
            $("#results-container").append(
                "<div>" +
                    "<a href='../book/api/" + d.id + "'>" +
                        "<h4>" +
                            d.volumeInfo.title +
                        "</h4>" +
                        "<img src='" + d.volumeInfo.imageLinks.thumbnail + "'/>" +
                    "</a>" +
                "</div>");
        });
    }
});