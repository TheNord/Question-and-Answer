import Token from './Token'
import AppStorage from './AppStorage'

class User {


    responseAfterLogin(res) {
        const access_token = res.data.access_token;
        const username = res.data.user;

        if (Token.isValid(access_token)) {
            AppStorage.store(access_token, username);
            window.location = '/forum'
        }
    }

    hasToken() {
        const storedToken = AppStorage.getToken();

        if (storedToken) {
            return Token.isValid(storedToken) ? true : this.logout()
        }

        return false;
    }

    loggedIn() {
        return this.hasToken()
    }

    checkAuth() {
        if (AppStorage.getToken()) {
            Token.checkToken()
        }
    }

    logout() {
        AppStorage.clear();
        window.location = '/forum'
    }

    name() {
        if(this.loggedIn()) {
            return AppStorage.getUser()
        }
    }

    id() {
        if(this.loggedIn()) {
            const payload = Token.payload(AppStorage.getToken());
            return payload.sub;
        }
    }

    own(id) {
        return this.id() === id
    }
}

export default User = new User();