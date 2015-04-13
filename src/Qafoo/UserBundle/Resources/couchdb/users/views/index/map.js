function (doc) {
    if (doc.type !== "Qafoo.UserBundle.Domain.FOSUserHelper") {
        return;
    }

    emit(['email', doc.email.email], null);
    emit(['token', doc.auth.confirmationToken], null);
}
