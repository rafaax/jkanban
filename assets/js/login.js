$("#login").on("submit", function (event) {

    event.preventDefault();
    $.ajax({
        method: "POST",
        url: "src/logar.php",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (json) {
            var resposta = JSON.parse(json);
            console.log(resposta.erro);
            if(resposta.erro == true) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Usuário ou senha incorretos!'
                })
            }else if(resposta.erro == 'empty'){
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Preencha o usuário e senha!'
                })
            }
            else if(resposta.erro == false) {
                window.location.href = "http://192.168.0.166/jkanban/"
            }
        }
    })
});