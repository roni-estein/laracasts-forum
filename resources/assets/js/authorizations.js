let user = window.App.user;

let authorizations = {
    owns(model, prop = 'user_id'){
        return model[prop] === user.id;
    },

    isAdmin() {
        console.log (user.email);
        return ['JohnDoe'].includes(user.name);
    }
};

module.exports = authorizations;
