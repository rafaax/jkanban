$(document).ready(function(){

    $('#contest_task').on("submit", function(event){
        event.preventDefault();
        if ($("#message").val() === "") {
            
            $("#message").addClass("piscar-vermelho");

            setTimeout(function() {
                $("#message").removeClass("piscar-vermelho");
            }, 10*1000);
                
            Swal.fire({
                title: "Erro!",
                text: "Você não pode enviar uma contestação sem ao menos dar uma justificativa!",
                icon: "warning"
            });
            
            event.preventDefault();
            return;
        }else{
            $("#message").removeClass("piscar-vermelho");
        }
        
        $.ajax({
            method: "POST",
            url: "src/contest_task.php",
            data: new FormData(this),
            contentType: false,
            processData: false,
            beforeSend: function () {
                Swal.fire({
                    title: 'Aguarde...',
                    text: 'Cadastrando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (result) {

                console.log(result);
                var json = JSON.parse(result);
                Swal.close();
                if(json.erro == false){
                    let timerInterval;
                    Swal.fire({
                        icon: 'success',
                        title: json.msg,
                        html: "Fechando em <b></b> milisegundos...",
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getPopup().querySelector("b");
                            timerInterval = setInterval(() => {
                            timer.textContent = `${Swal.getTimerLeft()}`;
                            }, 100);
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            window.location.href = "http://192.168.0.166/jkanban/";
                        }
                    });
                }else if(json.erro == true){
                    Swal.fire({
                        title: json.msg,
                        icon: 'error',
                        allowOutsideClick: () => {
                            const popup = Swal.getPopup()
                            popup.classList.remove('swal2-show')
                            setTimeout(() => {
                            popup.classList.add('animate__animated', 'animate__headShake')
                            })
                            setTimeout(() => {
                            popup.classList.remove('animate__animated', 'animate__headShake')
                            }, 500)
                            return false
                        }
                    })
                }
            }
        })
    });
})