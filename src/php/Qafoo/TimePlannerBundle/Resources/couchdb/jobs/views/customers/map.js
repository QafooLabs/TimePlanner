function (doc) {
    if (doc.type !== "Qafoo.TimePlannerBundle.Domain.Job") {
        return;
    }

    emit(doc.customer, 1);
}
