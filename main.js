document.getElementById('message-form').addEventListener('submit', function(e){
    e.preventDefault();
    sendToPhp();
});

document.addEventListener('DOMContentLoaded', function(e){
    e.preventDefault();
    getMessagesFromPhp();
    console.log('run');
});

async function sendToPhp(){
    const nameInput = document.getElementById('name-input').value.trim();
    const messageInput = document.getElementById('message-input').value.trim();

    if (!nameInput || !messageInput) {
        alert('Nama dan pesan harus diisi!');
        return;
    }

    try {
        const response = await fetch('main.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({name: nameInput, message: messageInput})
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const hasil = await response.json();
        console.log(hasil);

        // Reset form setelah berhasil
        document.getElementById('message-form').reset();
        
        // Tampilkan pesan sukses (opsional)
        alert('Pesan berhasil dikirim!');
        await getMessagesFromPhp();

    } catch (error) {
        console.error('Error:', error);
        alert('Gagal mengirim pesan. Coba lagi!');
    }
}

async function getMessagesFromPhp(){
    try {
        const response = await fetch('main.php', {
            method: 'GET'
        });

        // Tambah ini untuk debug
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const messages = await response.json();
        console.log('Messages data:', messages);
        showMessages(messages);

    } catch (error) {
        console.error('Error getting messages:', error);
    }
}

function showMessages(messages){
    console.log('tes');
    let cardsMessageContainer = document.getElementById('cards-message-container');
    cardsMessageContainer.innerHTML = '';

    for (const message of messages) {
        let divCardMessage = document.createElement('div');
        divCardMessage.className = 'card-message';

        let nameText = document.createElement('h3');
        nameText.textContent = message.name;

        let messageText = document.createElement('p');
        messageText.textContent = message.message;

        let timeCreatedText = document.createElement('h5');
        timeCreatedText.textContent = message.created_at;

        divCardMessage.appendChild(nameText);
        divCardMessage.appendChild(messageText);
        divCardMessage.appendChild(timeCreatedText);

        cardsMessageContainer.appendChild(divCardMessage);
    }
}