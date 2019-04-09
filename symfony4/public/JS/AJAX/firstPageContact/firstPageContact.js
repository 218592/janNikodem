$(document).ready(function () {

    $("#submitMail").click(function () {

        var email = $('input[name="email"]').val();
        var name = $('input[name="name"]').val();
        var surname = $('input[name="surname"]').val();
        var topic = $('input[name="topic"]').val();
        var text = $('textarea#textArea').val();

        if (email != 0 && name != 0 && surname != 0 && topic != 0 && text != 0) {
            $('.animation').show();
            $('.animation2').show();
            document.getElementById("submitMail").disabled = true;

            $('#infoFromAjaxFirstPageContact').hide();
            $.ajax({
                type: "POST",
                url: "/contact/ajaxContactController",
                data: {
                    'email': email,
                    'name': name,
                    'surname': surname,
                    'topic': topic,
                    'textArea': text
                },
                success: function (data) {
                    $('.animation').hide();
                    $('.animation2').hide();


                    if (data.ajaxResponseContactController[0]["responseFlag"] > 0) {
                        var output = '<div class="positiveResponse"><i class="fas fa-exclamation-triangle"></i> Wiadomość wysłana!</div>';
                    } else {
                        var output = '<div class="errorResponse"><i class="fas fa-exclamation-triangle"></i> Błędny adres email!</div>';
                    }

                    $('#infoFromAjaxFirstPageContact').show();
                    $("#infoFromAjaxFirstPageContact").html(output);
                    email = $('input[name="email"]').val('');
                    document.getElementById("submitMail").disabled = false;
                },
            });
        } else {
            var output = '<div class="errorResponse"><i class="fas fa-exclamation-triangle"></i> Wypełnij wszystkie pola!</div>';
            $("#infoFromAjaxFirstPageContact").html(output);
        }
    });
});
