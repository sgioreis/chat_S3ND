// Formulário responsável por autenticar o usuário e atendente ao sistema

$("#form-login").on("submit", function(event) {

    event.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "/atendimento/iniciar_atendimento.php",
        data: form_data
    }).done(function(response) {
        let dados = JSON.parse(response);
        localStorage.setItem('token', dados.token);
        localStorage.setItem('id_usuario', dados.id_usuario);
        localStorage.setItem('id_atendimento', dados.id_atendimento);
        if (dados.atendente) localStorage.setItem('atendente', dados.atendente);
        if (dados.atendente && !dados.id_atendimento) {
            $("#vazio").show();
            setTimeout(() => { window.location.href = 'index.html'; }, 3000)
        } else {
            window.location.href = "chat.html";
        }
    });

});