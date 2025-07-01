document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formReserva');
    form.addEventListener('submit', function(e) {
        const quantidade = parseInt(document.getElementById('quantidade_pessoas').value, 10);
        const sala = parseInt(document.getElementById('sala').value, 10);

        if (!sala || !quantidade) {
            alert('Por favor, selecione a sala e informe a quantidade de pessoas.');
            e.preventDefault();
            return;
        }

        if (sala <= 9 && quantidade > 4) {
            alert('Salas 1 a 9 suportam até 4 pessoas.');
            e.preventDefault();
            return;
        }

        if (sala >= 10 && quantidade <= 4) {
            alert('Salas 10 a 15 são para mais de 4 pessoas.');
            e.preventDefault();
            return;
        }
    });
});