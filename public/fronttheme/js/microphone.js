function startDictation() {

    if (window.hasOwnProperty('webkitSpeechRecognition')) {

        var recognition = new webkitSpeechRecognition();

        recognition.continuous = false;
        recognition.interimResults = false;

        recognition.lang = "en-US";
        recognition.start();

        recognition.onresult = function(e) {
            document.getElementById('transcript').value = e.results[0][0].transcript;
            recognition.stop();
            document.getElementById('labnol').submit();
        };

        recognition.onerror = function(e) {
            recognition.stop();
        }

    }
}
$(function() {
    $("div.star-rating > s, div.star-rating-rtl > s").on("click", function(e) {
        // remove all active classes first, needed if user clicks multiple times
        $(this).closest('div').find('.active').removeClass('active');
        $(e.target).parentsUntil("div").addClass('active'); // all elements up from the clicked one excluding self
        $(e.target).addClass('active');  // the element user has clicked on
        var numStars = $(e.target).parentsUntil("div").length+1;
        $('.show-result').text(numStars + (numStars == 1 ? " *" : " *"));
    });
});