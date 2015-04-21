function (doc) {
    if (doc.type !== "Qafoo.TimePlannerBundle.Domain.Vacation") {
        return;
    }

    var parseDate = function(date) {
            return new Date(date.substr(0, 4), date.substr(5, 2) - 1, date.substr(8, 2), 12, 0, 0);
        },
        end = parseDate(doc.end),
        start = parseDate(doc.start);

    emit([start.getFullYear(), doc._id], 1);
    emit([end.getFullYear(), doc._id], 1);
}
