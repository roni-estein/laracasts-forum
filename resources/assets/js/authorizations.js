let user = window.App.user;

let authorizations = {
    owns(model, prop = 'user_id'){
        return model[prop] === user.id;
    }
};

module.exports = authorizations;
