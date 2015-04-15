function (doc) {
    if (doc.type !== "Qafoo.TimePlannerBundle.Domain.Job") {
        return;
    }

    emit(doc.project, 1);
}
