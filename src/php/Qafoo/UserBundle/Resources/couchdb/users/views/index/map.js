function (doc) {
    if (doc.type !== "Qafoo.UserBundle.Domain.FOSUser") {
        return;
    }

    emit(['email', doc.email.email], null);
    emit(['token', doc.auth.confirmationToken], null);
}
