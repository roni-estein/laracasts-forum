let user = window.App.user;

let authorizations = {
    owns(model, prop = 'user_id'){
        return model[prop] === user.id;
    },

    isAdmin() {
        return ['john@doe.com'].includes(user.email);
    }
};

module.exports = authorizations;
