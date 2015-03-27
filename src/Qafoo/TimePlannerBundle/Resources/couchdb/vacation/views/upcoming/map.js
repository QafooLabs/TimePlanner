function (doc) {
    if (doc.type !== "Qafoo.TimePlannerBundle.Domain.Vacation") {
        return;
    }

    emit(doc.end, null);
}
