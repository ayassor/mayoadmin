import axios from 'axios';

// Initialiser une nouvelle instance de Vue
new Vue({
    el: '#app',
    data: {
        loggedIn: true,
        username: '',
        password: ''
    },
    methods: {
        login() {
            print('ererererere');
            this.loggedIn = false;
            this.username = '';
            this.password = '';


            // Ici, vous pouvez implémenter la logique d'authentification, par exemple en utilisant une requête AJAX vers un backend
            // Pour cet exemple, nous définissons juste loggedIn à true si le nom d'utilisateur et le mot de passe sont remplis
           
            // if (this.username && this.password) {
            //     const credentials = {
            //     email: this.username,
            //     password: this.password
            // };

            //     // Appel à votre API pour vérifier les informations d'identification
            //     axios.post('http://35.237.39.146:9000/api/v1/auth/signin', credentials)
            //         .then(response => {
            //             // Gérer la réponse de l'API (par exemple, rediriger l'utilisateur si la connexion est réussie)
            //             console.log('Connexion réussie', response.data);
            //             // this.loggedIn = true;

            //         })
            //         .catch(error => {
            //             // Gérer les erreurs (par exemple, afficher un message d'erreur à l'utilisateur)
            //             console.error('Erreur de connexion', error);
            //         });
            // } else {
            //     alert('Veuillez remplir tous les champs.');
            // }
        },
        logout() {
            this.loggedIn = false;
            this.username = '';
            this.password = '';
        }
    }
});