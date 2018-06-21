let user = window.App.user;

let authorizations = {
    updateReply(reply) {
        return user.id === reply.user_id;
    }
};

module.exports = authorizations;
