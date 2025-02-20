Copy
require('./bootstrap');

const app = new Vue({
    el: '#app',
    data: {
        messages: [],
        newMessage: ''
    },
    mounted() {
        this.fetchMessages();
        this.listenForNewMessages();
    },
    methods: {
        fetchMessages() {
            axios.get('/messages').then(response => {
                this.messages = response.data;
            });
        },
        sendMessage() {
            axios.post('/send-message', { message: this.newMessage }).then(response => {
                this.newMessage = '';
            });
        },
        listenForNewMessages() {
            window.Echo.channel('chat')
                .listen('MessageSent', (data) => {
                    this.messages.push(data.message);
                });
        }
    }
});