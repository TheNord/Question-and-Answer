import User from './User'

class Exception {
    handle(error) {
        this.isError(error.response.data.error)
    }

    isError(error) {
        if (error === 'Token is Expired' ||
            error === 'Token is invalid' ||
            error === 'Authorization Token not found') {
            User.logout()
        }
    }
}

export default Exception = new Exception();